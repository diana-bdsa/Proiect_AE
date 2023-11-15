<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        //luam toate datele din formular
        $cartData = $request->getSession()->get('cart_data');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $email = $request->get('email');
        $address = $request->get('address');
        $country = $request->get('country');
        $state = $request->get('state');
        $zip = $request->get('zip');
        $price = $request->get('price');

        if(empty($firstName) || empty($lastName) || empty($email) || empty($address)) { //daca nu sunt completate, redirectioneaza inapoi la formularul de checkout
            return $this->redirectToRoute('checkout');
        }

        $order = new Order(); //cream entiatatea order si setam parameterii din formularul de mai sus
        $order->setFirstName($firstName);
        $order->setLastName($lastName);
        $order->setEmail($email);
        $order->setAddress($address);
        $order->setCountry($country);
        $order->setState($state);
        $order->setZip($zip);
        $order->setRevenue($price);
        $order->setIsPaid(false); //initial se seteaza pe fals

        $user = $userRepository->findOneBy(['email' => $email]); //daca gaseste un user care are si email adaugat, il adauga in formular
        if ($user) {
            $order->setUser($user);
        }

        $entityManager->persist($order);

        foreach ($cartData as $data) { //cautam produsul din baza de date si il adaugam la comanda
            $product = $productRepository->findOneBy(['id' => $data['id']]);

            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($data['quantity']);

            $entityManager->persist($orderItem);
        }

        $entityManager->flush(); //se salveaza comanda in baza de date

        return $this->render('stripe/index.html.twig', [
            'price' => $price,
            'stripe_key' => $_ENV['STRIPE_KEY'],
            'order_id' => $order->getId(),
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request, OrderRepository $orderRepository, EntityManagerInterface $entityManager)
    {
       //se ia order id si suma cosului
        $orderId = (int) $request->get('order_id');
        $amount = (float) $request->get('amount');

        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Charge::create ([
            "amount" => $amount * 100,
            "currency" => "RON",
            "source" => $request->request->get('stripeToken'),
            "description" => "Ecom Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );

        $order = $orderRepository->findOneBy(['id' => $orderId]); //cauta comanda care a fost platita cu success
        $order->setIsPaid(true); //schimba campul din fals in true s salveaza in baza de date
        $entityManager->persist($order);
        $entityManager->flush();


        $request->getSession()->set('cart_data', []); //goleste datele din cos

        return $this->redirectToRoute('app_stripe', ['payment' => true], Response::HTTP_SEE_OTHER); //
    }
}
