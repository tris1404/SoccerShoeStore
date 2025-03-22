<?php
$conn = new mysqli("localhost", "root", "", "shoe_store");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo "<div class='popup-product'>";
        echo "<img src='{$row['image']}' alt='{$row['name']}'>";
        echo "<h2>{$row['name']}</h2>";
        echo "<p><strong>Giá:</strong> " . number_format($row['price'], 0, ',', '.') . "đ</p>";
        echo "<p><strong>Nhà cung cấp:</strong> {$row['brand']}</p>";

        // Hiển thị kích cỡ
        echo "<p><strong>Kích cỡ:</strong> ";
        if (!empty($row['sizes'])) {
            $sizes = explode(",", $row['sizes']);
            foreach ($sizes as $size) {
                echo "<span class='size-option'>$size</span> ";
            }
        } else {
            echo "Không có thông tin";
        }
        echo "</p>";

        echo "<p><strong>Số lượng:</strong> <input type='number' value='1' min='1' max='10'></p>";
        echo "<button onclick='addToCart({$row['id']})'>Thêm vào giỏ hàng</button>";
        echo "</div>";
    } else {
        echo "<p>Không tìm thấy sản phẩm!</p>";
    }
}

$conn->close();
?>
