<?php
session_start(); // Bắt đầu session

// Lưu URL của trang hiện tại để chuyển hướng sau khi đăng xuất
$previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'login.php'; // Nếu không có HTTP_REFERER thì chuyển đến trang login

// Xóa tất cả các session variables
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng người dùng về trang trước đó hoặc trang đăng nhập
header("Location: $previousPage"); // Quay lại trang trước đó hoặc trang đăng nhập nếu không có trang trước đó
exit();
?>
