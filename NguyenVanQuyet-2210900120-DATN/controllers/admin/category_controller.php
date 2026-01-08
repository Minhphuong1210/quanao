<?php

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helper.php';
require_once __DIR__ . '/../../models/category.php';

// Auth::checkAdmin();

$categoryModel = new Category();
$action = $_GET['action'] ?? 'index';

switch ($action) {

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'name'       => trim($_POST['name']),
                'slug'       => Helper::slugify($_POST['name']),
                'parent_id'  => $_POST['parent_id'] ?: null,
                'active'     => (int)$_POST['active']
            ];

            $categoryModel->create($data);
            header('Location: ../../views/admin/category/index.php?msg=created');
        }
        break;

    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'id'        => (int)$_POST['id'],
                'name'      => trim($_POST['name']),
                'slug' => !empty(trim($_POST['slug'])) ? trim($_POST['slug']) : Helper::slugify($_POST['name']),
                'parent_id' => $_POST['parent_id'] ?: null,
                'active'    => (int)$_POST['active']
            ];

            $categoryModel->update($data);
            header('Location: ../../views/admin/category/index.php?msg=updated');
            exit;
        }
        break;


    case 'delete':
        $categoryModel->delete($_GET['id']);
        header('Location: category.php');
        break;
}
