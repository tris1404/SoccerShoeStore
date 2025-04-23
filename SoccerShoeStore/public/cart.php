<?php
session_start();
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once '../config/database.php';

// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['user']['id']);
$userId = $isLoggedIn ? $_SESSION['user']['id'] : null;

// Hàm lưu giỏ hàng vào session
function saveCartToSession($cart)
{
    $_SESSION['guest_cart'] = $cart;
}

// Hàm lấy giỏ hàng từ session
function getCartFromSession()
{
    return isset($_SESSION['guest_cart']) ? $_SESSION['guest_cart'] : [];
}

// Hàm lấy cart_id của người dùng
function getCartId($conn, $userId)
{
    $query = "SELECT id FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_stmt_close($stmt);
        return $row['id'];
    }
    mysqli_stmt_close($stmt);
    return null;
}

// Hợp nhất giỏ hàng từ session vào cơ sở dữ liệu
function mergeCartFromSessionToDatabase($conn, $userId, $sessionCart)
{
    if (empty($sessionCart)) {
        return;
    }

    $cartId = getCartId($conn, $userId);
    if (!$cartId) {
        $query = "INSERT INTO cart (user_id) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $cartId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
    }

    foreach ($sessionCart as $key => $item) {
        list($productId, $size) = explode('_', $key);
        $productId = (int)$productId;
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];
        $size = mysqli_real_escape_string($conn, $size);

        $query = "INSERT INTO cart_items (cart_id, product_id, qty, price, size) 
                  VALUES (?, ?, ?, ?, ?) 
                  ON DUPLICATE KEY UPDATE qty = qty + ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiidss', $cartId, $productId, $quantity, $price, $size, $quantity);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    unset($_SESSION['guest_cart']);
}

// Lấy giỏ hàng hiện tại
if ($isLoggedIn) {
    $sessionCart = getCartFromSession();
    mergeCartFromSessionToDatabase($conn, $userId, $sessionCart);

    $cart = [];
    $query = "SELECT ci.*, p.name, p.image, p.price, p.discount 
              FROM cart_items ci 
              JOIN products p ON ci.product_id = p.id 
              WHERE ci.cart_id = (SELECT id FROM cart WHERE user_id = ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        
        $discounted_price = !empty($row['discount']) ? $row['price'] * (1 - $row['discount'] / 100) : $row['price'];

        $key = $row['product_id'] . '_' . $row['size'];
        $cart[$key] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'discount_price' => $discounted_price,
            'image' => $row['image'],
            'quantity' => $row['qty'],
            'size' => $row['size']
        ];
    }
    mysqli_stmt_close($stmt);
} else {
    $cart = getCartFromSession();
    foreach ($cart as $key => $item) {
        $cart[$key]['discount_price'] = (isset($item['discount']) && $item['discount'] > 0)
            ? $item['price'] * (1 - $item['discount'] / 100)
            : $item['price'];
    }
}

// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_POST['product_id'], $_POST['product_name'], $_POST['product_price'], $_POST['product_image'], $_POST['product_quantity'], $_POST['product_size'])) {
        die('Dữ liệu không hợp lệ');
    }

    $productId = (int)$_POST['product_id'];
    $productName = mysqli_real_escape_string($conn, $_POST['product_name']);
    $productPrice = (float)$_POST['product_price'];
    $productDiscount = isset($_POST['product_discount']) ? (float)$_POST['product_discount'] : 0;
    $discountPrice = $productPrice * (1 - $productDiscount / 100);
    $productImage = mysqli_real_escape_string($conn, $_POST['product_image']);
    $productQuantity = (int)$_POST['product_quantity'];
    $productSize = mysqli_real_escape_string($conn, $_POST['product_size']);

    $cartKey = $productId . '_' . $productSize;

    if ($isLoggedIn) {
        $cartId = getCartId($conn, $userId);
        if (!$cartId) {
            $query = "INSERT INTO cart (user_id) VALUES (?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $userId);
            mysqli_stmt_execute($stmt);
            $cartId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);
        }

        $query = "INSERT INTO cart_items (cart_id, product_id, qty, price, discount_price, size) 
                  VALUES (?, ?, ?, ?, ?, ?) 
                  ON DUPLICATE KEY UPDATE qty = qty + ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiiddsi', $cartId, $productId, $productQuantity, $productPrice, $discountPrice, $productSize, $productQuantity);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $cart = getCartFromSession();
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $productQuantity;
        } else {
            $cart[$cartKey] = [
                'name' => $productName,
                'price' => $productPrice,
                'discount_price' => $discountPrice,
                'discount' => $productDiscount,
                'image' => $productImage,
                'quantity' => $productQuantity,
                'size' => $productSize
            ];
        }
        saveCartToSession($cart);
    }

    header('Location: cart.php');
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $cartKey = $_POST['remove_product_id'];

    if ($isLoggedIn) {
        list($productId, $size) = explode('_', $cartKey);
        $productId = (int)$productId;
        $size = mysqli_real_escape_string($conn, $size);
        $query = "DELETE FROM cart_items 
                  WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?) 
                  AND product_id = ? AND size = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iis', $userId, $productId, $size);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $cart = getCartFromSession();
        unset($cart[$cartKey]);
        saveCartToSession($cart);
    }

    header('Location: cart.php');
    exit();
}

// Xử lý cập nhật số lượng sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cartKey = $_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];

    if ($isLoggedIn) {
        list($productId, $size) = explode('_', $cartKey);
        $productId = (int)$productId;
        $size = mysqli_real_escape_string($conn, $size);
        $query = "UPDATE cart_items 
                  SET qty = ? 
                  WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?) 
                  AND product_id = ? AND size = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiis', $newQuantity, $userId, $productId, $size);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $cart = getCartFromSession();
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $newQuantity;
            saveCartToSession($cart);
        }
    }

    echo 'Cập nhật số lượng thành công';
    exit();
}

// Hiển thị giỏ hàng
$cartItems = $cart;

error_log('Cart Items: ' . print_r($cartItems, true));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cart.css" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
    <title>Soccer Shoes Store</title>
</head>
<body>
    <div id="wrapper">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- End header -->

        <!-- Wrapper-container -->
        <div id="wrapper-container">
            <!-- Content -->
            <div class="content">
                <div class="wrapper-cart">
                    <div class="shopping-cart">
                        <div class="top-bar">
                            <div class="title">
                                <h4>Giỏ hàng</h4>
                            </div>
                            <div class="numbers-product">Tổng sản phẩm: </div>
                        </div>
                        <div class="product-container">
                            <div class="product-section">
                                <div class="section-title">Sản phẩm trong giỏ hàng</div>
                                <table class="product-table">
                                    <thead>
                                        <tr>
                                            <th>Chọn</th>
                                            <th>Sản phẩm</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($cartItems)): ?>
                                            <?php foreach ($cartItems as $key => $item): ?>
                                                
                                                <tr data-key="<?= htmlspecialchars($key) ?>" data-price="<?= htmlspecialchars($item['discount_price']) ?>" data-quantity="<?= htmlspecialchars($item['quantity']) ?>">
                                                    <td>
                                                        <input type="checkbox" name="selected_products[]" value="<?= htmlspecialchars($key) ?>" class="product-checkbox">
                                                    </td>
                                                    <td>
                                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="100">
                                                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                                                        <p style="float: left;">Size: <?= htmlspecialchars($item['size']) ?></p>
                                                    </td>
                                                    <td>
                                                        <span class="price-per-item" data-price="<?= htmlspecialchars($item['discount_price']) ?>">
                                                            <?= number_format($item['discount_price'], 0, ',', '.') ?>đ
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="quantity-container">
                                                            <button class="quantity-btn" onclick="changeQuantity(-1, '<?= htmlspecialchars($key) ?>')">-</button>
                                                            <input type="text" id="quantity<?= htmlspecialchars($key) ?>" class="quantity-input" value="<?= htmlspecialchars($item['quantity']) ?>" readonly>
                                                            <button class="quantity-btn" onclick="changeQuantity(1, '<?= htmlspecialchars($key) ?>')">+</button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="total-price">
                                                            <?= number_format($item['quantity'] * $item['discount_price'], 0, ',', '.') ?>đ
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form action="cart.php" method="POST">
                                                            <input type="hidden" name="remove_product_id" value="<?= htmlspecialchars($key) ?>">
                                                            <button type="submit" class="delete-btn">Xóa</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6">Giỏ hàng của bạn đang trống.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" style="text-align: left; font-weight: bold;">Tổng tiền:</td>
                                            <td colspan="2" style="text-align: right; font-weight: bold; color:red">
                                                <span id="total-price">0 đ</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="bill">
                    <form action="checkout.php" method="POST" id="checkout-form">
                        <button type="submit" class="checkout-btn">Thanh toán</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End content -->
    </div>
    <!-- End wrapper-container -->

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <!-- End footer -->
    </div>
    <button id="backToTop"><i class="fa-solid fa-angle-up"></i></button>
    <button id="zaloChat" onclick="window.open('https://zalo.me/09xxxxxxxx', '_blank')">
        <img src="https://stc-zaloprofile.zdn.vn/pc/v1/images/zalo_sharelogo.png" alt="Chat Zalo">
    </button>

    <script src="assets/js/scripts.js?v=1"></script>
    <script>
        // Hàm thay đổi số lượng sản phẩm
        function changeQuantity(amount, key) {
            const row = document.querySelector(`tr[data-key="${key}"]`);
            const quantityInput = row.querySelector('.quantity-input');
            const pricePerItem = parseFloat(row.dataset.price);
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + amount;

            if (newQuantity >= 1) {
                quantityInput.value = newQuantity;
                row.dataset.quantity = newQuantity;

                // Cập nhật tổng tiền cho sản phẩm
                const totalPriceElement = row.querySelector('.total-price');
                totalPriceElement.textContent = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(newQuantity * pricePerItem);

                // Gửi yêu cầu cập nhật số lượng đến server
                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_quantity=1&product_id=${key}&quantity=${newQuantity}`,
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Cập nhật số lượng thành công:', data);
                    calculateTotal();
                })
                .catch(error => {
                    console.error('Lỗi khi cập nhật số lượng:', error);
                });
            }
        }

        // Hàm tính tổng tiền
        function calculateTotal() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            let total = 0;

            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const priceElement = row.querySelector('.price-per-item');
                const quantityElement = row.querySelector('.quantity-input');

                const price = parseFloat(priceElement.dataset.price);
                const quantity = parseInt(quantityElement.value);

                total += price * quantity;
            });

            // Cập nhật tổng tiền
            document.getElementById('total-price').textContent = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(total);
        }

        // Gắn sự kiện cho các checkbox
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        // Gắn sự kiện khi thay đổi số lượng
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', calculateTotal);
        });

        // Gắn sự kiện cho form thanh toán
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
                return;
            }

            checkboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_products[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });
    </script>
</body>
</html>