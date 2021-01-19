<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        $properties = [];

        return $this->render('real_estate/index.html.twig', [
            'sizes' => $sizes,
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("creer-un-bien", name="real_estate_create")
     */
    public function create(Request $request) : Response {

        $realEstate = new RealEstate();

        $form = $this->createForm(RealEstateType::class, $realEstate);

        $form -> handleRequest($request);

        // On doit vérifier que le formulaire est soumis et valide

        if ($form->isSubmitted() && $form->isValid()) {
            //ici on va ajouter l'annonce dans la base...

           dump($realEstate);

           //je dois ajouter l'objet dans la BDD
            $entityManager = $this->getDoctrine() ->getManager();
            $entityManager->persist($realEstate);
            $entityManager->flush();


        }

        return $this->render('real_estate/create.html.twig', ['realEstateForm' => $form->createView()]);
}
}
