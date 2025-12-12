<?php

class ReviewController extends Controller
{
    public function submit()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        // Vérification CSRF
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/');
            return;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = $this->sanitize($_POST['comment'] ?? '');

        if (!$productId || $rating < 1 || $rating > 5) {
            $_SESSION['error'] = "Veuillez donner une note valide.";
            $this->redirect('/product/show/' . $productId);
            return;
        }

        $reviewModel = $this->model('Review');

        // Empêcher le spam d'avis
        if ($reviewModel->hasReviewed($this->getUserId(), $productId)) {
            $_SESSION['error'] = "Vous avez déjà donné votre avis sur ce produit.";
        } else {
            if ($reviewModel->createReview($this->getUserId(), $productId, $rating, $comment)) {
                $_SESSION['success'] = "Merci pour votre avis !";
            } else {
                $_SESSION['error'] = "Une erreur est survenue.";
            }
        }

        $this->redirect('/product/show/' . $productId);
    }
}