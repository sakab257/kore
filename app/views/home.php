<?php require_once APP . '/views/templates/header.php'; ?>

<section class="hero-immersive">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="hero-label">Nouvelle Collection 2024</span>
        <h1>L'Art du Minimalisme.</h1>
        <p>Une esthétique épurée, des matières nobles. Redéfinissez votre style avec KORE.</p>
        <div class="hero-actions">
            <a href="/kore/public/product" class="btn btn-white">Acheter maintenant</a>
            <a href="#featured" class="btn btn-outline-white">Voir les nouveautés</a>
        </div>
    </div>
</section>

<section class="values-section">
    <div class="container">
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                    </svg>
                </div>
                <h3>Design Intemporel</h3>
                <p>Des pièces conçues pour traverser les saisons sans prendre une ride.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" width="24" height="24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                        <path d="M535.3 70.7C541.7 64.6 551 62.4 559.6 65.2C569.4 68.5 576 77.7 576 88L576 274.9C576 406.1 467.9 512 337.2 512C260.2 512 193.8 462.5 169.7 393.3C134.3 424.1 112 469.4 112 520C112 533.3 101.3 544 88 544C74.7 544 64 533.3 64 520C64 445.1 102.2 379.1 160.1 340.3C195.4 316.7 237.5 304 280 304L360 304C373.3 304 384 293.3 384 280C384 266.7 373.3 256 360 256L280 256C240.3 256 202.7 264.8 169 280.5C192.3 210.5 258.2 160 336 160C402.4 160 451.8 137.9 484.7 116C503.9 103.2 520.2 87.9 535.4 70.7z"/>
                    </svg>
                </div>
                <h3>Matières Premium</h3>
                <p>Coton bio, laine mérinos et cuir véritable sourcés éthiquement.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" width="24" height="24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path d="M0 96C0 60.7 28.7 32 64 32l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L557.3 192c12 12 18.7 28.3 18.7 45.3L576 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64L64 448c-35.3 0-64-28.7-64-64L0 96zM512 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM192 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/>
                    </svg>
                </div>
                <h3>Livraison Rapide</h3>
                <p>Expédition en 24h et retours gratuits sous 30 jours.</p>
            </div>
        </div>
    </div>
</section>

<section id="featured" class="products-section">
    <div class="container">
        <div class="section-header">
            <h2>Les Incontournables</h2>
            <a href="/kore/public/product" class="link-arrow">Tout voir <span>&rarr;</span></a>
        </div>

        <div class="grid grid-4">
            <?php foreach (array_slice($products, 0, 4) as $product): ?>
                <a href="/kore/public/product/show/<?= $product['id'] ?>" class="product-card">
                    <div class="product-image">
                        <?php if ($product['primary_image']):
                            $imageUrl = (str_starts_with($product['primary_image'], 'http')) ? $product['primary_image'] : '/kore/public/' . $product['primary_image'];
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
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>Rejoignez le mouvement KORE</h2>
        <p>Inscrivez-vous à notre newsletter et profitez de -10% sur votre première commande.</p>
        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Merci pour votre inscription !');">
            <input type="email" placeholder="Votre adresse email" required>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>
</section>

<style>
/* Hero Immersif */
.hero-immersive {
    position: relative;
    height: 100vh;
    background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    margin-top: -80px; /* Pour passer sous le header transparent si besoin */
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 20px;
    animation: fadeInUp 1s ease-out;
}

.hero-label {
    text-transform: uppercase;
    letter-spacing: 0.2em;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    display: block;
    opacity: 0.9;
}

.hero h1 {
    font-size: 4.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.1;
}

.hero p {
    font-size: 1.25rem;
    margin-bottom: 2.5rem;
    opacity: 0.9;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

/* Boutons Hero */
.btn-white {
    background: white;
    color: black;
    padding: 1rem 2.5rem;
    border-radius: 99px;
    text-decoration: none;
    font-weight: 600;
    transition: transform 0.2s;
}

.btn-outline-white {
    background: transparent;
    color: white;
    border: 2px solid white;
    padding: 1rem 2.5rem;
    border-radius: 99px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.2s, color 0.2s;
}

.btn-white:hover { transform: scale(1.05); }
.btn-outline-white:hover { background: white; color: black; }

/* Values Section */
.values-section { padding: 5rem 0; background: white; }
.values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; text-align: center; }
.value-icon { font-size: 2.5rem; margin-bottom: 1rem; }
.value-card h3 { margin-bottom: 0.5rem; font-size: 1.25rem; }
.value-card p { color: var(--color-text-secondary); }

/* Products Section */
.products-section { padding: 5rem 0; background: var(--color-bg); }
.section-header { display: flex; justify-content: space-between; align-items: end; margin-bottom: 2.5rem; }
.link-arrow { color: var(--color-text); text-decoration: none; font-weight: 500; transition: gap 0.2s; display: flex; align-items: center; gap: 5px; }
.link-arrow:hover { gap: 10px; }

/* Product Card Style (Réutilisation améliorée) */
.product-card { text-decoration: none; color: inherit; display: block; group: true; }
.product-image { aspect-ratio: 3/4; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1rem; position: relative; }
.product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.product-card:hover .product-image img { transform: scale(1.05); }

/* CTA Section */
.cta-section { padding: 5rem 0; text-align: center; background: #1d1d1f; color: white; }
.newsletter-form { margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem; max-width: 500px; margin-left: auto; margin-right: auto; }
.newsletter-form input { padding: 0.75rem 1.5rem; border-radius: 99px; border: none; flex: 1; outline: none; }

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .hero h1 { font-size: 2.5rem; }
    .hero-actions { flex-direction: column; }
    .newsletter-form { flex-direction: column; }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>