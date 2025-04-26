<?php
session_start();

// Lưu URL của trang hiện tại để chuyển hướng sau khi đăng xuất
$previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'login.php';

// Xóa tất cả các session variables
session_unset();

// Hủy session
session_destroy();

// Thêm tham số vào URL để thông báo đăng xuất
$redirectUrl = $previousPage . (strpos($previousPage, '?') === false ? '?' : '&') . 'logout=success';
header("Location: $redirectUrl");
exit();
?>
