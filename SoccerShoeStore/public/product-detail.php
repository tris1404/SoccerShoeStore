<?php
session_start();
require_once '../config/database.php';

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Định nghĩa biến $source với giá trị mặc định
$source = isset($_GET['source']) ? $_GET['source'] : 'default';

// Khởi tạo biến $product
$product = null;

// Truy vấn sản phẩm từ database
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
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
$stmt->close();

// Xử lý "Mua ngay"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $product_id = $id;
    $product_name = $product_from_db['name'];
    $product_price = $product_from_db['price'];
    $product_discount = $product_from_db['discount'];
    $discount_price = $discounted_price;
    $product_image = $product_from_db['image'];
    $product_quantity = isset($_POST['product_quantity']) ? (int)$_POST['product_quantity'] : 1;
    $product_size = isset($_POST['product_size']) ? $_POST['product_size'] : '';

    // Kiểm tra size
    if (empty($product_size)) {
        echo "<script>alert('Vui lòng chọn kích thước!');</script>";
    } else {
        // Thêm vào giỏ hàng
        $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
        $cart_key = $product_id . '_' . $product_size;

        if ($userId) {
            // Lấy hoặc tạo cart_id
            $query = "SELECT id FROM cart WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $cart_id = $result->fetch_assoc()['id'];
            } else {
                $query = "INSERT INTO cart (user_id) VALUES (?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $cart_id = $conn->insert_id;
            }
            $stmt->close();

            // Kiểm tra sản phẩm trong giỏ hàng
            $query = "SELECT qty FROM cart_items WHERE cart_id = ? AND product_id = ? AND size = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iis", $cart_id, $product_id, $product_size);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $new_quantity = $row['qty'] + $product_quantity;
                $query = "UPDATE cart_items SET qty = ? WHERE cart_id = ? AND product_id = ? AND size = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iiis", $new_quantity, $cart_id, $product_id, $product_size);
            } else {
                $query = "INSERT INTO cart_items (cart_id, product_id, qty, size) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("iiis", $cart_id, $product_id, $product_quantity, $product_size);
            }
            $stmt->execute();
            $stmt->close();
        } else {
            // Khách vãng lai
            $cart = isset($_SESSION['guest_cart']) ? $_SESSION['guest_cart'] : [];
            if (isset($cart[$cart_key])) {
                $cart[$cart_key]['quantity'] += $product_quantity;
            } else {
                $cart[$cart_key] = [
                    'name' => $product_name,
                    'price' => $product_price,
                    'discount_price' => $discount_price,
                    'image' => $product_image,
                    'quantity' => $product_quantity,
                    'size' => $product_size
                ];
            }
            $_SESSION['guest_cart'] = $cart;
        }

        // Lưu sản phẩm được chọn vào session
        $_SESSION['selected_products'] = [$cart_key];

        // Chuyển hướng đến checkout.php
        header('Location: checkout.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/product-detail.css?v=1" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
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
                        <div class="size-container">
                            <label for="size">Chọn kích thước:</label>
                            <div class="size-options">
                                <?php
                                if (isset($product['size']) && !empty($product['size'])) {
                                    $sizes = explode(",", $product['size']);
                                    foreach ($sizes as $size) {
                                        echo "<button class='size-btn' data-size='$size' onclick='selectSize(\"$size\")'>$size</button>";
                                    }
                                } else {
                                    for ($size = 38; $size <= 45; $size++) {
                                        echo "<button class='size-btn' data-size='$size' onclick='selectSize(\"$size\")'>$size</button>";
                                    }
                                }
                                ?>
                            </div>
                            <input type="hidden" id="selected-size" name="product_size" value="">
                        </div>

                        <div class="table-size">
                            <a href="../public/assets/img/table-size/quydoisize.jpg">Bảng quy đổi size</a>
                        </div>
                        
                        <div class="quantity-cart-container">
                            <div class="quantity-container">
                                <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity1')">-</button>
                                <input type="text" id="quantity1" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="changeQuantity(1, 'quantity1')">+</button>
                            </div>
                            <form action="cart.php" method="POST" id="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                                <input type="hidden" name="product_price" value="<?= $product_from_db['price'] ?>">
                                <input type="hidden" name="product_discount" value="<?= $product_from_db['discount'] ?>">
                                <input type="hidden" name="discount_price" value="<?= $discounted_price ?>">
                                <input type="hidden" name="product_image" value="<?= htmlspecialchars($product['image']) ?>">
                                <input type="hidden" name="product_quantity" id="hidden-quantity" value="1">
                                <input type="hidden" id="hidden-selected-size" name="product_size" value="">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                            </form>
                            <button class="add-favorite-btn">Thêm vào yêu thích
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <form action="product-detail.php?id=<?= $id ?>" method="POST" id="buy-now-form">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                                <input type="hidden" name="product_price" value="<?= $product_from_db['price'] ?>">
                                <input type="hidden" name="product_discount" value="<?= $product_from_db['discount'] ?>">
                                <input type="hidden" name="discount_price" value="<?= $discounted_price ?>">
                                <input type="hidden" name="product_image" value="<?= htmlspecialchars($product['image']) ?>">
                                <input type="hidden" name="product_quantity" id="buy-now-quantity" value="1">
                                <input type="hidden" id="buy-now-selected-size" name="product_size" value="">
                                <input type="hidden" name="buy_now" value="1">
                                <button type="submit" class="buy-now-btn">Mua ngay</button>
                            </form>
                            
                        </div>
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
                    currentValue = 1;
                }
                input.value = currentValue;

                // Đồng bộ giá trị với input ẩn
                document.getElementById('hidden-quantity').value = currentValue;
                document.getElementById('buy-now-quantity').value = currentValue;
            }
        }

        function selectSize(size) {
            const selectedSizeInput = document.getElementById('selected-size');
            const hiddenSelectedSizeInput = document.getElementById('hidden-selected-size');
            const buyNowSelectedSizeInput = document.getElementById('buy-now-selected-size');
            
            // Gán giá trị size đã chọn
            selectedSizeInput.value = size;
            hiddenSelectedSizeInput.value = size;
            buyNowSelectedSizeInput.value = size;

            // Thêm hiệu ứng chọn size
            const sizeButtons = document.querySelectorAll('.size-btn');
            sizeButtons.forEach(button => button.classList.remove('selected'));
            document.querySelector(`.size-btn[data-size="${size}"]`).classList.add('selected');
        }
    </script>
</body>
</html>