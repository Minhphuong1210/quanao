<?php

class Cart
{
    private $items = [];

    public function __construct()
    {

        $this->items = $_SESSION['cart'] ?? [];
    }

    public function addItem($key, $name, $price, $image, $quantity = 1)
    {
        $key = (string)$key; // đảm bảo là string (ví dụ "1-2-3")
        $quantity = intval($quantity);

        if (isset($this->items[$key])) {
            $this->items[$key]['quantity'] += $quantity;
        } else {
            $this->items[$key] = [
                'key'      => $key,
                'name'     => $name,
                'price'    => floatval($price),
                'image'    => $image,
                'quantity' => $quantity
            ];
        }
        $this->save();
    }

    public function updateQuantity($key, $quantity)
    {
        $quantity = intval($quantity);
        if ($quantity <= 0) {
            unset($this->items[$key]);
        } elseif (isset($this->items[$key])) {
            $this->items[$key]['quantity'] = $quantity;
        }
        $this->save();
    }
    
    public function removeItem($key)
    {
        unset($this->items[$key]);
        $this->save();
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTotalItems()
    {
        return array_sum(array_column($this->items, 'quantity'));
    }

    public function getTotalPrice()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function clear()
    {
        $this->items = [];
        unset($_SESSION['cart']);
    }

    private function save()
    {
        $_SESSION['cart'] = $this->items;
    }
    public function getQuantity($id)
    {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            return 0;
        }

        foreach ($_SESSION['cart'] as $item) {
            if (isset($item['id']) && $item['id'] == $id) {
                return (int)$item['quantity'];
            }
        }
        return 0;
    }
}
