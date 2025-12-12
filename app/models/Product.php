<?php

class Product extends Model
{
    protected string $table = 'products';

    /**
     * C'est ici que la magie opère : on construit la requête SQL
     * en fonction des filtres reçus.
     */
    public function filterAll(?string $search, ?string $minPrice, ?string $maxPrice, string $sort): array
    {
        // On commence par sélectionner les produits avec leur image principale
        // "WHERE 1=1" est une astuce pour pouvoir ajouter des "AND" facilement après
        $sql = "
            SELECT p.*, i.image_url as primary_image
            FROM products p
            LEFT JOIN images i ON p.id = i.product_id AND i.is_primary = 1
            WHERE 1=1
        ";
        
        $params = [];

        // 1. Filtre Recherche (Nom ou Description)
        // CORRECTION ICI : Utilisation de deux marqueurs différents pour éviter l'erreur PDO
        if ($search) {
            $sql .= " AND (p.name LIKE :search_name OR p.description LIKE :search_desc)";
            $params['search_name'] = "%{$search}%";
            $params['search_desc'] = "%{$search}%";
        }

        // 2. Filtre Prix Min
        if ($minPrice !== null && $minPrice !== '') {
            $sql .= " AND p.price >= :min_price";
            $params['min_price'] = (float)$minPrice;
        }

        // 3. Filtre Prix Max
        if ($maxPrice !== null && $maxPrice !== '') {
            $sql .= " AND p.price <= :max_price";
            $params['max_price'] = (float)$maxPrice;
        }

        // 4. Tri (ORDER BY)
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.created_at DESC";
                break;
        }

        return $this->query($sql, $params);
    }

    public function getAllWithImages(): array
    {
        return $this->filterAll(null, null, null, 'newest');
    }

    public function getFullProduct(int $id): ?array
    {
        $product = $this->findById($id);
        if (!$product) return null;
        $product['images'] = $this->getImages($id);
        $product['variants'] = $this->getVariants($id);
        $product['reviews'] = $this->getReviews($id);
        $product['reviewStats'] = $this->getReviewStats($id);
        $product['sizes'] = $this->getAvailableSizes($id);
        $product['colors'] = $this->getAvailableColors($id);
        return $product;
    }
    
    public function getImages(int $productId): array {
        return $this->query("SELECT * FROM images WHERE product_id = :id ORDER BY is_primary DESC, display_order ASC", ['id' => $productId]);
    }
    
    public function getVariants(int $productId): array {
        return $this->query("SELECT * FROM variants WHERE product_id = :id ORDER BY size, color", ['id' => $productId]);
    }
    
    public function getReviews(int $productId): array {
        return $this->query("SELECT r.*, u.firstname, u.lastname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = :id ORDER BY r.created_at DESC", ['id' => $productId]);
    }
    
    public function getReviewStats(int $productId): array {
        $result = $this->queryOne("SELECT COUNT(*) as total_reviews, AVG(rating) as average_rating, SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_stars, SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_stars, SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_stars, SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_stars, SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star FROM reviews WHERE product_id = :id", ['id' => $productId]);
        if (!$result || $result['total_reviews'] == 0) {
            return ['total_reviews' => 0, 'average_rating' => 0, 'five_stars' => 0, 'four_stars' => 0, 'three_stars' => 0, 'two_stars' => 0, 'one_star' => 0];
        }
        return $result;
    }
    
    public function getAvailableSizes(int $productId): array {
        $results = $this->query("SELECT DISTINCT size FROM variants WHERE product_id = :id AND stock > 0 ORDER BY FIELD(size, 'XS', 'S', 'M', 'L', 'XL', 'XXL')", ['id' => $productId]);
        return array_column($results, 'size');
    }
    
    public function getAvailableColors(int $productId): array {
        return $this->query("SELECT DISTINCT color, color_hex FROM variants WHERE product_id = :id AND stock > 0 ORDER BY color", ['id' => $productId]);
    }
    
    // Correction mineure : Ajout de la méthode getVariantByAttributes si elle manquait
    public function getVariantByAttributes(int $productId, string $size, string $color): ?array {
        return $this->queryOne("SELECT * FROM variants WHERE product_id = :product_id AND size = :size AND color = :color LIMIT 1", ['product_id' => $productId, 'size' => $size, 'color' => $color]);
    }
}