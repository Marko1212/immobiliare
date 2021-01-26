<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RealEstateController extends AbstractController
{
    /**
     * @Route("/tous-les-biens", name="real_estate_list")
     */
    public function index(Request $request): Response
    {
        $sizes = [
            1 => 'Studio',
            2 => 'T2',
            3 => 'T3',
            4 => 'T4',
            5 => 'T5',
        ];

        $repository = $this->getDoctrine()->getRepository(RealEstate::class);
        $properties = $repository->findAllWithFilters(
             //$request->query->get('surface', 0)
            $request->get('surface', 0),
            $request->get('budget', 999999999999999999),
            $request->get('size')
        );

        return $this->render('real_estate/index.html.twig', [
            'sizes' => $sizes,
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/nos-biens/{slug}_{id}", name = "real_estate_show", requirements={"slug"="[a-z0-9\-]*"})
     */
    public function show(RealEstate $property)
    {
        //  $property = $this->getDoctrine()->getRepository(RealEstate::class)->find($id);

        //   if(!$property) {
        //       throw $this->createNotFoundException();
        // }
        return $this->render('real_estate/show.html.twig', ['property' => $property]);
    }

    /**
     * @Route("creer-un-bien", name="real_estate_create")
     */
    public function create(Request $request, SluggerInterface $slugger): Response
    {

        $realEstate = new RealEstate();

        $form = $this->createForm(RealEstateType::class, $realEstate);

        $form->handleRequest($request);

        // On doit vérifier que le formulaire est soumis et valide

        if ($form->isSubmitted() && $form->isValid()) {
            //ici on va ajouter l'annonce dans la base...

            dump($realEstate);

            // On génère le slug et on fait l'upload avant l'ajout en base

            $slug = $slugger->slug($realEstate->getTitle())->lower();
            //le nom de l'annonce devient : le-nom-de-l-annonce
            $realEstate->setSlug($slug);

            //du fait de l'upload, comment récupérer l'image ?
            // Equivalent du $_FILES['image']
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            if ($image) {
                $fileName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $fileName);
                $realEstate->setImage($fileName);
            } else {
                $realEstate->setImage('default.png');
            }
            //dump(__DIR__);
            //dd($image); // dump & die

            //on rajoute le propriétaire à l'annonce
            //getUser() renvoie le propriétaire, qui est l'utilisateur connecté
            $realEstate->setOwner($this->getUser());

            //je dois ajouter l'objet dans la BDD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($realEstate);
            $entityManager->flush();

            // faire une redirection après l'ajout et affiche un message de succès
            // après le flush() on peut récupérer le id de l'objet, ce qu'on fait ci-dessous
            $this->addFlash('success', 'Votre annonce ' . $realEstate->getId() . ' a bien été ajoutée');
            return $this->redirectToRoute('real_estate_list');
            // Faire la redirection vers la liste des annonces et afficher les messages flash sur le html

        }

        return $this->render('real_estate/create.html.twig', ['realEstateForm' => $form->createView()]);
    }

    /**
     * @Route("nos-biens/modifier/{id}", name="real_estate_edit")
     */
    public function edit(Request $request, RealEstate $realEstate)
    {
        $form = $this->createForm(RealEstateType::class, $realEstate);

        // Faire le traitement du formulaire

        //on écrit les données de la requête dans l'objet $form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // pas besoin de faire de persist...Doctrine va détecter
            // automatiquement qu'il doit faire un UPDATE
            // ATTENTION si on change le slug aux histoires de redirection (sites de e-commerce...)

            $image = $form->get('image')->getData();
            if ($image) {
                $defaultImages = ['default.png', 'fixtures/1.jpg', 'fixtures/2.jpg','fixtures/3.jpg'];
                //on supprime uniquement les images vraiment upload-ées par les utilisateurs
                if ($realEstate->getImage() && !in_array($realEstate->getImage(), $defaultImages)) {
                    $fs= new Filesystem();
                    $fs->remove($this->getParameter('upload_directory').'/'.$realEstate->getImage());
                }

                $fileName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $fileName);
                $realEstate->setImage($fileName);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'L\'annonce a bien été modifiée');
            return $this->redirectToRoute('real_estate_list');
        }
        return $this->render('real_estate/edit.html.twig', [
            'realEstateForm' => $form->createView(),
            'realEstate' => $realEstate,
        ]);

    }

    /**
     * @Route("nos-biens/supprimer/{id}", name="real_estate_delete")
     */
    public function delete(RealEstate $realEstate)
    {
        //il faudrait aussi effacer le fichier image ce qui n'a pas été fait ici (copié collé du code ci-dessus, méthode edit())
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($realEstate);

        $entityManager->flush();

        $this->addFlash('danger', 'L\'annonce a bien été supprimée');

        return $this->redirectToRoute('real_estate_list');
    }

}
