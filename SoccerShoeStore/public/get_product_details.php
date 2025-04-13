<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo "<div class='cart-popup-content'>";
        echo "<span class='close-btn' onclick='closePopup()'>×</span>";
        
        // Hiển thị hình ảnh
        echo "<div class='cart-popup-image'>";
        $image_path = '../admin/uploads/' . $row['image'];
        if (file_exists($image_path)) {
            echo "<img src='$image_path' alt='{$row['name']}'>";
        } else {
            echo "<p>Hình ảnh không tồn tại</p>";
        }
        echo "</div>";

        // Hiển thị thông tin sản phẩm
        echo "<div class='cart-popup-details'>";
        echo "<h3>{$row['name']}</h3>";
        echo "<p class='price'>Giá: " . number_format($row['price'], 0, ',', '.') . "đ</p>";
        echo "<p>Nhãn hiệu: {$row['brand']}</p>";

        // Hiển thị kích cỡ
        echo "<p>Kích cỡ: ";
        if (!empty($row['size'])) {
            $sizes = explode(",", $row['size']);
            echo "<select id='popup-size-{$row['id']}'>";
            foreach ($sizes as $size) {
                echo "<option value='$size'>$size</option>";
            }
            echo "</select>";
        } else {
            echo "Không có thông tin";
        }
        echo "</p>";

        echo "<p>Số lượng: <input type='number' id='popup-quantity-{$row['id']}' value='1' min='1' max='10'></p>";
        echo "</div>";

        // Nút hành động
        echo "<div class='cart-popup-actions'>";
        echo "<button class='view-details-btn' onclick=\"window.location.href='product-detail.php?id={$row['id']}'\">Xem chi tiết</button>";
        echo "<button class='add-to-cart-btn' onclick='addToCart({$row['id']})'>Thêm vào giỏ hàng</button>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>Không tìm thấy sản phẩm!</p>";
    }
}

$conn->close();
?>