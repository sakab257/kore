<?php

class FavoriteController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        $favoriteModel = $this->model('Favorite');
        $favorites = $favoriteModel->getUserFavorites($this->getUserId());

        $this->view('favorites/index', [
            'title' => 'Mes favoris - KORE',
            'favorites' => $favorites
        ]);
    }

    public function toggle()
    {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'Vous devez être connecté'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;

        if (!$productId) {
            $this->json(['success' => false, 'message' => 'ID produit manquant'], 400);
            return;
        }

        $favoriteModel = $this->model('Favorite');
        $userId = $this->getUserId();

        $isFavorite = $favoriteModel->isFavorite($userId, $productId);

        if ($isFavorite) {
            $favoriteModel->remove($userId, $productId);
            $this->json(['success' => true, 'is_favorite' => false]);
        } else {
            $favoriteModel->add($userId, $productId);
            $this->json(['success' => true, 'is_favorite' => true]);
        }
    }

    public function check()
    {
        if (!$this->isAjax()) {
            $this->redirect('/');
            return;
        }

        if (!$this->isLoggedIn()) {
            $this->json(['is_favorite' => false]);
            return;
        }

        $productId = $_GET['product_id'] ?? null;

        if (!$productId) {
            $this->json(['is_favorite' => false]);
            return;
        }

        $favoriteModel = $this->model('Favorite');
        $isFavorite = $favoriteModel->isFavorite($this->getUserId(), $productId);

        $this->json(['is_favorite' => $isFavorite]);
    }
}
