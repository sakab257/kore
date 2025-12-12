<?php
/**
 * KORE - Point d'entrée unique (Front Controller)
 * Toutes les requêtes passent par ici.
 */

// On démarre la session tout de suite (indispensable pour le panier et le login)
session_start();

// Définition des chemins absolus pour éviter les soucis d'include
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('PUBLIC_PATH', ROOT . '/public');

// Mode DEV : on affiche toutes les erreurs (à désactiver en production !)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Autoloader magique
 * Plus besoin de faire des require partout, ça charge les classes à la volée.
 */
spl_autoload_register(function ($className) {
    $paths = [
        APP . '/core/' . $className . '.php',
        APP . '/controllers/' . $className . '.php',
        APP . '/models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Chargement de la config BDD
require_once APP . '/config/db.php';

class Router
{
    private string $controller = 'HomeController';
    private string $method = 'index';
    private array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (isset($url[0]) && !empty($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';

            if (file_exists(APP . '/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once APP . '/controllers/' . $this->controller . '.php';
        $controllerInstance = new $this->controller;

        if (isset($url[1]) && !empty($url[1])) {
            if (method_exists($controllerInstance, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    /**
     * Découpe l'URL pour comprendre la demande
     * Ex: /product/show/42 devient ['product', 'show', '42']
     */
    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }
}

// Lancement du routeur
new Router();