<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'KORE' ?></title>
    <link rel="stylesheet" href="/kore/public/assets/css/style.css">
    <style>
        /* Amélioration locale du style du header */
        .nav-actions {
            gap: 1.25rem; /* Plus d'espace entre les icônes */
        }
        
        .nav-icon {
            color: var(--color-text);
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .nav-icon:hover {
            color: var(--color-black);
            transform: translateY(-2px); /* Petit effet de levitation */
            color: #5c5c5cff;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="/kore/public" class="logo" style="margin-right:15px">KORE</a>

                <ul class="nav-links">
                    <li><a href="/kore/public">Accueil</a></li>
                    <li><a href="/kore/public/product">Produits</a></li>
                    <li><a href="/kore/public/about">À propos</a></li>
                </ul>

                <div class="nav-actions">
                    <a href="/kore/public/cart" class="nav-icon cart-icon" title="Panier">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                            <circle cx="9" cy="22" r="1.5"/>
                            <circle cx="20" cy="22" r="1.5"/>
                        </svg>
                        <span class="cart-count" id="cartCount" style="display: none;">0</span>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/kore/public/favorite" class="nav-icon" title="Mes favoris">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                            </svg>
                        </a>

                        <a href="/kore/public/account" class="nav-icon" title="Mon compte">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="5"/>
                                <path d="M3 21c0-5 4-9 9-9s9 4 9 9"/>
                            </svg>
                        </a>
                        
                        <a href="/kore/public/auth/logout" class="nav-icon logout-icon" title="Se déconnecter">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="/kore/public/auth/login" class="nav-link">Connexion</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="main">