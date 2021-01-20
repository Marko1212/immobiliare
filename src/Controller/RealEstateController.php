<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RealEstateController extends AbstractController
{
    /**
     * @Route("/mes-biens", name="real_estate_list")
     */
    public function index(): Response
    {
        $sizes = [
            1 => 'Studio',
            2 => 'T2',
            3 => 'T3',
            4 => 'T4',
            5 => 'T5',
        ];

        $repository = $this->getDoctrine()->getRepository(RealEstate::class);
        $properties = $repository->findAll();

        return $this->render('real_estate/index.html.twig', [
            'sizes' => $sizes,
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/nos-biens/{slug}_{id}", name = "real_estate_show")
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
    public function create(Request $request, SluggerInterface $slugger) : Response {

        $realEstate = new RealEstate();

        $form = $this->createForm(RealEstateType::class, $realEstate);

        $form -> handleRequest($request);

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

           //je dois ajouter l'objet dans la BDD
            $entityManager = $this->getDoctrine() ->getManager();
            $entityManager->persist($realEstate);
            $entityManager->flush();

            // faire une redirection après l'ajout et affiche un message de succès
            // après le flush() on peut récupérer le id de l'objet, ce qu'on fait ci-dessous
            $this->addFlash('success', 'Votre annonce '.$realEstate->getId().' a bien été ajoutée');
            return $this->redirectToRoute('real_estate_list');
            // Faire la redirection vers la liste des annonces et afficher les messages flash sur le html

        }

        return $this->render('real_estate/create.html.twig', ['realEstateForm' => $form->createView()]);
}
}
