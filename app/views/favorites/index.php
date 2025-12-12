<?php require_once APP . '/views/templates/header.php'; ?>

<div class="favorites-page">
    <div class="container">
        <h1 class="mb-4">Mes Favoris</h1>

        <?php if (empty($favorites)): ?>
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                </svg>
                <h2 class="mt-2">Votre liste d'envies est vide</h2>
                <p class="text-secondary">Sauvegardez vos articles préférés pour les retrouver plus tard.</p>
                <a href="/kore/public/product" class="btn btn-primary mt-3">Explorer la boutique</a>
            </div>
        <?php else: ?>
            <div class="grid grid-4">
                <?php foreach ($favorites as $product): ?>
                    <div class="product-card">
                        <a href="/kore/public/product/show/<?= $product['id'] ?>" class="product-link">
                            <div class="product-image">
                                <?php if ($product['primary_image']):
                                    $imageUrl = (str_starts_with($product['primary_image'], 'http')) 
                                        ? $product['primary_image'] 
                                        : '/kore/public/' . $product['primary_image'];
                                ?>
                                    <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php else: ?>
                                    <div class="product-image-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-info">
                                <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="product-price"><?= number_format($product['price'], 2) ?> €</p>
                            </div>
                        </a>
                        
                        <div class="favorite-actions mt-2">
                            <button class="btn btn-secondary btn-sm w-100 remove-fav-btn" data-product-id="<?= $product['id'] ?>">
                                Retirer des favoris
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Petit script inline pour gérer la suppression immédiate depuis cette page
document.querySelectorAll('.remove-fav-btn').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.preventDefault();
        const productId = this.dataset.productId;
        const card = this.closest('.product-card');

        try {
            const response = await fetch('/kore/public/favorite/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: parseInt(productId) })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Animation de suppression
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    card.remove();
                    // Si plus de favoris, on recharge pour afficher l'état vide
                    if (document.querySelectorAll('.product-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        } catch (error) {
            console.error('Erreur', error);
        }
    });
});
</script>

<style>
.favorites-page { padding: var(--spacing-xl) 0; min-height: 60vh; }
.empty-state { text-align: center; padding: var(--spacing-2xl) 0; color: var(--color-text-secondary); }
.empty-state svg { color: var(--color-border); margin-bottom: var(--spacing-sm); }
.product-link { text-decoration: none; color: inherit; display: block; }
.product-image { aspect-ratio: 3/4; border-radius: var(--radius-md); overflow: hidden; margin-bottom: var(--spacing-sm); }
.product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
.product-card:hover .product-image img { transform: scale(1.05); }
.w-100 { width: 100%; }
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>