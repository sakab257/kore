<?php
/**
 * Contrôleur Parent (Base Controller)
 * Tous tes contrôleurs (ProductController, CartController...) vont hériter de celui-ci.
 * Il contient les outils communs : charger une vue, charger un modèle, rediriger, renvoyer du JSON...
 */

class Controller
{
    /**
     * Affiche une "Vue" (le fichier visuel .php dans /views)
     * $data est un tableau de données qu'on veut passer à la page (ex: liste des produits)
     */
    protected function view(string $view, array $data = []): void
    {
        $viewPath = APP . '/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            // Cette fonction magique transforme ['titre' => 'Accueil'] en variable $titre = 'Accueil'
            extract($data);

            // On inclut le fichier, et il aura accès aux variables créées juste au-dessus
            require_once $viewPath;
        } else {
            die("Oups, la vue n'existe pas : " . $view);
        }
    }

    /**
     * Charge un Modèle pour interagir avec la BDD
     */
    protected function model(string $model): object
    {
        $modelPath = APP . '/models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Impossible de trouver le modèle : " . $model);
        }
    }

    /**
     * Redirection vers une autre page du site
     */
    protected function redirect(string $url): void
    {
        // On s'assure qu'il y a bien un slash au début
        if ($url[0] !== '/') {
            $url = '/' . $url;
        }

        // Attention : Adapte '/kore/public' si ton dossier change de nom !
        header('Location: /kore/public' . $url);
        exit;
    }

    /**
     * Envoie des données en format JSON (Indispensable pour nos requêtes AJAX/JS sans recharger la page)
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Vérifie si la requête vient du Javascript (AJAX)
     */
    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // Est-ce que le visiteur est connecté ?
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    // Récupère l'ID du user connecté (ou null s'il n'est pas là)
    protected function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Barrière de sécurité : Si le user n'est pas connecté, on l'envoie se logger.
     * On sauvegarde l'URL d'où il vient pour le rediriger au bon endroit après connexion.
     */
    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('/auth/login');
        }
    }

    /**
     * Nettoyage des données entrantes (Sécurité de base contre les failles XSS)
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }

        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sécurité CSRF : Vérifie que le token envoyé par le formulaire correspond à celui en session.
     * (Empêche qu'un autre site ne soumette un formulaire à la place de l'utilisateur)
     */
    protected function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Génère un nouveau token CSRF unique pour la session
    protected function generateCsrfToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}