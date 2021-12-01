<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    protected $productRepository;
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }


    /**
     * @Route("/cart/add/{id<\d+>}", name="cart_add")
     */
    public function add($id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit n'existe pas !");
        }

        $this->cartService->add($id);

        $this->addFlash(
            'success',
            'Votre article a été ajouté au panier'
        );

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }
        return $this->redirectToRoute('product_show', [
            "category_slug" => $product->getCategory()->getSlug(),
            "slug" => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart/delete/{id<\d+>}", name="cart_delete")
     */
    public function delete($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit n° $id n'existe pas");
        }

        $this->cartService->remove($id);

        $this->addFlash("success", "Le produit a bien été supprimé ");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart" , name="cart_show")
     */
    public function show()
    {

        $detailsCart = $this->cartService->getDetailedCartItems();

        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', ['items' => $detailsCart, 'total' => $total]);
    }

    /**
     * @Route("/cart/decrement/{id<\d+>}" , name="cart_decrement")
     */
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit n° $id n'existe pas");
        }

        $this->cartService->decrement($id);
        $this->addFlash("success", "Le produit a bien été enlevé ");
        return $this->redirectToRoute("cart_show");
    }
}
