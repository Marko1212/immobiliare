<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 *
 * Un service est une classe dont l'objectif est
 * d'être réutilisable. Elle sert aussi à organiser
 * son code.
 */

class SuperCart
{
    private $session;

    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }
    public function addItem($item)
    {
        $products = $this->session->get('products', []);
        $products[] = $item;
        $this->session->set('products', $products);

    }

}