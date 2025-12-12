<?php require_once APP . '/views/templates/header.php'; ?>

<?php
// Traduction des statuts pour l'affichage
$statusLabels = [
    'pending'    => 'En attente',
    'processing' => 'En cours de traitement',
    'shipped'    => 'Expédiée',
    'delivered'  => 'Livrée',
    'cancelled'  => 'Annulée'
];
?>

<div class="account-page">
    <div class="container">
        <div class="account-header mb-4">
            <h1>Mon Compte</h1>
            <p class="text-secondary">Ravi de vous revoir, <?= htmlspecialchars($user['firstname']) ?>.</p>
        </div>

        <div class="account-grid">
            <aside class="account-sidebar">
                <div class="card info-card">
                    <h3>Mes informations</h3>
                    <div class="info-group">
                        <label>Nom complet</label>
                        <p><?= htmlspecialchars($user['firstname']) ?> <?= htmlspecialchars($user['lastname']) ?></p>
                    </div>
                    <div class="info-group">
                        <label>Email</label>
                        <p><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <div class="info-group">
                        <label>Membre depuis</label>
                        <p><?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                    </div>
                    <a href="/kore/public/auth/logout" class="btn btn-secondary btn-sm mt-2">Se déconnecter</a>
                </div>
            </aside>

            <section class="account-history">
                <h2 class="mb-3">Historique des commandes</h2>

                <?php if (empty($orders)): ?>
                    <div class="empty-state card">
                        <p class="text-secondary">Vous n'avez pas encore passé de commande.</p>
                        <a href="/kore/public/product" class="btn btn-primary mt-2">Découvrir la collection</a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <a href="/kore/public/account/order/<?= $order['id'] ?>" class="order-card">
                                <div class="order-header">
                                    <span class="order-ref">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                    <span class="order-date"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                                </div>
                                <div class="order-body">
                                    <div class="order-status status-<?= $order['status'] ?>">
                                        <?= $statusLabels[$order['status']] ?? $order['status'] ?>
                                    </div>
                                    <div class="order-total">
                                        <?= $order['items_count'] ?> article<?= $order['items_count'] > 1 ? 's' : '' ?>
                                        <span class="price"><?= number_format($order['total'], 2) ?> €</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>

<style>
.account-page { padding: var(--spacing-xl) 0; }
.account-grid { display: grid; grid-template-columns: 300px 1fr; gap: var(--spacing-2xl); }
.account-sidebar .card { padding: var(--spacing-lg); }
.info-group { margin-bottom: var(--spacing-md); }
.info-group label { display: block; font-size: 0.8rem; color: var(--color-text-secondary); margin-bottom: 4px; }
.info-group p { font-weight: 500; }

.orders-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.order-card { 
    display: block; 
    text-decoration: none; 
    color: inherit; 
    background: var(--color-surface); 
    padding: var(--spacing-lg); 
    border-radius: var(--radius-md); 
    border: 1px solid transparent; 
    transition: all 0.2s ease; 
}
.order-card:hover { border-color: var(--color-border); transform: translateY(-2px); box-shadow: var(--shadow-sm); }
.order-header { display: flex; justify-content: space-between; margin-bottom: var(--spacing-sm); font-size: 0.9rem; color: var(--color-text-secondary); }
.order-ref { font-family: monospace; font-weight: 600; color: var(--color-text); }
.order-body { display: flex; justify-content: space-between; align-items: center; }
.order-total { text-align: right; font-size: 0.9375rem; }
.order-total .price { display: block; font-weight: 600; color: var(--color-text); font-size: 1.125rem; }

.order-status { display: inline-block; padding: 4px 12px; border-radius: 99px; font-size: 0.8rem; font-weight: 500; }
.status-pending { background: #fff7ed; color: #c2410c; } /* Orange */
.status-processing { background: #eff6ff; color: #1d4ed8; } /* Bleu */
.status-shipped { background: #f0fdf4; color: #15803d; } /* Vert */
.status-delivered { background: #f0fdf4; color: #15803d; } /* Vert */
.status-cancelled { background: #fef2f2; color: #b91c1c; } /* Rouge */

@media (max-width: 768px) {
    .account-grid { grid-template-columns: 1fr; }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>