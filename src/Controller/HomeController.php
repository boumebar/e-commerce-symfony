<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{


    /**
     * @Route("/", name="home")
     */
    public function home(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 9);

        return $this->render('home.html.twig', ['products' => $products]);
    }
}
