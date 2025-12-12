<?php require_once APP . '/views/templates/header.php'; ?>

<div class="checkout-page">
    <div class="container">
        <h1 class="mb-4">Finaliser la commande</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error mb-4">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/kore/public/checkout/submit" method="POST" class="checkout-layout">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <div class="checkout-form">
                <section class="checkout-section">
                    <h2>Adresse de livraison</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" class="input" value="<?= htmlspecialchars($user['firstname']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" class="input" value="<?= htmlspecialchars($user['lastname']) ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" name="address" class="input" placeholder="123 rue de la Mode" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Code Postal</label>
                            <input type="text" name="zipcode" class="input" placeholder="75000" required>
                        </div>
                        <div class="form-group">
                            <label>Ville</label>
                            <input type="text" name="city" class="input" placeholder="Paris" required>
                        </div>
                    </div>
                </section>

                <section class="checkout-section mt-4">
                    <h2>Paiement</h2>
                    <div class="payment-mockup">
                        <div class="form-group">
                            <label>Numéro de carte</label>
                            <input type="text" class="input" placeholder="0000 0000 0000 0000" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiration</label>
                                <input type="text" class="input" placeholder="MM/AA" required>
                            </div>
                            <div class="form-group">
                                <label>CVC</label>
                                <input type="text" class="input" placeholder="123" required>
                            </div>
                        </div>
                        <p class="text-secondary mt-2 text-sm">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                            Paiement sécurisé (Simulation : aucune somme ne sera débitée)
                        </p>
                    </div>
                </section>
            </div>

            <div class="checkout-summary">
                <div class="summary-card">
                    <h3>Votre commande</h3>
                    <div class="summary-items">
                        <?php foreach ($items as $item): ?>
                            <div class="summary-item">
                                <div class="summary-item-info">
                                    <span class="summary-item-name"><?= htmlspecialchars($item['product_name']) ?></span>
                                    <span class="summary-item-desc">Taille: <?= $item['size'] ?> · Qté: <?= $item['quantity'] ?></span>
                                </div>
                                <span class="summary-item-price"><?= number_format($item['subtotal'], 2) ?> €</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <span><?= number_format($total, 2) ?> €</span>
                    </div>
                    <div class="summary-row">
                        <span>Livraison</span>
                        <span>Gratuit</span>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row summary-total">
                        <span>Total à payer</span>
                        <span><?= number_format($total, 2) ?> €</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large mt-3">Payer la commande</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.checkout-page { padding: var(--spacing-xl) 0; }
.checkout-layout { display: grid; grid-template-columns: 1fr 380px; gap: var(--spacing-2xl); }
.checkout-section { background: var(--color-surface); padding: var(--spacing-lg); border-radius: var(--radius-lg); }
.checkout-section h2 { font-size: 1.25rem; margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-sm); border-bottom: 1px solid var(--color-border); }
.payment-mockup { background: var(--color-bg); padding: var(--spacing-md); border-radius: var(--radius-md); }
.summary-items { max-height: 300px; overflow-y: auto; margin-bottom: var(--spacing-md); }
.summary-item { display: flex; justify-content: space-between; margin-bottom: var(--spacing-sm); font-size: 0.9rem; }
.summary-item-info { display: flex; flex-direction: column; }
.summary-item-desc { color: var(--color-text-secondary); font-size: 0.8rem; }
.text-sm { font-size: 0.8rem; }

@media (max-width: 900px) {
    .checkout-layout { grid-template-columns: 1fr; }
    .checkout-summary { grid-row: 1; } /* On affiche le total en premier sur mobile */
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>