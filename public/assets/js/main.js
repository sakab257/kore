document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialisation Globale (Header, Panier)
    updateCartCount();
    setupCartPageListeners();

    // 2. Initialisation Page Produit (si on est sur une page produit)
    if (document.getElementById('addToCartBtn')) {
        setupProductPage();
    }
});

/**
 * --- LOGIQUE PAGE PRODUIT ---
 */
function setupProductPage() {
    // Gestion Ajout Panier
    const addToCartBtn = document.getElementById('addToCartBtn');
    addToCartBtn.addEventListener('click', handleAddToCart);

    // Gestion Favoris
    const favoriteBtn = document.getElementById('favoriteBtn');
    if (favoriteBtn) {
        // Vérifie l'état au chargement
        const productId = favoriteBtn.dataset.productId;
        checkFavoriteStatus(productId);
        favoriteBtn.addEventListener('click', handleToggleFavorite);
    }

    // Sélection Taille
    const sizeBtns = document.querySelectorAll('.size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Retirer la classe active des autres boutons
            sizeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateStockUI();
        });
    });

    // Sélection Couleur
    const colorBtns = document.querySelectorAll('.color-btn');
    colorBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            colorBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateStockUI();
        });
    });

    // Galerie Images (Remplacement des onclick inline)
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const newSrc = this.querySelector('img').src;
            const mainImg = document.querySelector('#mainImage img');
            
            if(mainImg) mainImg.src = newSrc;
            
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

function updateStockUI() {
    // On récupère les données injectées depuis PHP via window.productData
    if (typeof window.productData === 'undefined') return;

    const selectedSizeBtn = document.querySelector('.size-btn.active');
    const selectedColorBtn = document.querySelector('.color-btn.active');

    if (!selectedSizeBtn || !selectedColorBtn) return;

    const size = selectedSizeBtn.dataset.size;
    const color = selectedColorBtn.dataset.color;

    const variant = window.productData.variants.find(v => v.size === size && v.color === color);

    const stockInfo = document.getElementById('stockInfo');
    const addToCartBtn = document.getElementById('addToCartBtn');

    if (variant) {
        if (variant.stock > 0) {
            stockInfo.innerHTML = `<span class="stock-available">✓ En stock (${variant.stock} disponible${variant.stock > 1 ? 's' : ''})</span>`;
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = 'Ajouter au panier';
        } else {
            stockInfo.innerHTML = '<span class="stock-unavailable">✗ Rupture de stock</span>';
            addToCartBtn.disabled = true;
            addToCartBtn.textContent = 'Épuisé';
        }
    } else {
        stockInfo.innerHTML = '<span class="stock-unavailable">✗ Combinaison non disponible</span>';
        addToCartBtn.disabled = true;
        addToCartBtn.textContent = 'Indisponible';
    }
}

async function handleAddToCart(e) {
    e.preventDefault();

    // Vérification des sélections
    const selectedSizeBtn = document.querySelector('.size-btn.active');
    const selectedColorBtn = document.querySelector('.color-btn.active');

    if (!selectedSizeBtn || !selectedColorBtn) {
        showNotification('Veuillez sélectionner une taille et une couleur', 'error');
        // Animation visuelle pour attirer l'attention sur les sélecteurs
        document.querySelector('.product-options')?.classList.add('shake-animation');
        setTimeout(() => document.querySelector('.product-options')?.classList.remove('shake-animation'), 500);
        return;
    }

    if (typeof window.productData === 'undefined') return;

    const variant = window.productData.variants.find(v =>
        v.size === selectedSizeBtn.dataset.size && v.color === selectedColorBtn.dataset.color
    );

    if (!variant) {
        showNotification('Combinaison non disponible', 'error');
        return;
    }

    const btn = this;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Ajout...';

    try {
        const response = await fetch('/kore/public/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: window.productData.id,
                variant_id: variant.id,
                quantity: 1
            })
        });

        const data = await response.json();

        if (data.success) {
            updateCartCount(data.cartCount);
            animateAddToCart(); // Petite animation de l'image qui vole vers le panier
            showNotification('Produit ajouté au panier', 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
        }
    } catch (error) {
        console.error(error);
        showNotification('Erreur réseau', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

/**
 * --- GESTION DES FAVORIS ---
 */
async function handleToggleFavorite(e) {
    e.preventDefault();
    const btn = this;
    const productId = btn.dataset.productId;
    const icon = btn.querySelector('svg');

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
            updateFavoriteIcon(icon, btn, data.is_favorite);
            showNotification(data.is_favorite ? 'Ajouté aux favoris' : 'Retiré des favoris', 'success');
        } else if (data.message === 'Vous devez être connecté') {
            window.location.href = '/kore/public/auth/login';
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        console.error(error);
        showNotification('Une erreur est survenue', 'error');
    }
}

async function checkFavoriteStatus(productId) {
    try {
        const response = await fetch(`/kore/public/favorite/check?product_id=${productId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        const btn = document.getElementById('favoriteBtn');
        const icon = btn.querySelector('svg');
        updateFavoriteIcon(icon, btn, data.is_favorite);
    } catch (error) {
        console.error('Erreur check favoris');
    }
}

function updateFavoriteIcon(icon, btn, isFavorite) {
    if (isFavorite) {
        icon.setAttribute('fill', 'currentColor');
        btn.style.color = '#ff3b30'; // Rouge
    } else {
        icon.setAttribute('fill', 'none');
        btn.style.color = ''; // Reset
    }
}

/**
 * --- LOGIQUE PANIER (CART PAGE) ---
 */
function setupCartPageListeners() {
    const cartUpdateBtns = document.querySelectorAll('.cart-update-btn');
    cartUpdateBtns.forEach(btn => {
        btn.addEventListener('click', handleCartUpdate);
    });

    const removeFromCartBtns = document.querySelectorAll('.remove-from-cart');
    removeFromCartBtns.forEach(btn => {
        btn.addEventListener('click', handleRemoveFromCart);
    });
}

async function handleCartUpdate(e) {
    const variantId = this.dataset.variantId;
    const action = this.dataset.action;
    const quantityElement = this.parentElement.querySelector('.item-quantity');
    let quantity = parseInt(quantityElement.textContent);

    if (action === 'increase') {
        quantity++;
    } else if (action === 'decrease') {
        if (quantity > 1) {
            quantity--;
        } else {
            // Si on descend à 0, on supprime
            handleRemoveFromCart.call(this.closest('.cart-item').querySelector('.remove-from-cart'), e);
            return;
        }
    }

    try {
        const response = await fetch('/kore/public/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                variant_id: parseInt(variantId),
                quantity: quantity
            })
        });

        const data = await response.json();
        if (data.success) {
            quantityElement.textContent = quantity;
            updateCartCount(data.cartCount);
            updateCartTotal(data.total);
            // Mettre à jour le sous-total de la ligne si nécessaire
            const itemSubtotal = this.closest('.cart-item').querySelector('.item-subtotal');
            if(itemSubtotal && data.itemSubtotal) { // Suppose que le backend renvoie le subtotal
                 // Sinon recharger la page est une option simple
            }
        }
    } catch (error) {
        showNotification('Erreur lors de la mise à jour', 'error');
    }
}

async function handleRemoveFromCart(e) {
    e.preventDefault();
    const variantId = this.dataset.variantId;
    const cartItem = this.closest('.cart-item');

    try {
        const response = await fetch('/kore/public/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ variant_id: parseInt(variantId) })
        });

        const data = await response.json();

        if (data.success) {
            cartItem.style.transition = 'all 0.3s ease';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(100px)';

            setTimeout(() => {
                cartItem.remove();
                updateCartCount(data.cartCount);
                updateCartTotal(data.total);

                // Si panier vide, recharger pour afficher l'état vide
                if (data.cartCount === 0) location.reload();
            }, 300);
        }
    } catch (error) {
        showNotification('Erreur lors de la suppression', 'error');
    }
}

/**
 * --- UTILITAIRES ---
 */
async function updateCartCount(count = null) {
    const cartCountElement = document.getElementById('cartCount');
    if (!cartCountElement) return;

    if (count !== null) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'flex' : 'none';
    } else {
        try {
            const response = await fetch('/kore/public/cart/count', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            cartCountElement.textContent = data.count;
            cartCountElement.style.display = data.count > 0 ? 'flex' : 'none';
        } catch (error) {
            console.error('Failed to update cart count');
        }
    }
}

function updateCartTotal(total) {
    const totalElement = document.getElementById('cartTotal');
    if (totalElement) {
        totalElement.textContent = parseFloat(total).toFixed(2) + ' €';
    }
}

function animateAddToCart() {
    const mainImage = document.querySelector('.main-image img');
    const cartIcon = document.querySelector('.cart-icon');

    if (!mainImage || !cartIcon) return;

    const clone = mainImage.cloneNode(true);
    const imageRect = mainImage.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();

    clone.style.position = 'fixed';
    clone.style.left = imageRect.left + 'px';
    clone.style.top = imageRect.top + 'px';
    clone.style.width = imageRect.width + 'px';
    clone.style.height = imageRect.height + 'px';
    clone.style.zIndex = '9999';
    clone.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
    clone.style.pointerEvents = 'none';
    clone.style.borderRadius = '12px';

    document.body.appendChild(clone);

    requestAnimationFrame(() => {
        clone.style.left = cartRect.left + 'px';
        clone.style.top = cartRect.top + 'px';
        clone.style.width = '20px';
        clone.style.height = '20px';
        clone.style.opacity = '0';
        clone.style.borderRadius = '50%';
    });

    setTimeout(() => {
        clone.remove();
        cartIcon.style.animation = 'bounce 0.5s';
        setTimeout(() => cartIcon.style.animation = '', 500);
    }, 800);
}

function showNotification(message, type = 'info') {
    // Supprime les notifs existantes pour éviter l'empilement
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    requestAnimationFrame(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    });

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Injection des styles de notification et animation
const styles = document.createElement('style');
styles.textContent = `
    .notification {
        position: fixed;
        top: 90px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 500;
        border-left: 4px solid #007aff;
    }
    .notification-success { border-left-color: #34c759; }
    .notification-error { border-left-color: #ff3b30; }
    
    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
    .shake-animation {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
`;
document.head.appendChild(styles);