# KORE - E-commerce PHP MVC

**KORE** est une plateforme e-commerce moderne et minimaliste développée en **PHP natif** selon une architecture **MVC (Modèle-Vue-Contrôleur)** personnalisée.

Le projet met l'accent sur une expérience utilisateur fluide ("Apple-like"), une gestion robuste des stocks et des fonctionnalités dynamiques sans dépendance à des frameworks lourds.

---

## ✨ Fonctionnalités Principales

### 🛍️ Expérience d'Achat
* **Catalogue Immersif** : Filtrage par prix, tri, recherche dynamique et mise en avant des produits.
* **Fiche Produit Complète** : Galerie d'images, sélection de variantes (Taille/Couleur) avec gestion de stock en temps réel via JavaScript.
* **Panier AJAX** : Ajout, modification et suppression d'articles sans rechargement de page.
* **Wishlist (Favoris)** : Sauvegarde des articles préférés (AJAX).

### 👤 Espace Client
* **Authentification Sécurisée** : Inscription, Connexion, Déconnexion (hachage des mots de passe `bcrypt`).
* **Tableau de Bord** : Vue d'ensemble des informations personnelles.
* **Historique des Commandes** : Suivi des statuts de commande (En attente → Expédiée) simulé en temps réel.
* **Avis & Notations** : Possibilité de laisser une note et un commentaire sur les produits.

### ⚙️ Backend & Architecture
* **Architecture MVC** : Séparation claire des responsabilités (Router, Controllers, Models, Views).
* **Sécurité** : Protection CSRF sur les formulaires, requêtes SQL préparées (PDO), nettoyage des entrées (XSS).
* **Base de Données** : Modèle relationnel complexe (Users, Products, Variants, Images, Reviews, Orders).

---

## 🚀 Installation

### Prérequis
* PHP 8.0 ou supérieur.
* MySQL / MariaDB.
* Serveur Web (Apache avec `mod_rewrite` activé ou Nginx).

### Configuration MAMP (Mac)

Si vous utilisez MAMP sur Mac, vous devez activer le module `mod_rewrite` :

1. Ouvrez le fichier `/Applications/MAMP/conf/apache/httpd.conf`
2. Recherchez la ligne `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Supprimez le `#` pour décommenter la ligne
4. Redémarrez MAMP

### Étapes

1.  **Cloner le projet** dans votre dossier web (ex: `htdocs` ou `www`) :
    ```bash
    git clone [https://github.com/votre-user/kore.git](https://github.com/votre-user/kore.git)
    ```

2.  **Base de données** :
    * Ouvrez votre gestionnaire SQL (ex: phpMyAdmin).
    * Créez une base de données nommée `kore_shop`.
    * Importez le fichier `database.sql` situé à la racine du projet.

3.  **Configuration** :
    * Ouvrez le fichier `app/config/db.php`.
    * Vérifiez vos identifiants :
    ```php
    private const HOST = 'localhost';
    private const DB_NAME = 'kore_shop';
    private const USERNAME = 'root';
    private const PASSWORD = ''; // Votre mot de passe
    ```

    > **Note importante** : Le mot de passe par défaut de MySQL diffère selon votre système :
    > - **Windows (WAMP/XAMPP)** : mot de passe vide `''`
    > - **Mac (MAMP)** : mot de passe `'root'`

4.  **Lancement** :
    * Accédez au projet via votre navigateur.
    * URL typique Windows : `http://localhost/kore/public`
    * URL typique Mac : `http://localhost:8888/kore/public`

---

## 🧪 Comptes de Démonstration

Pour tester l'application sans créer de compte, utilisez ces utilisateurs pré-générés :

| Email | Mot de passe | Rôle |
| :--- | :--- | :--- |
| **salim@gmail.com** | `password` | Client |
| **barta@gmail.com** | `password` | Client |
| **mota@gmail.com** | `password` | Client |

Les autres utilisateurs tests sont dans database.sql

Vous pouvez bien sûr vous créer vous même votre compte

---

## 📂 Structure du Projet

```text
kore/
├── app/
│   ├── controllers/    # Logique de traitement (ProductController, CartController...)
│   ├── core/           # Cœur du framework (Router, Model, Controller, Database)
│   ├── models/         # Interaction BDD (Product, User, Order...)
│   └── views/          # Templates HTML/PHP
│       ├── account/
│       ├── auth/
│       ├── cart/
│       ├── checkout/
│       ├── product/
│       └── templates/  # Header, Footer
├── public/             # Racine web (Point d'entrée)
│   ├── assets/
│   │   ├── css/        # style.css
│   │   └── js/         # main.js
│   ├── index.php       # Routeur principal
│   └── .htaccess       # Réécriture d'URL
└── database.sql        # Fichier d'import SQL