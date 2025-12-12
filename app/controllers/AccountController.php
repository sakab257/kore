<?php

class AccountController extends Controller
{
    /**
     * Page d'accueil du compte (Tableau de bord)
     */
    public function index()
    {
        $this->requireAuth();
        $userId = $this->getUserId();

        // 1. Mise à jour automatique des statuts (Simulation temps réel)
        $orderModel = $this->model('Order');
        $orderModel->refreshStatus($userId);

        // 2. Récupération des données
        $userModel = $this->model('User');
        $user = $userModel->findById($userId);
        $orders = $userModel->getOrders($userId);

        $this->view('account/index', [
            'title' => 'Mon Compte - KORE',
            'user' => $user,
            'orders' => $orders
        ]);
    }

    /**
     * Page de détail d'une commande spécifique
     */
    public function order($id)
    {
        $this->requireAuth();
        $userId = $this->getUserId();

        // Mise à jour du statut avant affichage
        $this->model('Order')->refreshStatus($userId);

        $userModel = $this->model('User');
        $order = $userModel->getOrderDetails($id, $userId);

        if (!$order) {
            $this->redirect('/account');
            return;
        }

        $this->view('account/order', [
            'title' => 'Commande #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . ' - KORE',
            'order' => $order
        ]);
    }
}