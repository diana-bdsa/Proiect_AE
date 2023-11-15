<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Homepage extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $user = $this->getUser() ?? null; //ia user daca exista/e autentificat

        $products = $productRepository->findBy(
            ['isAvailable' => true], //se iau toate produsele din baza de data care au campul is_available=true
        );
        $cart = $request->getSession()->get('cart_data') ?? [];//ia variabila de sesiune cart_data unde sunt datele despre cos

        return $this->render('homepage/index.html.twig', [ //intoarce pagina html cu cele 3 variabile user, produse si cos
            'user' => $user,
            'products' => $products,
            'cart' => $cart
        ]);
    }

    #[Route('/category/{category_name}', name: 'category')]
    public function category(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response //Functie pentru categorie
    {
        $cart = $request->getSession()->get('cart_data');
        $user = $this->getUser() ?? null;

        $categoryName = $request->get('category_name'); //ia variabila category_name din URL
        $category = $categoryRepository->findOneBy(['name' => $categoryName]); //cauta o categorie cu numele variabilei
        $products = $productRepository->findBy(
            ['category' => $category]
        );

        return $this->render('homepage/index.html.twig', [
            'user' => $user,
            'products' => $products,
            'cart' => $cart
        ]);
    }
}