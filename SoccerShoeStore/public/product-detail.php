<?php
// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Định nghĩa biến $source với giá trị mặc định
$source = isset($_GET['source']) ? $_GET['source'] : 'default';

// Khởi tạo biến $product
$product = null;

// Kết nối database
require_once '../config/database.php';

// Truy vấn sản phẩm từ database
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $product_from_db = $result->fetch_assoc();
    $product = [
        'name' => $product_from_db['name'],
        'price' => number_format($product_from_db['price'], 0, ',', '.') . 'đ',
        'image' => $product_from_db['image'], 
        'description' => $product_from_db['description'] ?? 'Không có mô tả',
        'size' => $product_from_db['size'] ?? ''
    ];
    $discounted_price = $product_from_db['price'] * (1 - $product_from_db['discount'] / 100);
} else {
    die('Sản phẩm không tồn tại trong cơ sở dữ liệu!');
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/product-detail.css?v=1" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css"> <!-- CSS của trang chủ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
    <!-- Font Awesome -->
    <!-- CSS riêng cho trang chi tiết -->
    <title><?= htmlspecialchars($product['name']) ?> - Soccer Shoes Store</title>
</head>

<body>
    <div id="wrapper">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- End header -->

        <!-- Nội dung chi tiết sản phẩm -->
        <div id="wrapper-container">
            <!-- Content -->
            <div class="content">
                <div class="product-detail-container">
                    <ul class="slick-dots">
                        <button class="btn-up"><i class="fa-solid fa-angle-up"></i></button>
                        <li class="slick-active">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='assets/img/default-product.png';">
                        </li>
                        <li class="presentation">
                            <img src="https://venifutebol.com.br/cdn/shop/files/ChuteiraCampoeSocietyPumaUltra5CarbonMGVolumeUpPack4.png?v=1733938699" alt="Hình ảnh phụ 1" onerror="this.src='assets/img/default-product.png';">
                        </li>
                        <li class="presentation">
                            <img src="https://venifutebol.com.br/cdn/shop/files/ChuteiraCampoeSocietyPumaUltra5CarbonMGVolumeUpPack5.png?v=1733938699" alt="Hình ảnh phụ 2" onerror="this.src='assets/img/default-product.png';">
                        </li>
                        <li class="presentation">
                            <img src="https://venifutebol.com.br/cdn/shop/files/ChuteiraCampoeSocietyPumaUltra5CarbonMGVolumeUpPack3.png?v=1733938699" alt="Hình ảnh phụ 3" onerror="this.src='assets/img/default-product.png';">
                        </li> 
                        <li class="presentation">
                            <img src="https://venifutebol.com.br/cdn/shop/files/ChuteiraCampoeSocietyPumaUltra5CarbonMGVolumeUpPack2.png?v=1733938699" alt="Hình ảnh phụ 4" onerror="this.src='assets/img/default-product.png';">
                        </li>
                        <button class="btn-down"><i class="fa-solid fa-angle-down"></i></button>
                    </ul>
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='assets/img/default-product.png';">
                    </div>
                    <div class="product-info">
                        <h1><?= htmlspecialchars($product['name']) ?></h1>
                        <div class="price-container">
                            <span class="price">
                                <?= number_format($discounted_price, 0, ',', '.') ?>
                            </span>
                            <?php if ($product_from_db['discount'] > 0): ?>
                                <span class="original-price" style="text-decoration: line-through; color: gray;">
                                    <?= number_format($product_from_db['price'], 0, ',', '.') ?>đ
                                </span>
                            <?php endif; ?>
                        </div>
                        <!-- <p class="description"><?= htmlspecialchars($product['description']) ?></p> -->
                        <div class="size-container">
                            <label for="size">Chọn kích thước:</label>
                            <div class="size-options">
                                <?php
                                if (isset($product['size']) && !empty($product['size'])) {
                                    $sizes = explode(",", $product['size']);
                                    foreach ($sizes as $size) {
                                        echo "<button class='size-btn' data-size='$size' onclick='selectSize($size)'>$size</button>";
                                    }
                                } else {
                                    for ($size = 38; $size <= 45; $size++) {
                                        echo "<button class='size-btn' data-size='$size' onclick='selectSize($size)'>$size</button>";
                                    }
                                }
                                ?>
                            </div>
                            <input type="hidden" id="selected-size" name="product_size" value="">
                        </div>
                        <div class="quantity-cart-container">
                            <div class="quantity-container">
                                <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity1')">-</button>
                                <input type="text" id="quantity1" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="changeQuantity(1, 'quantity1')">+</button>
                            </div>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                                <input type="hidden" name="product_price" value="<?= $product_from_db['price'] ?>"> <!-- Giá gốc -->
                                <input type="hidden" name="product_discount" value="<?= $product_from_db['discount'] ?>"> <!-- Phần trăm giảm giá -->
                                <input type="hidden" name="discount_price" value="<?= $discounted_price ?>"> <!-- Giá đã giảm -->
                                <input type="hidden" name="product_image" value="<?= htmlspecialchars($product['image']) ?>">
                                <input type="hidden" name="product_quantity" id="hidden-quantity" value="1">
                                <input type="hidden" id="hidden-selected-size" name="product_size" value="">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                            </form>
                            <button class="add-favorite-btn">Thêm vào yêu thích
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>
                        <button class="buy-now-btn">Mua ngay</button>
                        <div class="product-description">
                            <h2>Mô tả sản phẩm</h2>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                        </div>       
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- End footer -->
    </div>

    <script>
        function changeQuantity(amount, inputId) {
            const input = document.getElementById(inputId);
            let currentValue = parseInt(input.value);
            if (!isNaN(currentValue)) {
                currentValue += amount;
                if (currentValue < 1) {
                    currentValue = 1; // Không cho phép giá trị nhỏ hơn 1
                }
                input.value = currentValue;

                // Đồng bộ giá trị với input ẩn
                const hiddenQuantityInput = document.getElementById('hidden-quantity');
                hiddenQuantityInput.value = currentValue;
            }
        }

        function selectSize(size) {
            const selectedSizeInput = document.getElementById('selected-size');
            const hiddenSelectedSizeInput = document.getElementById('hidden-selected-size');
            
            // Gán giá trị size đã chọn vào các input ẩn
            selectedSizeInput.value = size;
            hiddenSelectedSizeInput.value = size;

            // Thêm hiệu ứng chọn size
            const sizeButtons = document.querySelectorAll('.size-btn');
            sizeButtons.forEach(button => button.classList.remove('selected')); // Xóa lớp 'selected' khỏi tất cả các nút
            document.querySelector(`.size-btn[data-size="${size}"]`).classList.add('selected'); // Thêm lớp 'selected' vào nút được chọn
        }
    </script>
</body>

</html>