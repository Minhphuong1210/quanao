<?php

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        include(__DIR__ . '/home.php');
        break;
    case 'allProduct':

        include(__DIR__ . '/allProduct.php');
            break;

        

    case 'single':
        include(__DIR__ . '/single.php');
        break;

    case 'contact':
        include(__DIR__ . '/contact.php');
        break;

    default:
        include(__DIR__ . '/404.php');
        break;
}
