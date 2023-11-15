<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Checkout extends AbstractController
{
    #[Route('/checkout', name: 'checkout', methods: ['GET'])]
    public function checkout(Request $request): Response
    {
        $cartData = $request->getSession()->get('cart_data');

        if (empty($cartData)) {
            return $this->redirectToRoute('homepage'); //daca cosul este gol te redirectioneaza la homepage
        }


        return $this->render('checkout/index.html.twig', [
            'cart' => $cartData
        ]);
    }

    #[Route('/checkoutaction', name: 'checkoutAction', methods: ['POST'])]
    public function checkoutAction(Request $request)
    {
        $cartData = json_decode($request->getContent(), true); //ia datele din cos

        if (empty($cartData)) {
            return new JsonResponse(['error' => 'Empty cart data received'], 400);
        }

        // Salveaza datele din cos in sesiune
        $request->getSession()->set('cart_data', $cartData);

        return new JsonResponse(['message' => 'Cart data saved in session!']);
    }
}