<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Thiếu ID đơn hàng!";
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT orders.*, users.name, users.email, users.phone, users.address 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        WHERE orders.id = $id";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/order.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        .container {
            width: 60%;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0b529e;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin: 12px 0;
            padding-left: 10px;
            border-left: 4px solid #0b529e;
            background-color: #f9f9f9;
        }

        strong {
            color: #333;
        }

        .add-btn {
            display: inline-block;
            text-align: center;
            background-color: #0b529e;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            transition: 0.3s;
        }

        .add-btn:hover {
            background-color: #4a76a4;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
    <p><strong>Ngày đặt:</strong> <?= $order['created_at'] ?></p>
    <p><strong>Lần cập nhật cuối:</strong> <?= $order['updated_at'] ?></p>
    <a href="../orders.php" class="add-btn">← Quay lại danh sách</a>
</div>
</body>
</html>
