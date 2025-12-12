<?php require_once APP . '/views/templates/header.php'; ?>

<div class="cart-page">
    <div class="container">
        <h1 class="mb-4">Panier</h1>

        <?php if (empty($cartItems)): ?>
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <h2 class="mt-2">Votre panier est vide</h2>
                <p class="text-secondary">Découvrez notre collection et ajoutez vos articles préférés</p>
                <a href="/kore/public/product" class="btn btn-primary mt-3">Voir les produits</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
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

                            <div class="item-details">
                                <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                                <p class="item-variant text-secondary">
                                    <?= htmlspecialchars($item['size']) ?> / <?= htmlspecialchars($item['color']) ?>
                                </p>
                                <p class="item-price"><?= number_format($item['price'], 2) ?> €</p>
                            </div>

                            <div class="item-quantity-control">
                                <button class="qty-btn cart-update-btn" data-variant-id="<?= $item['variant_id'] ?>" data-action="decrease">−</button>
                                <span class="item-quantity"><?= $item['quantity'] ?></span>
                                <button class="qty-btn cart-update-btn" data-variant-id="<?= $item['variant_id'] ?>" data-action="increase">+</button>
                            </div>

                            <div class="item-subtotal">
                                <?= number_format($item['subtotal'], 2) ?> €
                            </div>

                            <button class="remove-from-cart" data-variant-id="<?= $item['variant_id'] ?>">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Récapitulatif</h3>

                        <div class="summary-row">
                            <span>Sous-total</span>
                            <span id="cartTotal"><?= number_format($total, 2) ?> €</span>
                        </div>

                        <div class="summary-row">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span><?= number_format($total, 2) ?> €</span>
                        </div>

                        <a href="/kore/public/checkout" class="btn btn-primary btn-large">Commander</a>
                        <a href="/kore/public/product" class="btn btn-secondary btn-large">Continuer mes achats</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.cart-page {
    padding: var(--spacing-xl) 0;
}

.empty-state { text-align: center; padding: var(--spacing-2xl) 0; color: var(--color-text-secondary); }
.empty-state svg { color: var(--color-border); margin-bottom: var(--spacing-sm); }

.empty-cart {
    text-align: center;
    padding: var(--spacing-2xl) 0;
}

.empty-cart svg {
    color: var(--color-text-secondary);
    margin-bottom: var(--spacing-lg);
}

.empty-cart h2 {
    margin-bottom: var(--spacing-sm);
}

.cart-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: var(--spacing-xl);
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto auto auto;
    gap: var(--spacing-md);
    align-items: center;
    background: var(--color-surface);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.item-image {
    width: 100px;
    height: 130px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--color-bg);
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
}

.item-details h3 {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: var(--spacing-xs);
}

.item-variant {
    font-size: 0.875rem;
    margin-bottom: var(--spacing-xs);
}

.item-price {
    font-weight: 500;
}

.item-quantity-control {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    background: var(--color-bg);
    border-radius: var(--radius-pill);
    padding: 0.25rem;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: var(--color-surface);
    cursor: pointer;
    font-size: 1.125rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.qty-btn:hover {
    background: var(--color-black);
    color: white;
}

.qty-btn:active {
    transform: scale(0.95);
}

.item-quantity {
    font-weight: 500;
    min-width: 24px;
    text-align: center;
}

.item-subtotal {
    font-size: 1.125rem;
    font-weight: 600;
    min-width: 100px;
    text-align: right;
}

.remove-from-cart {
    border: none;
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    transition: color 0.2s ease;
}

.remove-from-cart:hover {
    color: #ff3b30;
}

.cart-summary {
    position: sticky;
    top: 80px;
    height: fit-content;
}

.summary-card {
    background: var(--color-surface);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
}

.summary-card h3 {
    margin-bottom: var(--spacing-md);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
    font-size: 0.9375rem;
}

.summary-divider {
    height: 1px;
    background: var(--color-border);
    margin: var(--spacing-md) 0;
}

.summary-total {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: var(--spacing-lg);
}

.btn-large {
    width: 100%;
    margin-bottom: var(--spacing-sm);
}

@media (max-width: 768px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }

    .cart-item {
        grid-template-columns: 80px 1fr;
        gap: var(--spacing-sm);
    }

    .item-quantity-control {
        grid-column: 2;
    }

    .item-subtotal {
        grid-column: 2;
        text-align: left;
    }

    .remove-from-cart {
        grid-column: 1 / -1;
        justify-self: center;
    }

    .cart-summary {
        position: relative;
        top: 0;
    }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>
