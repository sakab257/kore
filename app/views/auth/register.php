<?php require_once APP . '/views/templates/header.php'; ?>

<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <h1>Créer un compte</h1>
            <p class="text-secondary mb-3">Rejoignez KORE et profitez d'une expérience shopping unique</p>

            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['errors']); ?>
                </div>
            <?php endif; ?>

            <form action="/kore/public/auth/register" method="POST" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">Prénom</label>
                        <input
                            type="text"
                            id="firstname"
                            name="firstname"
                            class="input"
                            value="<?= htmlspecialchars($_SESSION['old']['firstname'] ?? '') ?>"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="lastname">Nom</label>
                        <input
                            type="text"
                            id="lastname"
                            name="lastname"
                            class="input"
                            value="<?= htmlspecialchars($_SESSION['old']['lastname'] ?? '') ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input"
                        value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
                        required
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
                    <small class="form-hint">Minimum 6 caractères</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="input"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-large">Créer mon compte</button>

                <p class="auth-link">
                    Vous avez déjà un compte ?
                    <a href="/kore/public/auth/login">Se connecter</a>
                </p>
            </form>
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
    max-width: 480px;
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
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

.form-hint {
    display: block;
    font-size: 0.8125rem;
    color: var(--color-text-secondary);
    margin-top: var(--spacing-xs);
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

.alert-error p {
    margin: 0;
    font-size: 0.875rem;
}

.alert-error p:not(:last-child) {
    margin-bottom: var(--spacing-xs);
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

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .auth-card {
        padding: var(--spacing-lg);
    }
}
</style>

<?php unset($_SESSION['old']); ?>
<?php require_once APP . '/views/templates/footer.php'; ?>
