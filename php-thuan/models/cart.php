<?php

class Cart {
    private $items = [];

    public function __construct() {
        
        $this->items = $_SESSION['cart'] ?? [];
    }

    public function addItem($id, $name, $price, $image, $quantity = 1) {
        $id = intval($id);
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
        } else {
            $this->items[$id] = [
                'id'       => $id,
                'name'     => $name,
                'price'    => floatval($price),
                'image'    => $image,
                'quantity' => intval($quantity)
            ];
        }
        $this->save();
    }

    public function updateQuantity($id, $quantity) {
        $id = intval($id);
        $quantity = intval($quantity);
        if ($quantity <= 0) {
            unset($this->items[$id]);
        } elseif (isset($this->items[$id])) {
            $this->items[$id]['quantity'] = $quantity;
        }
        $this->save();
    }

    public function removeItem($id) {
        $id = intval($id);
        unset($this->items[$id]);
        $this->save();
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotalItems() {
        return array_sum(array_column($this->items, 'quantity'));
    }

    public function getTotalPrice() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function isEmpty() {
        return empty($this->items);
    }

    public function clear() {
        $this->items = [];
        unset($_SESSION['cart']);
    }

    private function save() {
        $_SESSION['cart'] = $this->items;
    }
}