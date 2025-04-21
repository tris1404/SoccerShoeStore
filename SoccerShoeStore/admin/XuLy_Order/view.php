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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables */
        :root {
            --primary-color: #e6c200; /* Vàng nhạt ánh kim */
            --background-color: #f5f5f5; /* Trắng ngọc trai */
            --card-background: rgba(255, 255, 255, 0.7); /* Kính mờ nhẹ */
            --text-color: #333333; /* Đen nhạt */
            --text-muted: #666666; /* Xám trung */
            --border-color: rgba(0, 0, 0, 0.1); /* Viền mờ */
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease-in-out;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            margin: 20px;
        }

        .container {
            width: 60%;
            margin: auto;
            background: var(--card-background);
            backdrop-filter: blur(5px);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        p {
            font-size: 16px;
            margin: 12px 0;
            padding-left: 10px;
            border-left: 4px solid var(--primary-color);
            background: rgba(255, 255, 255, 0.5);
            color: var(--text-muted);
        }

        strong {
            color: var(--text-color);
        }

        .add-btn {
            display: inline-block;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), #d4af37);
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: var(--transition);
        }

        .add-btn:hover {
            background: linear-gradient(135deg, #d4af37, var(--primary-color));
            box-shadow: 0 0 15px rgba(230, 194, 0, 0.5);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
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