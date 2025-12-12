<?php require_once APP . '/views/templates/header.php'; ?>

<div class="product-page">
    <div class="container">
        <div class="product-layout">
            <div class="product-gallery">
                <div class="main-image" id="mainImage">
                    <?php if (!empty($product['images'])):
                        $mainImageUrl = (str_starts_with($product['images'][0]['image_url'], 'http'))
                            ? $product['images'][0]['image_url']
                            : '/kore/public/' . $product['images'][0]['image_url'];
                    ?>
                        <img src="<?= htmlspecialchars($mainImageUrl) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php else: ?>
                        <div class="image-placeholder"></div>
                    <?php endif; ?>
                </div>

                <?php if (count($product['images']) > 1): ?>
                    <div class="thumbnails">
                        <?php foreach ($product['images'] as $index => $image):
                            $thumbUrl = (str_starts_with($image['image_url'], 'http'))
                                ? $image['image_url']
                                : '/kore/public/' . $image['image_url'];
                        ?>
                            <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= htmlspecialchars($thumbUrl) ?>" alt="Thumbnail">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-details">
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                <p class="product-price-large"><?= number_format($product['price'], 2) ?> €</p>

                <div class="product-rating">
                    <?php if ($product['reviewStats']['total_reviews'] > 0): ?>
                        <div class="stars">
                            <?php
                            $avgRating = round($product['reviewStats']['average_rating']);
                            for ($i = 1; $i <= 5; $i++):
                            ?>
                                <span class="star <?= $i <= $avgRating ? 'filled' : '' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-text"><?= number_format($product['reviewStats']['average_rating'], 1) ?> (<?= $product['reviewStats']['total_reviews'] ?> avis)</span>
                    <?php else: ?>
                        <span class="text-secondary text-sm">Aucun avis pour le moment</span>
                    <?php endif; ?>
                </div>

                <p class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                <div class="product-options">
                    <div class="option-group">
                        <label>Taille</label>
                        <div class="size-selector">
                            <?php foreach ($product['sizes'] as $size): ?>
                                <button class="size-btn" data-size="<?= htmlspecialchars($size) ?>">
                                    <?= htmlspecialchars($size) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="option-group">
                        <label>Couleur</label>
                        <div class="color-selector">
                            <?php foreach ($product['colors'] as $color): ?>
                                <button class="color-btn" data-color="<?= htmlspecialchars($color['color']) ?>" title="<?= htmlspecialchars($color['color']) ?>">
                                    <?php if ($color['color_hex']): ?>
                                        <span class="color-swatch" style="background-color: <?= htmlspecialchars($color['color_hex']) ?>"></span>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($color['color']) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="product-actions">
                    <button class="btn btn-primary btn-large" id="addToCartBtn" disabled>
                        Ajouter au panier
                    </button>
                    <button class="btn btn-secondary btn-icon favorite-btn" id="favoriteBtn" data-product-id="<?= $product['id'] ?>" title="Ajouter aux favoris">
                        <svg id="favIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                        </svg>
                    </button>
                </div>

                <div class="stock-info" id="stockInfo">
                    <span class="text-secondary">Sélectionnez une taille et une couleur</span>
                </div>
            </div>
        </div>

        <div class="reviews-section">
            <h2>Avis clients</h2>
            <div class="reviews-stats">
                <div class="rating-summary">
                    <div class="rating-number"><?= number_format($product['reviewStats']['average_rating'], 1) ?></div>
                    <div class="stars-large">
                        <?php
                        $avgRating = round($product['reviewStats']['average_rating']);
                        for ($i = 1; $i <= 5; $i++):
                        ?>
                            <span class="star <?= $i <= $avgRating ? 'filled' : '' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <div class="rating-count"><?= $product['reviewStats']['total_reviews'] ?> avis</div>
                </div>

                <div class="rating-bars">
                    <?php
                    $total = $product['reviewStats']['total_reviews'];
                    $ratings = [
                        5 => $product['reviewStats']['five_stars'],
                        4 => $product['reviewStats']['four_stars'],
                        3 => $product['reviewStats']['three_stars'],
                        2 => $product['reviewStats']['two_stars'],
                        1 => $product['reviewStats']['one_star']
                    ];
                    foreach ($ratings as $stars => $count):
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                    ?>
                        <div class="rating-bar-row">
                            <span class="bar-label"><?= $stars ?> ★</span>
                            <div class="bar-container">
                                <div class="bar-fill" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <span class="bar-count"><?= $count ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="review-form-container">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <h3>Donnez votre avis</h3>
                        <form action="/kore/public/review/submit" method="POST" class="review-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>"> 
                            
                            <div class="form-group mb-2">
                                <label class="text-sm">Note</label>
                                <div class="star-rating-input">
                                    <?php for($i=5; $i>=1; $i--): ?>
                                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required />
                                        <label for="star<?= $i ?>" title="<?= $i ?> étoiles">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="form-group mb-2">
                                <label for="comment" class="text-sm">Commentaire</label>
                                <textarea name="comment" id="comment" rows="3" class="input" placeholder="Ce produit est..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm w-100">Publier l'avis</button>
                        </form>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p>Vous possédez ce produit ?</p>
                            <a href="/kore/public/auth/login" class="btn btn-secondary btn-sm">Connectez-vous pour donner votre avis</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="reviews-list">
                <?php foreach ($product['reviews'] as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-author">
                                <strong><?= htmlspecialchars($review['firstname']) ?> <?= htmlspecialchars(substr($review['lastname'], 0, 1)) ?>.</strong>
                                <span class="review-date"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                            </div>
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i <= $review['rating'] ? 'filled' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="review-comment"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // On passe simplement les données du backend au frontend ici
    // Toute la logique est maintenant dans main.js
    window.productData = {
        id: <?= $product['id'] ?>,
        variants: <?= json_encode($product['variants']) ?>
    };
</script>

<style>
/* CSS existant */
.product-page { padding: var(--spacing-xl) 0; }
.product-layout { display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-2xl); margin-bottom: var(--spacing-2xl); }
.product-gallery { position: sticky; top: 80px; height: fit-content; }
.main-image { background: var(--color-surface); border-radius: var(--radius-lg); overflow: hidden; aspect-ratio: 3/4; margin-bottom: var(--spacing-md); }
.main-image img { width: 100%; height: 100%; object-fit: cover; }
.thumbnails { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: var(--spacing-sm); }
.thumbnail { aspect-ratio: 3/4; border-radius: var(--radius-sm); overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: border-color 0.2s ease; }
.thumbnail.active { border-color: var(--color-black); }
.thumbnail img { width: 100%; height: 100%; object-fit: cover; }
.product-details h1 { font-size: 2rem; margin-bottom: var(--spacing-sm); }
.product-price-large { font-size: 1.5rem; font-weight: 600; margin-bottom: var(--spacing-md); }
.product-rating { display: flex; align-items: center; gap: var(--spacing-sm); margin-bottom: var(--spacing-md); }
.stars { display: flex; gap: 2px; }
.star { color: #d0d0d0; font-size: 1.25rem; }
.star.filled { color: #ffc107; }
.rating-text { color: var(--color-text-secondary); font-size: 0.9375rem; }
.product-description { color: var(--color-text-secondary); line-height: 1.7; margin-bottom: var(--spacing-lg); }
.product-options { margin-bottom: var(--spacing-lg); }
.option-group { margin-bottom: var(--spacing-md); }
.option-group label { display: block; font-weight: 500; margin-bottom: var(--spacing-sm); }
.size-selector, .color-selector { display: flex; gap: var(--spacing-sm); flex-wrap: wrap; }
.size-btn, .color-btn { padding: 0.625rem 1.25rem; border-radius: var(--radius-pill); border: 1px solid var(--color-border); background: var(--color-surface); font-family: var(--font-system); font-size: 0.9375rem; cursor: pointer; transition: all 0.2s ease; }
.size-btn:hover, .color-btn:hover { border-color: var(--color-black); }
.size-btn.active, .color-btn.active { background: var(--color-black); color: white; border-color: var(--color-black); }
.color-btn { display: flex; align-items: center; gap: var(--spacing-xs); }
.color-swatch { width: 16px; height: 16px; border-radius: 50%; border: 1px solid rgba(0, 0, 0, 0.1); }
.product-actions { display: flex; gap: var(--spacing-sm); margin-bottom: var(--spacing-md); }
.btn-large { flex: 1; padding: 1rem 2rem; }
.btn-icon { width: 52px; padding: 0; }
.stock-info { font-size: 0.9375rem; margin-top: var(--spacing-sm); }
.stock-available { color: #34c759; }
.stock-unavailable { color: #ff3b30; }

.reviews-section { margin-top: var(--spacing-2xl); padding-top: var(--spacing-2xl); border-top: 1px solid var(--color-border); }
.reviews-section h2 { margin-bottom: var(--spacing-lg); }
.reviews-stats { display: grid; grid-template-columns: 150px 1fr 300px; gap: var(--spacing-xl); margin-bottom: var(--spacing-2xl); padding: var(--spacing-lg); background: var(--color-surface); border-radius: var(--radius-lg); }
.rating-summary { text-align: center; }
.rating-number { font-size: 3rem; font-weight: 600; margin-bottom: var(--spacing-xs); }
.stars-large { display: flex; justify-content: center; gap: 4px; margin-bottom: var(--spacing-xs); }
.stars-large .star { font-size: 1.5rem; }
.rating-count { color: var(--color-text-secondary); font-size: 0.875rem; }
.rating-bars { display: flex; flex-direction: column; gap: var(--spacing-sm); justify-content: center; }
.rating-bar-row { display: flex; align-items: center; gap: var(--spacing-sm); }
.bar-label { width: 40px; font-size: 0.875rem; color: var(--color-text-secondary); }
.bar-container { flex: 1; height: 8px; background: #f0f0f0; border-radius: 4px; overflow: hidden; }
.bar-fill { height: 100%; background: #ffc107; transition: width 0.3s ease; }
.bar-count { width: 30px; text-align: right; font-size: 0.875rem; color: var(--color-text-secondary); }

.review-form-container { padding-left: var(--spacing-lg); border-left: 1px solid var(--color-border); }
.review-form-container h3 { font-size: 1rem; margin-bottom: var(--spacing-sm); }
.star-rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
.star-rating-input input { display: none; }
.star-rating-input label { font-size: 1.5rem; color: #d0d0d0; cursor: pointer; transition: color 0.2s; }
.star-rating-input input:checked ~ label,
.star-rating-input label:hover,
.star-rating-input label:hover ~ label { color: #ffc107; }
.login-prompt { text-align: center; padding: var(--spacing-md) 0; }
.login-prompt p { margin-bottom: var(--spacing-xs); font-size: 0.9rem; color: var(--color-text-secondary); }

.reviews-list { display: flex; flex-direction: column; gap: var(--spacing-md); }
.review-card { background: var(--color-surface); padding: var(--spacing-lg); border-radius: var(--radius-md); }
.review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--spacing-sm); }
.review-author { display: flex; flex-direction: column; }
.review-date { color: var(--color-text-secondary); font-size: 0.8125rem; }
.review-comment { color: var(--color-text-secondary); line-height: 1.6; }
.alert-success { background: #dcfce7; color: #166534; padding: 1rem; border-radius: var(--radius-md); }
.alert-error { background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: var(--radius-md); }

#comment {
    resize: none;
    border-radius: 0;
}

@media (max-width: 900px) {
    .reviews-stats { grid-template-columns: 1fr; gap: var(--spacing-lg); }
    .review-form-container { border-left: none; padding-left: 0; border-top: 1px solid var(--color-border); padding-top: var(--spacing-lg); }
}
@media (max-width: 768px) {
    .product-layout { grid-template-columns: 1fr; }
    .product-gallery { position: relative; top: 0; }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>