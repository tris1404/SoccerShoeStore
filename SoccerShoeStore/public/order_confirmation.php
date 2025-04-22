<?php
session_start();
require_once '../config/database.php';

$orderCode = isset($_GET['order_code']) ? $_GET['order_code'] : '';
$order = null;

if ($orderCode) {
    $query = "SELECT * FROM orders WHERE order_code = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $orderCode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/order_confirmation.css" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <title>Xác nhận đơn hàng</title>
</head>
<body>
    <div id="wrapper">
        <h1>Xác nhận đơn hàng</h1>
        <?php if ($order): ?>
            <p>Cảm ơn bạn đã đặt hàng!</p>
            <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($order['order_code']) ?></p>
            <p><strong>Họ và tên:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
            <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
            <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
            <a href="index.php">Quay lại trang chủ</a>
        <?php else: ?>
            <p>Không tìm thấy đơn hàng.</p>
        <?php endif; ?>
    </div>
</body>
</html>