<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, $page = 1): Response
    {
        // Pour démarrer, on va créer un tableau d'annonces

        $properties = $this->properties;

        dump($page);
        // Equivalent au var_dump
        dump($properties);

        // On peut récupérer des informations liées à la requête http

        $surface = $request->query->get('surface'); // équivaut à $_GET['surface']
        $budget = $request->query->get('budget');
        $budget = $request->query->get('size');

        // il nous manque la BDD pour faire le tri

        dump($surface);

        //dump($request);

        // On prépare un tableau avec les tailles des biens

        $sizes = [
            1 => 'Studio',
            2 => 'T2',
            3 => 'T3',
            4 => 'T4',
            5 => 'T5',
        ];

        return $this->render('property/index.html.twig', ['properties' => $properties, 'sizes' => $sizes]);
    }

    /**
     * @Route("/property/{slug}", name="property_show")
     * Page qui affiche une annonce avec un paramètre dynamique
     */
    public function show($slug) : Response {

        //ici, on peut vérifier que le slug soit dans notre tableau

        if (!in_array($slug, array_column($this->properties, 'title'))) {
            throw $this->createNotFoundException();
        }

        return $this->render('property/show.html.twig', ['slug' => $slug]);
    }

    /**
     * @Route("/property.{_format}", name="property_api")
     */

    public function api(): Response
    {
       // return $this->json($this->properties);

        return new Response(json_encode($this->properties));
    }

}
