<?php

class User extends Model
{
    protected string $table = 'users';

    public function getOrders(int $userId): array
    {
        $sql = "
            SELECT
                o.*,
                COUNT(oi.id) as items_count
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            WHERE o.user_id = :user_id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ";

        return $this->query($sql, ['user_id' => $userId]);
    }

    public function getOrderDetails(int $orderId, int $userId): ?array
    {
        $sql = "
            SELECT o.*
            FROM orders o
            WHERE o.id = :order_id AND o.user_id = :user_id
            LIMIT 1
        ";

        $order = $this->queryOne($sql, [
            'order_id' => $orderId,
            'user_id' => $userId
        ]);

        if (!$order) {
            return null;
        }

        $order['items'] = $this->getOrderItems($orderId);

        return $order;
    }

    private function getOrderItems(int $orderId): array
    {
        $sql = "
            SELECT
                oi.*,
                p.name as product_name,
                v.size,
                v.color,
                i.image_url
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN variants v ON oi.variant_id = v.id
            LEFT JOIN images i ON p.id = i.product_id AND i.is_primary = 1
            WHERE oi.order_id = :order_id
        ";

        return $this->query($sql, ['order_id' => $orderId]);
    }
}
