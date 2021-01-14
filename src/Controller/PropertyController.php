<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

private $properties = [
['title' => 'Maison avec piscine'],
['title' => 'Appartement avec terrasse'],
['title' => 'Studio centre ville']
];

    /**
     * @Route("/property/{page}", name="property_list", requirements = {"page" = "\d+"})
     * Page qui liste les annonces immobilières
     */
    public function index($page = 1): Response
    {
        // Pour démarrer, on va créer un tableau d'annonces

        $properties = $this->properties;

        dump($page);
        // Equivalent au var_dump
        dump($properties);



        return $this->render('property/index.html.twig', ['properties' => $properties]);
    }

    /**
     * @Route("property/{slug}", name="property_show")
     * Page qui affiche une annonce avec un paramètre dynamique
     */
    public function show($slug) : Response {

        //ici, on peut vérifier que le slug soit dans notre tableau

        if (!in_array($slug, array_column($this->properties, 'title'))) {
            throw $this->createNotFoundException();
        }

        return $this->render('property/show.html.twig', ['slug' => $slug]);
    }
}
