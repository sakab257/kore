<?php

class HomeController extends Controller
{
    public function index()
    {
        $productModel = $this->model('Product');
        $products = $productModel->getAllWithImages();

        $this->view('home', [
            'title' => 'KORE - Vêtements Premium',
            'products' => $products
        ]);
    }
}
