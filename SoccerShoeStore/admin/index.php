<?php
session_start();
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    echo "<script>alert('$message');</script>";
    unset($_SESSION['success']);
}

// Kiểm tra nếu chưa đăng nhập hoặc không phải admin/staff
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || 
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../public/login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin tổng quan
// Tổng số sản phẩm
$sql_products = "SELECT COUNT(*) as total FROM products_admin";
$result_products = mysqli_query($conn, $sql_products);
$total_products = mysqli_fetch_assoc($result_products)['total'];

// Tổng số khách hàng
$sql_customers = "SELECT COUNT(*) as total FROM users";
$result_customers = mysqli_query($conn, $sql_customers);
$total_customers = mysqli_fetch_assoc($result_customers)['total'];

// Tổng số danh mục
$sql_categories = "SELECT COUNT(*) as total FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);
$total_categories = mysqli_fetch_assoc($result_categories)['total'];

// Tổng số đơn hàng (giả sử có bảng orders)
$sql_orders = "SELECT COUNT(*) as total FROM orders"; // Thay "orders" bằng tên bảng đơn hàng thực tế
$result_orders = mysqli_query($conn, $sql_orders);
$total_orders = mysqli_fetch_assoc($result_orders)['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Admin</title>
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
    <link rel="stylesheet" href="assets/css/index.css?v=1">
    <script src="https://kit.fontawesome.com/1e93584bbb.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Menu -->
        <?php include 'template/sidebar.php'; ?>
        <!-- End menu -->
        <!-- Header -->
        <?php include 'template/header.php'; ?>
        <!-- End header -->
        <!-- Nội dung chính -->
        <main class="main-content">
            <h2>Trang chủ Admin</h2>

            <!-- Dashboard: Hiển thị thông tin tổng quan -->
            <div class="dashboard">
                <div class="card">
                    <i class="fa-solid fa-box"></i>
                    <h3>Tổng số sản phẩm</h3>
                    <p><?= $total_products ?></p>
                </div>
                <div class="card">
                    <i class="fa-solid fa-users"></i>
                    <h3>Tổng số khách hàng</h3>
                    <p><?= $total_customers ?></p>
                </div>
                <div class="card">
                    <i class="fa-solid fa-list"></i>
                    <h3>Tổng số danh mục</h3>
                    <p><?= $total_categories ?></p>
                </div>
                <div class="card">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <h3>Tổng số đơn hàng</h3>
                    <p><?= $total_orders ?></p>
                </div>
            </div>
        </main>
        <!-- End Nội dung chính -->
        <!-- Footer -->
        <?php include 'template/footer.php'; ?>
        <!-- End Footer -->
    </div>
</body>
</html>