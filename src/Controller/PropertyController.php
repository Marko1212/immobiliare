<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/property", name="property")
     * Page qui liste les annonces immobilières
     */
    public function index(): Response
    {
        // Pour démarrer, on va créer un tableau d'annonces

        $properties = ['Maison avec piscine',
                      'Appartement avec terrasse',
                        'Studio centre ville'];

        dump($properties);

        return $this->render('property/index.html.twig', ['properties' => $properties]);
    }

    /**
     * @Route("property/maison", name="property_show")
     * Page qui affiche une annonce
     */
    public function show() : Response {
        return $this->render('property/show.html.twig');
    }
}
