<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Services\SuperCart;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add(RealEstate $realEstate, SuperCart $superCart): Response
    {
        // Avant d'ajouter au panier, on va vérifier si l'annonce est toujours
        // en vente ou que l'annonce n'est pas déjà dans le panier

        if ($realEstate->getSold()) {
            $this->addFlash('danger', 'Trop tard, l\'annonce est vendue');
        } else if ($superCart->hasItem($realEstate->getId())) {
            $this->addFlash('danger', 'Vous avez déjà choisi cette annonce');
        } else {

            //Ajouter l'annonce dans la session
            $superCart->addItem($realEstate);

        }


        //Rediriger vers la page de l'annonce

        return $this->redirectToRoute('real_estate_show', [
            'id' => $realEstate->getId(),
            'slug'=>$realEstate->getSlug(),
        ]);

    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove(RealEstate $realEstate, SuperCart $superCart)
    {
        $superCart->removeItem($realEstate->getId());

        return $this->redirectToRoute('cart_index');

    }

    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(SuperCart $superCart, $stripeKey) {

        //dd($stripeKey);

        Stripe::setApiKey($stripeKey);

        //vérification non cohérente dans la réalité
        //c'est pour éviter le bug, car
        //Stripe autorise un montant maximum pour le montant
        //on ne peut pas passer plus de 1000000
        $total = $superCart->total();

        if ($total >= 9999999) {
            $total = 999999;
        }

        $clientSecret = null;

        if ($total > 0) {
            //On va créer l'intention de paiement
            $paymentIntent = PaymentIntent::create([
                'amount' => $total * 100, //10.99 devient 1099
                'currency' => 'eur',
            ]);
        $clientSecret = $paymentIntent->client_secret;
        }
        return $this->render('cart/index.html.twig', [
            'items' => $superCart->getItems(),
            // le client_secret permet d'effectuer le paiement plus tard
            'clientSecret' => $clientSecret,
        ]);
    }

    /**
     * @Route("/cart/success/{id}", name="cart_success")
     */
    public function success($id, $stripeKey, SuperCart $superCart, MailerInterface $mailer)
    {
        Stripe::setApiKey($stripeKey);
        $paymentIntent = PaymentIntent::retrieve($id);
        dump($paymentIntent);

        //Envoyer le email ...

        //Je rédige le mail..
        $email = (new Email())
                ->from('commande@immobiliare.com')
                ->to($this->getUser()->getEmail())
                ->subject('Votre commande')
                ->html('<h1>Hello</h1>');

        //J'envoie le mail...
        $mailer->send($email);

        return $this->render('cart/success.html.twig');
    }

}
