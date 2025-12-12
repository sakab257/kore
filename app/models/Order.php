<?php

class Order extends Model
{
    protected string $table = 'orders';

    public function createOrder(int $userId, array $cartItems, float $total): ?int
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO orders (user_id, total, status, created_at) VALUES (:user_id, :total, 'pending', NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'total' => $total
            ]);
            
            $orderId = (int)$this->db->lastInsertId();

            $sqlItem = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, price) VALUES (:order_id, :product_id, :variant_id, :quantity, :price)";
            $stmtItem = $this->db->prepare($sqlItem);

            $sqlUpdateStock = "UPDATE variants SET stock = stock - :quantity WHERE id = :id";
            $stmtUpdateStock = $this->db->prepare($sqlUpdateStock);

            foreach ($cartItems as $item) {
                $stmtItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                $stmtUpdateStock->execute([
                    'quantity' => $item['quantity'],
                    'id' => $item['variant_id']
                ]);
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * NOUVEAU : Simule l'avancement des commandes en fonction du temps écoulé
     * Appelé à chaque fois qu'on affiche l'historique
     */
    public function refreshStatus(int $userId): void
    {
        // Passage à "processing" après 5 secondes
        $sql1 = "UPDATE orders 
                 SET status = 'processing' 
                 WHERE user_id = :user_id 
                 AND status = 'pending' 
                 AND created_at <= (NOW() - INTERVAL 5 SECOND)";

        // Passage à "shipped" après 10 secondes
        $sql2 = "UPDATE orders 
                 SET status = 'shipped' 
                 WHERE user_id = :user_id 
                 AND (status = 'pending' OR status = 'processing') 
                 AND created_at <= (NOW() - INTERVAL 10 SECOND)";

        $this->db->prepare($sql1)->execute(['user_id' => $userId]);
        $this->db->prepare($sql2)->execute(['user_id' => $userId]);
    }
}