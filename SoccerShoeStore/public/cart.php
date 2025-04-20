<?php
session_start();
require_once '../config/database.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['user']['id']); // Giả sử user_id được lưu trong session khi đăng nhập
$userId = $isLoggedIn ? $_SESSION['user']['id'] : null;

// Hàm lưu giỏ hàng vào cookie
function saveCartToCookie($cart)
{
    setcookie('guest_cart', json_encode($cart), time() + (100 * 24 * 60 * 60), '/'); // 100 ngày
}

// Hàm lấy giỏ hàng từ cookie
function getCartFromCookie()
{
    return isset($_COOKIE['guest_cart']) ? json_decode($_COOKIE['guest_cart'], true) : [];
}

// Hàm lấy cart_id của người dùng
function getCartId($conn, $userId)
{
    $query = "SELECT id FROM cart WHERE user_id = $userId";
    $result = mysqli_query($conn, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['id'];
    }
    return null;
}

// Hợp nhất giỏ hàng từ cookie vào cơ sở dữ liệu
function mergeCartFromCookieToDatabase($conn, $userId, $cookieCart)
{
    if (empty($cookieCart)) {
        return;
    }

    // Lấy cart_id của người dùng
    $cartId = getCartId($conn, $userId);
    if (!$cartId) {
        $query = "INSERT INTO cart (user_id) VALUES ($userId)";
        mysqli_query($conn, $query);
        $cartId = mysqli_insert_id($conn);
    }

    // Hợp nhất từng sản phẩm từ cookie vào cơ sở dữ liệu
    foreach ($cookieCart as $key => $item) {
        list($productId, $size) = explode('_', $key);
        $quantity = $item['quantity'];
        $price = $item['price'];

        $query = "INSERT INTO cart_items (cart_id, product_id, qty, price, size) 
                  VALUES ($cartId, $productId, $quantity, $price, '$size') 
                  ON DUPLICATE KEY UPDATE qty = qty + $quantity";
        mysqli_query($conn, $query);
    }

    // Xóa cookie sau khi hợp nhất
    setcookie('guest_cart', '', time() - 3600, '/');
}

// Lấy giỏ hàng hiện tại
if ($isLoggedIn) {
    // Nếu người dùng đã đăng nhập, hợp nhất giỏ hàng từ cookie vào cơ sở dữ liệu
    $cookieCart = getCartFromCookie();
    mergeCartFromCookieToDatabase($conn, $userId, $cookieCart);

    // Lấy giỏ hàng từ cơ sở dữ liệu
    $cart = [];
    $query = "SELECT ci.*, p.name, p.image, p.price, p.discount_price, p.discount 
              FROM cart_items ci 
              JOIN products p ON ci.product_id = p.id 
              WHERE ci.cart_id = (SELECT id FROM cart WHERE user_id = $userId)";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        // Tính giá đã giảm nếu có discount
        $discounted_price = !empty($row['discount']) ? $row['price'] * (1 - $row['discount'] / 100) : $row['price'];

        $cart[$row['product_id'] . '_' . $row['size']] = [
            'name' => $row['name'],
            'price' => $row['price'], // Giá gốc
            'discount_price' => $discounted_price, // Giá đã giảm
            'image' => $row['image'],
            'quantity' => $row['qty'],
            'size' => $row['size']
        ];
    }
} else {
    // Nếu người dùng chưa đăng nhập, lấy giỏ hàng từ cookie
    $cart = getCartFromCookie();
}

// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_POST['product_image'];
    $productQuantity = (int)$_POST['product_quantity'];
    $productSize = $_POST['product_size'];

    $cartKey = $productId . '_' . $productSize;

    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity'] += $productQuantity;
    } else {
        $cart[$cartKey] = [
            'name' => $productName,
            'price' => $productPrice,
            'image' => $productImage,
            'quantity' => $productQuantity,
            'size' => $productSize
        ];
    }

    if ($isLoggedIn) {
        // Lưu vào cơ sở dữ liệu
        $cartId = getCartId($conn, $userId);
        if (!$cartId) {
            $query = "INSERT INTO cart (user_id) VALUES ($userId)";
            mysqli_query($conn, $query);
            $cartId = mysqli_insert_id($conn);
        }

        $query = "INSERT INTO cart_items (cart_id, product_id, qty, price, size) 
                  VALUES ($cartId, $productId, $productQuantity, $productPrice, '$productSize') 
                  ON DUPLICATE KEY UPDATE qty = qty + $productQuantity";
        mysqli_query($conn, $query);
    } else {
        // Lưu vào cookie
        saveCartToCookie($cart);
    }

    header('Location: cart.php');
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $cartKey = $_POST['remove_product_id'];

    if ($isLoggedIn) {
        // Xóa khỏi cơ sở dữ liệu
        list($productId, $size) = explode('_', $cartKey);
        $query = "DELETE FROM cart_items 
                  WHERE cart_id = (SELECT id FROM cart WHERE user_id = $userId) 
                  AND product_id = $productId AND size = '$size'";
        mysqli_query($conn, $query);
    } else {
        // Xóa khỏi cookie
        unset($cart[$cartKey]);
        saveCartToCookie($cart);
    }

    header('Location: cart.php');
    exit();
}

// Xử lý cập nhật số lượng sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cartKey = $_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];

    if ($isLoggedIn) {
        // Cập nhật trong cơ sở dữ liệu
        list($productId, $size) = explode('_', $cartKey);
        $query = "UPDATE cart_items 
                  SET qty = $newQuantity 
                  WHERE cart_id = (SELECT id FROM cart WHERE user_id = $userId) 
                  AND product_id = $productId AND size = '$size'";
        mysqli_query($conn, $query);
    } else {
        // Cập nhật trong cookie
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $newQuantity;
            saveCartToCookie($cart);
        }
    }

    echo 'Cập nhật số lượng thành công';
    exit();
}

// Hiển thị giỏ hàng
$cartItems = $cart;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cart.css" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Soccer Shoes Store</title>
</head>

<style>

</style>

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
                                            <th>Chọn</th> <!-- Thêm cột checkbox -->
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
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selected_products[]" value="<?= $key ?>" class="product-checkbox">
                                                    </td>
                                                    <td>
                                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="100">
                                                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                                                        <p style="float: left;">Size: <?= isset($item['size']) ? htmlspecialchars($item['size']) : 'Không có thông tin size' ?></p>
                                                    </td>
                                                    <td>
                                                        <span class="price-per-item" data-price="<?= !empty($item['discount_price']) ? $item['discount_price'] : $item['price'] ?>">
                                                            <?= number_format(!empty($item['discount_price']) ? $item['discount_price'] : $item['price'], 0, ',', '.') ?>đ
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="quantity-container">
                                                            <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity<?= $key; ?>', '<?= $key; ?>')">-</button>
                                                            <input type="text" id="quantity<?= $key; ?>" class="quantity-input" value="<?= htmlspecialchars($item['quantity']); ?>" readonly>
                                                            <button class="quantity-btn" onclick="changeQuantity(1, 'quantity<?= $key; ?>', '<?= $key; ?>')">+</button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $priceToUse = !empty($item['discount_price']) ? $item['discount_price'] : $item['price'];
                                                        echo number_format($item['quantity'] * $priceToUse, 0, ',', '.') . 'đ';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <form action="cart.php" method="POST">
                                                            <input type="hidden" name="remove_product_id" value="<?= $key ?>">
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
                                            <td colspan="3" style="text-align: left; font-weight: bold;">Tổng tiền:</td>
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
        <!-- Sidebar -->
        <!-- End sidebar -->

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
        // Thêm bớt sô lương sản phẩm
        function changeQuantity(amount, id, productId) {
            let quantityInput = document.getElementById(id);
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + amount;

            if (newQuantity >= 1) {
                quantityInput.value = newQuantity;

                // Gửi yêu cầu cập nhật số lượng đến server
                fetch('cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `update_quantity=1&product_id=${productId}&quantity=${newQuantity}`,
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log('Cập nhật số lượng thành công:', data);
                        location.reload(); // Tải lại trang để đồng bộ dữ liệu
                    })
                    .catch(error => {
                        console.error('Lỗi khi cập nhật số lượng:', error);
                    });
            }
        }
        // Xóa sản phẩm
        function deleteProduct(button) {
            let productItem = button.closest('.product-items');
            productItem.remove();
        }

        // Thêm sản phẩm từ yêu thích sang giỏ hàng
        function addToCart(button) {
            let productItem = button.closest('.product-items');
            let productName = productItem.querySelector('h3').innerText;
            alert(`Đã thêm sản phẩm "${productName}" vào giỏ hàng.`);
        }

        function redirectToCheckout() {
            window.location.href = "checkout.php"; // Thay bằng đường dẫn đúng
        }
    </script>
    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
                return;
            }

            // Thêm các checkbox được chọn vào form
            checkboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_products[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });
    </script>
    <script>
        // Hàm tính tổng tiền dựa trên các sản phẩm được chọn
        function calculateTotal() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            let total = 0;

            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const priceElement = row.querySelector('.price-per-item');
                const quantityElement = row.querySelector('.quantity-input');

                const price = parseFloat(priceElement.dataset.price); // Lấy giá từ data-price
                const quantity = parseInt(quantityElement.value); // Lấy số lượng từ input

                total += price * quantity; // Tính tổng tiền
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
    </script>
</body>

</html>