<?php


namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }


    /**
     * Ajouter un article dans le panier
     */

    public function add($id)
    {

        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;


        $this->saveCart($cart);
    }

    /**
     * supprime un article dans le panier
     */
    public function remove($id)
    {

        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        unset($cart[$id]);
        $this->saveCart($cart);
    }

    public function decrement($id)
    {
        $cart = $this->getCart();
        if (!array_key_exists($id, $cart)) {
            return;
        }
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }
        $cart[$id]--;


        $this->saveCart($cart);
    }

    /**
     *  Renvoi tout les articles du panier avec les quantites
     * @return array
     */
    public function getDetailedCartItems(): array
    {
        $detailsCart = [];

        foreach ($this->getCart() as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailsCart[] = new CartItem($product, $quantity);
        }

        return $detailsCart;
    }


    /**
     * Renvoi le total du panier 
     *
     * @return integer
     */
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $quantity;
        }

        return $total;
    }


    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }
}
