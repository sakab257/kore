<?php

class AuthController extends Controller
{
    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
            return;
        }

        $this->view('auth/register', [
            'title' => 'Créer un compte - KORE',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    private function handleRegister()
    {
        $firstname = $this->sanitize($_POST['firstname'] ?? '');
        $lastname = $this->sanitize($_POST['lastname'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $csrfToken = $_POST['csrf_token'] ?? '';

        if (!$this->validateCsrfToken($csrfToken)) {
            $_SESSION['error'] = 'Token de sécurité invalide';
            $this->redirect('/auth/register');
            return;
        }

        $errors = [];

        if (empty($firstname)) $errors[] = 'Le prénom est requis';
        if (empty($lastname)) $errors[] = 'Le nom est requis';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas';
        }

        $userModel = $this->model('User');

        if ($userModel->exists('email', $email)) {
            $errors[] = 'Cet email est déjà utilisé';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/auth/register');
            return;
        }

        $userId = $userModel->create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_firstname'] = $firstname;
        $_SESSION['success'] = 'Compte créé avec succès';

        $this->redirect('/');
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }

        $this->view('auth/login', [
            'title' => 'Connexion - KORE',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    private function handleLogin()
    {
        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrfToken = $_POST['csrf_token'] ?? '';

        if (!$this->validateCsrfToken($csrfToken)) {
            $_SESSION['error'] = 'Token de sécurité invalide';
            $this->redirect('/auth/login');
            return;
        }

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Tous les champs sont requis';
            $this->redirect('/auth/login');
            return;
        }

        $userModel = $this->model('User');
        $user = $userModel->findOneBy('email', $email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Email ou mot de passe incorrect';
            $this->redirect('/auth/login');
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_firstname'] = $user['firstname'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['success'] = 'Bienvenue ' . $user['firstname'] . ' !';

        $redirectTo = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);

        $this->redirect($redirectTo);
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_firstname']);
        unset($_SESSION['user_email']);
        $_SESSION['success'] = 'Vous avez été déconnecté';
        $this->redirect('/');
    }
}
