<?php

namespace App\Controller;

use App\Repository\RealEstateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/api/search/{query}", name="api_search")
     */
    public function index($query = '', RealEstateRepository $repository): Response
    {
        $realEstates = $repository->search($query);
        //On renvoie du JSON car c'est une API
       // return new Response(json_encode(['query' => $query])); //retourne du content type html (réponse)
        //donc besoin de faire un parse du JSON côté JS
        return $this->json([//'results' => $realEstates,
                            'html' => $this->renderView('real_estate/_real_estate.html.twig', ['properties' => $realEstates]),
        ]);
        // retourne du JSON
        // grâce à Symfony, retour du JSON (content type JSON de la réponse)
        //JS le convertit alors automatiquement en objet JS
        //donc, en fonction du serveur, besoin ou non de 'parser' la réponse en objet
    }
}
