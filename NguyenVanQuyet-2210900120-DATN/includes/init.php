<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Cart.php';

$cart = new Cart();
$cartCount = $cart->getTotalItems();
$cartTotal = $cart->getTotalPrice();

?>