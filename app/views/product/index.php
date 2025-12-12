<?php require_once APP . '/views/templates/header.php'; ?>

<div class="products-page">
    <div class="container">
        
        <div class="products-header">
            <h1 class="page-title">Tous les produits <span class="count-badge"><?= count($products) ?></span></h1>
            
            <form method="GET" action="/kore/public/product" class="filter-bar">
                <div class="search-group">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="q" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </div>

                <div class="filters-group">
                    <select name="sort" onchange="this.form.submit()" class="select-filter">
                        <option value="newest" <?= ($_GET['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Nouveautés</option>
                        <option value="price_asc" <?= ($_GET['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
                        <option value="price_desc" <?= ($_GET['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                    </select>
                    
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min €" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Max €" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                        <button type="submit" class="btn-filter">OK</button>
                    </div>
                    
                    <?php if(!empty($_GET)): ?>
                        <a href="/kore/public/product" class="btn-reset">Réinitialiser</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty-search">
                <p>Aucun produit ne correspond à votre recherche.</p>
                <a href="/kore/public/product" class="btn btn-secondary mt-2">Voir tout le catalogue</a>
            </div>
        <?php else: ?>
            <div class="grid grid-4">
                <?php foreach ($products as $product): ?>
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
        <?php endif; ?>
    </div>
</div>

<style>
.products-page { padding: var(--spacing-xl) 0; }

/* Header & Filtres */
.products-header { margin-bottom: var(--spacing-xl); }
.page-title { font-size: 2rem; margin-bottom: var(--spacing-lg); display: flex; align-items: center; gap: 10px; }
.count-badge { font-size: 1rem; background: #eee; padding: 2px 8px; border-radius: 12px; color: #666; font-weight: normal; }

.filter-bar { 
    display: flex; 
    flex-wrap: wrap; 
    gap: var(--spacing-md); 
    background: white; 
    padding: 1rem; 
    border-radius: var(--radius-md); 
    box-shadow: var(--shadow-sm); 
    align-items: center;
}

.search-group { 
    position: relative; 
    flex: 1; 
    min-width: 200px; 
}
.search-group svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; }
.search-group input { 
    width: 100%; 
    padding: 0.75rem 1rem 0.75rem 2.5rem; 
    border: 1px solid var(--color-border); 
    border-radius: var(--radius-sm); 
    font-size: 0.9375rem; 
    outline: none;
    transition: border-color 0.2s;
}
.search-group input:focus { border-color: var(--color-black); }

.filters-group { display: flex; gap: var(--spacing-sm); align-items: center; flex-wrap: wrap; }
.select-filter { 
    padding: 0.75rem 2rem 0.75rem 1rem; 
    border: 1px solid var(--color-border); 
    border-radius: var(--radius-sm); 
    background-color: white; 
    cursor: pointer;
    font-size: 0.9375rem;
}

.price-inputs { display: flex; align-items: center; gap: 8px; }
.price-inputs input { width: 70px; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.875rem; }
.btn-filter { background: var(--color-black); color: white; border: none; padding: 0.5rem 1rem; border-radius: var(--radius-sm); cursor: pointer; }
.btn-reset { color: var(--color-text-secondary); text-decoration: none; font-size: 0.875rem; border-bottom: 1px solid transparent; }
.btn-reset:hover { border-color: currentColor; }

/* Empty State */
.empty-search { text-align: center; padding: 4rem 0; color: var(--color-text-secondary); }

/* Card Style (Uniformisé avec Home) */
.product-card { text-decoration: none; color: inherit; display: block; }
.product-image { aspect-ratio: 3/4; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 0.75rem; background: #f5f5f5; }
.product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
.product-card:hover .product-image img { transform: scale(1.05); }
.product-name { font-size: 1rem; font-weight: 500; margin-bottom: 4px; }
.product-price { font-size: 0.9375rem; color: var(--color-text-secondary); }

@media (max-width: 768px) {
    .filter-bar { flex-direction: column; align-items: stretch; }
    .filters-group { width: 100%; overflow-x: auto; padding-bottom: 5px; }
}
</style>

<?php require_once APP . '/views/templates/footer.php'; ?>