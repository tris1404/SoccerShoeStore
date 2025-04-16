<?php
session_start();

$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý thêm sản phẩm vào giỏ hàng qua AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $productId = intval($_POST['product_id']);
    $productName = $_POST['product_name'];
    $productPrice = floatval($_POST['product_price']);
    $productImage = $_POST['product_image'];
    $productQuantity = intval($_POST['product_quantity']);
    $productSize = $_POST['product_size'];
    $discount = floatval($_POST['discount']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $productQuantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'name' => $productName,
            'price' => $productPrice,
            'discount_price' => ($discount > 0) ? $productPrice * (1 - $discount / 100) : NULL,
            'image' => $productImage,
            'quantity' => $productQuantity,
            'size' => $productSize
        ];
    }

    echo json_encode(['status' => 'success', 'message' => 'Đã thêm sản phẩm vào giỏ hàng!']);
    exit;
}

// Xử lý lấy thông tin sản phẩm
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo "<div class='cart-popup-content'>";
        echo "<span class='close-btn' onclick='closePopup()'>×</span>";
        
        echo "<div class='cart-popup-image'>";
        if (!empty($row['image'])) {
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
        } else {
            echo "<img src='assets/img/default-product.png' alt='Hình ảnh không tồn tại'>";
        }
        echo "</div>";

        echo "<div class='cart-popup-details'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        
        echo "<p class='price'>";
        if ($row['discount'] > 0 && $row['discount_price']) {
            echo "<span style='text-decoration: line-through; color: #999;'>" . number_format($row['price'], 0, ',', '.') . "đ</span> ";
            echo "<span style='color: red;'>" . number_format($row['discount_price'], 0, ',', '.') . "đ</span>";
        } else {
            echo "Giá: " . number_format($row['price'], 0, ',', '.') . "đ";
        }
        echo "</p>";
        
        echo "<p>Nhãn hiệu: " . htmlspecialchars($row['brand']) . "</p>";

        echo "<p>Kích cỡ: ";
        if (!empty($row['size'])) {
            $sizes = explode(",", $row['size']);
            echo "<select id='popup-size-" . $row['id'] . "'>";
            foreach ($sizes as $size) {
                echo "<option value='$size'>$size</option>";
            }
            echo "</select>";
        } else {
            echo "Không có thông tin";
        }
        echo "</p>";

        echo "<p>Số lượng: <input type='number' id='popup-quantity-" . $row['id'] . "' value='1' min='1' max='10'></p>";
        echo "</div>";

        echo "<div class='cart-popup-actions'>";
        echo "<button class='view-details-btn' onclick=\"window.location.href='product-detail.php?id=" . $row['id'] . "&source=natural'\">Xem chi tiết</button>";
        echo "<button class='add-to-cart-btn' onclick='addToCart(" . $row['id'] . ", \"" . htmlspecialchars($row['name'], ENT_QUOTES) . "\", " . $row['price'] . ", \"" . htmlspecialchars($row['image'], ENT_QUOTES) . "\", " . $row['discount'] . ")'>Thêm vào giỏ hàng</button>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>Không tìm thấy sản phẩm!</p>";
    }
}

$conn->close();
?>