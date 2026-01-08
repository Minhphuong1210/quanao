<?php

$page = $_GET['page'] ?? 'admin';



switch ($page) {

    case 'dashboard':
        include(__DIR__ . '/dashboard/index.php');
        break;
    case 'category':
        include __DIR__ . '/../../category/index.php';
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
