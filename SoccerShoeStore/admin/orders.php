<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Truy vấn kết hợp bảng orders và users
$sql = "SELECT orders.*, users.name, users.email, users.phone 
        FROM orders 
        JOIN users ON orders.user_id = users.id
        WHERE users.name LIKE '%$search%' 
            OR users.email LIKE '%$search%' 
            OR users.phone LIKE '%$search%' 
            OR orders.id LIKE '%$search%'
        ORDER BY orders.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hóa đơn</title>
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
    <link rel="stylesheet" href="assets/css/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <?php include 'template/sidebar.php'; ?>
    <?php include 'template/header.php'; ?>

    <main class="main-content">
        <h2>Quản lý Hóa đơn</h2>

        <form method="GET" action="orders.php" style="margin-bottom: 20px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tên, email, SĐT, mã đơn..." style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 8px 12px; border: 1px solid #ccc; background-color:#0b529e; color: white; border-radius: 4px; cursor: pointer;">Tìm kiếm</button>
        </form>

        <table>
            <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>Số ĐT</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>
                        <form action="XuLy_Order/update_status.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <?php
                                $statuses = ['Processing', 'Confirmed', 'Shipping', 'Delivered', 'Cancelled'];
                                foreach ($statuses as $status) {
                                    $selected = $status == $row['status'] ? 'selected' : '';
                                    echo "<option value='$status' $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </td>
                    <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
                    <td>
                        <a class="edit-btn" href="XuLy_Order/view.php?id=<?= $row['id'] ?>">Chi tiết</a> |
                        <a class="delete-btn" href="XuLy_Order/delete.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này không?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <?php include 'template/footer.php'; ?>
</div>
</body>
</html>
