<?php
session_start();
require_once '../config/database.php';

$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
$orders = [];
$order = null;
$orderItems = [];
$error = '';

if ($userId) {
    // Truy xuất tất cả đơn hàng của người dùng đã đăng nhập
    $query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}

// Xử lý tra cứu đơn hàng hoặc xem chi tiết đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['order_code'])) {
    $orderCode = isset($_POST['order_code']) ? trim($_POST['order_code']) : (isset($_GET['order_code']) ? $_GET['order_code'] : '');
    
    if (empty($orderCode)) {
        $error = 'Vui lòng nhập mã đơn hàng!';
    } else {
        // Truy xuất thông tin đơn hàng
        $query = "SELECT * FROM orders WHERE order_code = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $orderCode);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($order) {
            // Truy xuất danh sách sản phẩm trong đơn hàng
            $query = "SELECT oi.*, p.name, p.image FROM order_items oi 
                      JOIN products p ON oi.product_id = p.id 
                      WHERE oi.order_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $order['id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $orderItems = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Không tìm thấy đơn hàng với mã này!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="assets/css/track_order.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
    <title>Theo dõi đơn hàng</title>
</head>
<body>
    <div id="wrapper">
        <h1>Theo dõi đơn hàng</h1>

        <?php if ($userId && empty($order)): ?>
            <div class="order-list">
                <h2>Danh sách đơn hàng</h2>
                <?php if (empty($orders)): ?>
                    <p class="error-message">Bạn chưa có đơn hàng nào.</p>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <div class="order-item">
                            <p><strong>Mã đơn hàng:</strong> <a href="track_order.php?order_code=<?= urlencode($ord['order_code']) ?>"><?= htmlspecialchars($ord['order_code']) ?></a></p>
                            <p><strong>Ngày đặt:</strong> <?= date("d/m/Y H:i", strtotime($ord['created_at'])) ?></p>
                            <p><strong>Trạng thái:</strong> <span class="status status-<?= strtolower(str_replace(' ', '-', $ord['status'])) ?>"><?= htmlspecialchars($ord['status']) ?></span></p>
                            <?php
                            // Tính tổng tiền
                            $query = "SELECT SUM(quantity * discount_price) as total FROM order_items WHERE order_id = ?";
                            $stmt = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt, 'i', $ord['id']);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $total = mysqli_fetch_assoc($result)['total'];
                            mysqli_stmt_close($stmt);
                            ?>
                            <p><strong>Tổng tiền:</strong> <?= number_format($total, 0, ',', '.') ?>₫</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php elseif (!$userId): ?>
            <div class="track-form">
                <form method="POST" action="track_order.php" id="trackForm">
                    <label for="order_code">Nhập mã đơn hàng:</label>
                    <input type="text" id="order_code" name="order_code" placeholder="VD: ORD-20250422-001" required>
                    <button type="submit">Tra cứu</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($order): ?>
            <div class="order-info">
                <h2>Thông tin đơn hàng</h2>
                <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($order['order_code']) ?></p>
                <p><strong>Họ và tên:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                <p><strong>Trạng thái:</strong> <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>"><?= htmlspecialchars($order['status']) ?></span></p>
                <p><strong>Ngày đặt:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
            </div>
            <?php if (!empty($orderItems)): ?>
                <div class="order-items">
                    <h2>Chi tiết sản phẩm</h2>
                    <?php foreach ($orderItems as $item): ?>
                        <div class="item">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-image">
                            <div class="item-details">
                                <p><strong>Sản phẩm:</strong> <?= htmlspecialchars($item['name']) ?></p>
                                <p><strong>Kích thước:</strong> <?= htmlspecialchars($item['size']) ?></p>
                                <p><strong>Số lượng:</strong> <?= $item['quantity'] ?></p>
                                <p><strong>Giá:</strong> <?= number_format($item['discount_price'] * $item['quantity'], 0, ',', '.') ?>₫</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <a href="track_order.php">Quay lại danh sách đơn hàng</a>
        <?php endif; ?>
        <a href="index.php">Quay lại trang chủ</a>
    </div>
</body>
</html>