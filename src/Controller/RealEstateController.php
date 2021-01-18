<?php

namespace App\Controller;

use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealEstateController extends AbstractController
{
    /**
     * @Route("/mes-biens", name="real_estate_list")
     */
    public function index(): Response
    {
        return $this->render('real_estate/index.html');
    }

    /**
     * @Route("creer-un-bien", name="real_estate_create")
     */
    public function create() : Response {

        $form = $this->createForm(RealEstateType::class);
        return $this->render('real_estate/create.html.twig', ['realEstateForm' => $form->createView()]);
}
}
