<?php

class Favorite extends Model
{
    protected string $table = 'favorites';

    public function getUserFavorites(int $userId): array
    {
        $sql = "
            SELECT
                p.*,
                i.image_url as primary_image,
                f.created_at as favorited_at
            FROM favorites f
            JOIN products p ON f.product_id = p.id
            LEFT JOIN images i ON p.id = i.product_id AND i.is_primary = 1
            WHERE f.user_id = :user_id
            ORDER BY f.created_at DESC
        ";

        return $this->query($sql, ['user_id' => $userId]);
    }

    public function isFavorite(int $userId, int $productId): bool
    {
        $sql = "
            SELECT COUNT(*) FROM favorites
            WHERE user_id = :user_id AND product_id = :product_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function add(int $userId, int $productId): bool
    {
        try {
            return (bool)$this->create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function remove(int $userId, int $productId): bool
    {
        $sql = "DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    /**
     * Renommé en 'countByUser' pour éviter le conflit avec Model::count()
     */
    public function countByUser(int $userId): int
    {
        $sql = "SELECT COUNT(*) FROM favorites WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}