<?php

class CartController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index()
    {
        $cartModel = $this->model('Cart');
        $cartItems = $cartModel->getCartItems();
        $total = $cartModel->getTotal();

        $this->view('cart/index', [
            'title' => 'Panier - KORE',
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function add()
    {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $productId = $data['product_id'] ?? null;
        $variantId = $data['variant_id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$productId || !$variantId) {
            $this->json(['success' => false, 'message' => 'Données manquantes'], 400);
            return;
        }

        $cartModel = $this->model('Cart');
        $result = $cartModel->addItem($productId, $variantId, $quantity);

        if ($result['success']) {
            $this->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'cartCount' => $cartModel->getItemCount()
            ]);
        } else {
            $this->json(['success' => false, 'message' => $result['message']], 400);
        }
    }

    public function update()
    {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cart');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $variantId = $data['variant_id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if (!$variantId || $quantity < 0) {
            $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            return;
        }

        $cartModel = $this->model('Cart');

        if ($quantity == 0) {
            $cartModel->removeItem($variantId);
        } else {
            $cartModel->updateQuantity($variantId, $quantity);
        }

        $this->json([
            'success' => true,
            'cartCount' => $cartModel->getItemCount(),
            'total' => $cartModel->getTotal()
        ]);
    }

    public function remove()
    {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cart');
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $variantId = $data['variant_id'] ?? null;

        if (!$variantId) {
            $this->json(['success' => false, 'message' => 'ID manquant'], 400);
            return;
        }

        $cartModel = $this->model('Cart');
        $cartModel->removeItem($variantId);

        $this->json([
            'success' => true,
            'cartCount' => $cartModel->getItemCount(),
            'total' => $cartModel->getTotal()
        ]);
    }

    public function count()
    {
        $cartModel = $this->model('Cart');
        $this->json(['count' => $cartModel->getItemCount()]);
    }

    public function clear()
    {
        $_SESSION['cart'] = [];
        $this->redirect('/');
    }
}
