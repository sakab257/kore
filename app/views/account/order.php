<?php require_once APP . '/views/templates/header.php'; ?>

<?php
// Traduction des statuts
$statusLabels = [
    'pending'    => 'En attente',
    'processing' => 'En cours de traitement',
    'shipped'    => 'Expédiée',
    'delivered'  => 'Livrée',
    'cancelled'  => 'Annulée'
];
?>

<div class="order-page">
    <div class="container">
        <a href="/kore/public/account" class="back-link mb-3">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Retour au compte
        </a>

        <div class="order-header-main mb-4">
            <h1>Commande #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h1>
            <div class="order-meta">
                <span class="order-status status-<?= $order['status'] ?>">
                    <?= $statusLabels[$order['status']] ?? $order['status'] ?>
                </span>
                <span class="text-secondary">Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></span>
            </div>
        </div>

        <div class="card order-items">
            <?php foreach ($order['items'] as $item): ?>
                <div class="order-item">
                    <div class="item-image">
                         <?php if ($item['image_url']):
                            $itemImageUrl = (str_starts_with($item['image_url'], 'http://') || str_starts_with($item['image_url'], 'https://'))
                                ? $item['image_url']
                                : '/kore/public/' . $item['image_url'];
                        ?>
                            <img src="<?= htmlspecialchars($itemImageUrl) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        <?php else: ?>
                            <div class="image-placeholder"></div>
                        <?php endif; ?>
                    </div>
                    <div class="item-info">
                        <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                        <p class="text-secondary"><?= htmlspecialchars($item['size']) ?> / <?= htmlspecialchars($item['color']) ?></p>
                    </div>
                    <div class="item-price">
                        <span class="text-secondary">x<?= $item['quantity'] ?></span>
                        <span class="price"><?= number_format($item['price'], 2) ?> €</span>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="order-summary mt-4 pt-4 border-top">
                <div class="summary-row">
                    <span>Total</span>
                    <span class="total-price"><?= number_format($order['total'], 2) ?> €</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-page { padding: var(--spacing-xl) 0; }
.back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--color-text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
.back-link:hover { color: var(--color-text); }
.order-header-main { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--spacing-md); }
.order-header-main h1 { margin: 0; font-size: 1.75rem; }
.order-meta { display: flex; align-items: center; gap: var(--spacing-md); }

.order-items { padding: var(--spacing-lg); }
.order-item { display: grid; grid-template-columns: 80px 1fr auto; gap: var(--spacing-lg); align-items: center; margin-bottom: var(--spacing-lg); }
.order-item:last-child { margin-bottom: 0; }
.item-image { width: 80px; height: 100px; border-radius: var(--radius-sm); overflow: hidden; background: #f5f5f5; }
.item-image img { width: 100%; height: 100%; object-fit: cover; }
.item-info h3 { font-size: 1rem; margin-bottom: 4px; }
.item-price { text-align: right; }
.item-price .price { display: block; font-weight: 500; }

.order-summary { display: flex; justify-content: flex-end; }
.summary-row { display: flex; gap: var(--spacing-xl); align-items: baseline; font-size: 1.25rem; font-weight: 600; }
.border-top { border-top: 1px solid var(--color-border); }

/* Status Colors (Identiques à index.php) */
.order-status { display: inline-block; padding: 4px 12px; border-radius: 99px; font-size: 0.8rem; font-weight: 500; }
.status-pending { background: #fff7ed; color: #c2410c; }
.status-processing { background: #eff6ff; color: #1d4ed8; }
.status-shipped { background: #f0fdf4; color: #15803d; }
.status-delivered { background: #f0fdf4; color: #15803d; }
.status-cancelled { background: #fef2f2; color: #b91c1c; }

@media (max-width: 600px) {
    .order-header-main { flex-direction: column; align-items: flex-start; }
    .order-item { grid-template-columns: 60px 1fr; }
    .item-price { grid-column: 2; text-align: left; display: flex; gap: var(--spacing-sm); }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>