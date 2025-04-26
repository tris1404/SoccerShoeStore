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
$sql = "SELECT orders.* 
        FROM orders 
        WHERE orders.id = $id";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

$order = $result->fetch_assoc();

// Truy vấn các mục đơn hàng từ order_items
$items_sql = "SELECT oi.*, p.name as product_name 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = $id";
$items_result = $conn->query($items_sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="../assets/css/order.css">
    <link rel="icon" type="image/x-icon" href="../../public/assets/img/football-shoes.png">
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

        /* Table for order items */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: var(--card-background);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .items-table th,
        .items-table td {
            border: 1px solid var(--border-color);
            padding: 12px;
            text-align: left;
            font-size: 14px;
            color: var(--text-muted);
        }

        .items-table th {
            background: linear-gradient(135deg, var(--primary-color), #d4af37);
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
        }

        .items-table td {
            background: rgba(255, 255, 255, 0.5);
        }

        .no-items {
            text-align: center;
            font-style: italic;
            color: var(--text-muted);
            padding: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .items-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chi tiết đơn hàng <?= htmlspecialchars($order['order_code']) ?></h2>
    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['full_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Phương thức giao hàng:</strong> <?= htmlspecialchars($order['delivery_method']) ?></p>
    <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
    <p><strong>Ghi chú đơn hàng:</strong> <?= htmlspecialchars($order['order_note'] ?: 'Không có') ?></p>
    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
    <p><strong>Ngày đặt:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
    <p><strong>Ngày chỉnh sửa gần nhất:</strong> <?= date("d/m/Y H:i", strtotime($order['updated_at'])) ?></p>

    <h3>Danh sách sản phẩm</h3>
    <?php if ($items_result->num_rows > 0): ?>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Kích thước</th>
                    <th>Giá</th>
                    <th>Giá giảm</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= $item['size'] ?></td>
                        <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= number_format($item['discount_price'], 0, ',', '.') ?> VNĐ</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-items">Không có sản phẩm trong đơn hàng này.</p>
    <?php endif; ?>

    <a href="../orders.php" class="add-btn">← Quay lại danh sách</a>
</div>
</body>
</html>