<?php
/**
 * Chống XSS – lọc dữ liệu người dùng nhập
 */
if (!function_exists('checkXss')) {
    function checkXss($data)
    {
        if (is_array($data)) {
            return array_map('checkXss', $data);
        }

        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Lấy lại dữ liệu cũ khi validate lỗi
 */
if (!function_exists('old')) {
    function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

/**
 * Hiển thị lỗi validate
 */
if (!function_exists('error')) {
    function error($key)
    {
        return $_SESSION['errors'][$key] ?? '';
    }
}
