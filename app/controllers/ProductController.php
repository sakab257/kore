<?php

class ProductController extends Controller
{
    public function index()
    {
        $productModel = $this->model('Product');
        
        // 1. On récupère les filtres depuis l'URL (méthode GET)
        // L'opérateur '??' met une valeur par défaut si le paramètre n'existe pas
        $search = $_GET['q'] ?? null;
        $minPrice = $_GET['min_price'] ?? null;
        $maxPrice = $_GET['max_price'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';

        // 2. On appelle la nouvelle méthode du modèle avec ces paramètres
        $products = $productModel->filterAll($search, $minPrice, $maxPrice, $sort);

        // 3. On envoie les résultats à la vue
        $this->view('product/index', [
            'title' => 'Tous les produits - KORE',
            'products' => $products
        ]);
    }

    public function show($id = null)
    {
        if (!$id) {
            $this->redirect('/');
            return;
        }

        $productModel = $this->model('Product');
        $product = $productModel->getFullProduct($id);

        if (!$product) {
            $this->redirect('/');
            return;
        }

        $this->view('product/show', [
            'title' => $product['name'] . ' - KORE',
            'product' => $product
        ]);
    }
}