<?php
session_start();
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    echo "<script>alert('$message');</script>";
    unset($_SESSION['success']);
}
// Xóa session khi người dùng vào trang admin.php


// Kiểm tra nếu chưa đăng nhập hoặc không phải admin/staff
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || 
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    
    // Chuyển về trang login
    header("Location: ../public/login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý Cửa Hàng Bán Giày</title>
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <!-- menu -->
        <?php include 'template/sidebar.php'; ?>
        <!-- End menu -->
        <!-- Nội dung chính -->
        <!-- header -->
        <?php include 'template/header.php'; ?>
        <!-- end header -->
         <!-- Nội dung chính --> 
        <main class="main-content">
            <!-- dashboad -->
            <?php include 'template/dashboard.php'; ?>
            <!-- end dashboad -->
        </main>
        <!-- end Nội dung chính -->     
        <!-- Footer -->
        <?php include 'template/footer.php'; ?> 
        <!-- End Footer -->   
    </div>
</body>
</html>

