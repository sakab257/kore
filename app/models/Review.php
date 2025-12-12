<?php

class Review extends Model
{
    protected string $table = 'reviews';

    // Vérifie si l'utilisateur a déjà noté ce produit
    public function hasReviewed(int $userId, int $productId): bool
    {
        $sql = "SELECT COUNT(*) FROM reviews WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function createReview(int $userId, int $productId, int $rating, string $comment): bool
    {
        return (bool)$this->create([
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}