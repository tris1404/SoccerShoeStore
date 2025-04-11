<?php
session_start();

// Kiểm tra nếu danh sách yêu thích đã được khởi tạo
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Xử lý yêu cầu xóa sản phẩm khỏi danh sách yêu thích
if (isset($_GET['remove'])) {
    $productId = $_GET['remove'];
    if (($key = array_search($productId, $_SESSION['favorites'])) !== false) {
        unset($_SESSION['favorites'][$key]);
    }
}

// Lấy danh sách sản phẩm yêu thích
$favorites = $_SESSION['favorites'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách yêu thích</title>
    <link rel="stylesheet" href="styles.css"> <!-- Đường dẫn tới file CSS -->
</head>
<body>
    <h1>Danh sách yêu thích</h1>

    <?php if (empty($favorites)): ?>
        <p>Danh sách yêu thích của bạn đang trống.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($favorites as $productId): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($productId); ?></td>
                        <td>Giá sản phẩm</td> <!-- Thay thế bằng giá thực tế -->
                        <td>
                            <a href="favorite.php?remove=<?php echo urlencode($productId); ?>">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="index.php">Quay lại trang chủ</a>
</body>
</html>