<?php require_once APP . '/views/templates/header.php'; ?>

<div class="success-page">
    <div class="container text-center">
        <div class="success-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        
        <h1>Merci pour votre commande !</h1>
        <p class="order-ref">Commande #<?= str_pad($order_id, 6, '0', STR_PAD_LEFT) ?></p>
        
        <p class="success-message">
            Votre commande a été enregistrée avec succès.<br>
            Préparation en cours... Vous allez être redirigé vers votre compte.
        </p>

        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
        <p class="text-secondary text-sm mt-2" id="statusText">Validation du paiement...</p>

        <div class="actions mt-4">
            <a href="/kore/public/account" class="btn btn-secondary">Voir ma commande</a>
            <a href="/kore/public/product" class="btn btn-primary">Continuer mes achats</a>
        </div>
    </div>
</div>

<script>
    // Petit script pour simuler les étapes visuellement avant la redirection
    const statusText = document.getElementById('statusText');
    
    setTimeout(() => {
        statusText.textContent = "Préparation de l'expédition...";
    }, 2500);

    setTimeout(() => {
        statusText.textContent = "Commande expédiée ! Redirection...";
    }, 5000);

    // Redirection vers le compte après 6 secondes (le statut sera passé à 'processing' ou 'shipped')
    setTimeout(() => {
        window.location.href = '/kore/public/account';
    }, 6000);
</script>

<style>
.success-page { padding: var(--spacing-2xl) 0; min-height: 60vh; display: flex; align-items: center; }
.success-icon { color: #34c759; margin-bottom: var(--spacing-md); }
.order-ref { font-size: 1.25rem; font-weight: 600; color: var(--color-text); margin-bottom: var(--spacing-md); }
.success-message { color: var(--color-text-secondary); margin-bottom: var(--spacing-lg); line-height: 1.6; }
.actions { display: flex; justify-content: center; gap: var(--spacing-md); }

.progress-container {
    width: 200px;
    height: 6px;
    background: #f0f0f0;
    border-radius: 3px;
    margin: 0 auto;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: #34c759;
    width: 0%;
    animation: progress 6s linear forwards;
}

@keyframes progress {
    0% { width: 0%; }
    100% { width: 100%; }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>