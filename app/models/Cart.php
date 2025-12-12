<?php

class Cart extends Model
{
    public function addItem(int $productId, int $variantId, int $quantity = 1): array
    {
        $variant = $this->checkStock($variantId, $quantity);

        if (!$variant) {
            return ['success' => false, 'message' => 'Stock insuffisant'];
        }

        $cartKey = (string)$variantId;

        if (isset($_SESSION['cart'][$cartKey])) {
            $newQuantity = $_SESSION['cart'][$cartKey]['quantity'] + $quantity;

            if ($newQuantity > $variant['stock']) {
                return ['success' => false, 'message' => 'Stock insuffisant'];
            }

            $_SESSION['cart'][$cartKey]['quantity'] = $newQuantity;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ];
        }

        return ['success' => true];
    }

    public function updateQuantity(int $variantId, int $quantity): bool
    {
        $cartKey = (string)$variantId;

        if (!isset($_SESSION['cart'][$cartKey])) {
            return false;
        }

        $variant = $this->checkStock($variantId, $quantity);

        if (!$variant) {
            return false;
        }

        $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
        return true;
    }

    public function removeItem(int $variantId): void
    {
        $cartKey = (string)$variantId;
        unset($_SESSION['cart'][$cartKey]);
    }

    public function getCartItems(): array
    {
        if (empty($_SESSION['cart'])) {
            return [];
        }

        $items = [];

        foreach ($_SESSION['cart'] as $variantId => $item) {
            $sql = "
                SELECT
                    p.id as product_id,
                    p.name as product_name,
                    p.price,
                    v.id as variant_id,
                    v.size,
                    v.color,
                    v.stock,
                    v.sku,
                    i.image_url
                FROM variants v
                JOIN products p ON v.product_id = p.id
                LEFT JOIN images i ON p.id = i.product_id AND i.is_primary = 1
                WHERE v.id = :variant_id
            ";

            $result = $this->queryOne($sql, ['variant_id' => $variantId]);

            if ($result) {
                $result['quantity'] = $item['quantity'];
                $result['subtotal'] = $result['price'] * $item['quantity'];
                $items[] = $result;
            }
        }

        return $items;
    }

    public function getItemCount(): int
    {
        if (empty($_SESSION['cart'])) {
            return 0;
        }

        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    public function getTotal(): float
    {
        $items = $this->getCartItems();
        $total = 0;

        foreach ($items as $item) {
            $total += $item['subtotal'];
        }

        return $total;
    }

    private function checkStock(int $variantId, int $quantity): ?array
    {
        $sql = "SELECT * FROM variants WHERE id = :id AND stock >= :quantity";
        return $this->queryOne($sql, ['id' => $variantId, 'quantity' => $quantity]);
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }
}
