<?php

class CheckoutController extends Controller
{
    public function index()
    {
        // 1. Sécurité : User connecté et Panier non vide
        $this->requireAuth();
        
        $cartModel = $this->model('Cart');
        if ($cartModel->getItemCount() === 0) {
            $this->redirect('/cart');
            return;
        }

        $items = $cartModel->getCartItems();
        $total = $cartModel->getTotal();

        // On pré-remplit le formulaire avec les infos du user
        $userModel = $this->model('User');
        $user = $userModel->findById($this->getUserId());

        $this->view('checkout/index', [
            'title' => 'Paiement - KORE',
            'items' => $items,
            'total' => $total,
            'user' => $user,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    public function submit()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/checkout');
            return;
        }

        // Vérification CSRF
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/checkout');
            return;
        }

        $cartModel = $this->model('Cart');
        $items = $cartModel->getCartItems();
        $total = $cartModel->getTotal();

        if (empty($items)) {
            $this->redirect('/cart');
            return;
        }

        // Création de la commande via le Modèle
        $orderModel = $this->model('Order');
        $orderId = $orderModel->createOrder($this->getUserId(), $items, $total);

        if ($orderId) {
            // Si succès : on vide le panier et on redirige vers la confirmation
            $cartModel->clear();
            $this->redirect('/checkout/success/' . $orderId);
        } else {
            // Si erreur (ex: stock épuisé entre temps)
            $_SESSION['error'] = "Une erreur est survenue lors de la commande. Veuillez réessayer.";
            $this->redirect('/checkout');
        }
    }

    public function success($orderId = null)
    {
        $this->requireAuth();

        if (!$orderId) {
            $this->redirect('/');
            return;
        }

        $this->view('checkout/success', [
            'title' => 'Commande confirmée - KORE',
            'order_id' => $orderId
        ]);
    }
}