<?php require_once APP . '/views/templates/header.php'; ?>

<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <h1>Connexion</h1>
            <p class="text-secondary mb-3">Bienvenue sur KORE</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <p><?= htmlspecialchars($_SESSION['error']) ?></p>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/kore/public/auth/login" method="POST" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-large">Se connecter</button>

                <p class="auth-link">
                    Pas encore de compte ?
                    <a href="/kore/public/auth/register">Créer un compte</a>
                </p>
            </form>

            <div class="demo-info">
                <p class="text-secondary">
                    <strong>Comptes de démo :</strong><br>
                    salim@gmail.com / password<br>
                    barta@gmail.com / password<br>
                    mota@gmail.com / password<br>
                    ...(voir README)
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-page {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    padding: var(--spacing-xl) 0;
}

.auth-card {
    max-width: 420px;
    margin: 0 auto;
    background: var(--color-surface);
    border-radius: var(--radius-lg);
    padding: var(--spacing-2xl);
    box-shadow: var(--shadow-md);
}

.auth-card h1 {
    font-size: 2rem;
    margin-bottom: var(--spacing-xs);
    text-align: center;
}

.auth-card > p {
    text-align: center;
}

.auth-form {
    margin-top: var(--spacing-lg);
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: var(--spacing-xs);
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-md);
}

.alert-error {
    background: #fff0f0;
    border-left: 3px solid #ff3b30;
    color: #d32f2f;
}

.alert-success {
    background: #f0f9ff;
    border-left: 3px solid #34c759;
    color: #1b7030;
}

.alert p {
    margin: 0;
    font-size: 0.875rem;
}

.auth-link {
    text-align: center;
    margin-top: var(--spacing-lg);
    font-size: 0.9375rem;
    color: var(--color-text-secondary);
}

.auth-link a {
    color: var(--color-accent);
    text-decoration: none;
    font-weight: 500;
}

.auth-link a:hover {
    text-decoration: underline;
}

.demo-info {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--color-border);
    text-align: center;
}

.demo-info p {
    font-size: 0.8125rem;
    line-height: 1.8;
}

@media (max-width: 768px) {
    .auth-card {
        padding: var(--spacing-lg);
    }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>
