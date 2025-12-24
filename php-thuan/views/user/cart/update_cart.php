<?php
require_once 'models/Cart.php';

$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['quantity'])) {
        $cart->updateQuantity($_POST['id'], $_POST['quantity']);
    }
}

header('Location: views/cart.php');
exit;