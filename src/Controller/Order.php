<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Order extends AbstractController
{
    #[Route('/orders', name: 'user_orders')]
    public function orders(Request $request, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser() ?? null;

        $orders = $orderRepository->findBy(['user' => $user]); //pune in variabila orders toate comenzile utilizatorului

        return $this->render('user/orders.html.twig', [
           'user' => $user,
           'orders' => $orders
        ]);
    }
}