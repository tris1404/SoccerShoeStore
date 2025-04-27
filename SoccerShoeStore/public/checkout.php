<?php
session_start();
require_once '../config/database.php';

// Đảm bảo $userId luôn được định nghĩa
$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

// Hàm tạo mã đơn hàng duy nhất
function generateOrderCode($conn) {
    $date = date('Ymd');
    $query = "SELECT COUNT(*) as count FROM orders WHERE order_code LIKE 'ORD-$date%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'] + 1;
    return "ORD-$date-" . str_pad($count, 3, '0', STR_PAD_LEFT);
}

// Hàm xóa sản phẩm đã thanh toán khỏi giỏ hàng
function removeCartItems($conn, $userId, $selectedProducts) {
    if ($userId) {
        foreach ($selectedProducts as $key) {
            list($productId, $size) = explode('_', $key);
            $productId = (int)$productId;
            $size = mysqli_real_escape_string($conn, $size);
            $query = "DELETE FROM cart_items 
                      WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?) 
                      AND product_id = ? AND size = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'iis', $userId, $productId, $size);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        $cart = isset($_SESSION['guest_cart']) ? $_SESSION['guest_cart'] : [];
        foreach ($selectedProducts as $key) {
            unset($cart[$key]);
        }
        $_SESSION['guest_cart'] = $cart;
    }
}

// Xử lý form thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paymentMethod'])) {
    // Thu thập dữ liệu từ form
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $deliveryMethod = isset($_POST['deliveryMethod']) ? $_POST['deliveryMethod'] : null;
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : null;
    $orderNote = isset($_POST['orderNote']) ? mysqli_real_escape_string($conn, $_POST['orderNote']) : null;

    // Ánh xạ deliveryMethod sang giá trị ENUM
    $deliveryMethodEnum = null;
    if ($deliveryMethod === 'homeDelivery') {
        $deliveryMethodEnum = 'Giao hàng tận nhà';
    } elseif ($deliveryMethod === 'storePickup') {
        $deliveryMethodEnum = 'Nhận hàng tại cửa hàng';
    }

    // Ánh xạ paymentMethod sang giá trị ENUM
    $paymentMethodEnum = null;
    switch ($paymentMethod) {
        case 'cod':
            $paymentMethodEnum = 'Thanh toán khi nhận hàng';
            break;
        case 'bankTransfer':
            $paymentMethodEnum = 'Chuyển khoản ngân hàng';
            break;
        case 'creditCard':
            $paymentMethodEnum = 'Thẻ tín dụng/ ghi nợ';
            break;
    }

    // Kiểm tra dữ liệu bắt buộc
    if (empty($deliveryMethodEnum) || empty($paymentMethodEnum)) {
        echo "Vui lòng chọn phương thức giao hàng và thanh toán hợp lệ.";
        exit();
    }

    // Xử lý địa chỉ
    $address = '';
    $storeId = null;
    if ($deliveryMethod === 'homeDelivery') {
        $addressDetails = mysqli_real_escape_string($conn, $_POST['addressDetails']);
        $commune = mysqli_real_escape_string($conn, $_POST['commune']);
        $district = mysqli_real_escape_string($conn, $_POST['district']);
        $province = mysqli_real_escape_string($conn, $_POST['province']);
        $address = "$addressDetails, $commune, $district, $province";
    } elseif ($deliveryMethod === 'storePickup') {
        $storeId = isset($_POST['store']) ? mysqli_real_escape_string($conn, $_POST['store']) : null;
        if (empty($storeId)) {
            echo "Vui lòng chọn cửa hàng nhận.";
            exit();
        }
        $address = "Nhận tại cửa hàng: $storeId";
    } else {
        echo "Phương thức giao hàng không hợp lệ.";
        exit();
    }

    // Lấy danh sách sản phẩm được chọn
    $selectedProducts = [];
    if (isset($_POST['selected_products'])) {
        if (is_array($_POST['selected_products'])) {
            $selectedProducts = $_POST['selected_products'];
        } elseif (is_string($_POST['selected_products'])) {
            $selectedProducts = json_decode($_POST['selected_products'], true);
        }
    }
    if (empty($selectedProducts)) {
        echo 'Không có sản phẩm nào được chọn để thanh toán.';
        exit();
    }

    // Lấy thông tin sản phẩm
    $cartItems = [];

    if ($userId) {
        foreach ($selectedProducts as $key) {
            list($productId, $size) = explode('_', $key);
            $productId = (int)$productId;
            $size = mysqli_real_escape_string($conn, $size);

            $query = "SELECT ci.*, p.name, p.image, p.price, p.discount 
                      FROM cart_items ci 
                      JOIN products p ON ci.product_id = p.id 
                      WHERE ci.cart_id = (SELECT id FROM cart WHERE user_id = ?) 
                      AND ci.product_id = ? AND ci.size = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'iis', $userId, $productId, $size);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $discounted_price = !empty($row['discount']) ? $row['price'] * (1 - $row['discount'] / 100) : $row['price'];
                $cartItems[] = [
                    'product_id' => $row['product_id'],
                    'name' => $row['name'],
                    'image' => $row['image'],
                    'price' => $row['price'],
                    'discount_price' => $discounted_price,
                    'qty' => $row['qty'],
                    'size' => $row['size']
                ];
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $cart = isset($_SESSION['guest_cart']) ? $_SESSION['guest_cart'] : [];
        foreach ($selectedProducts as $key) {
            if (isset($cart[$key])) {
                $item = $cart[$key];
                $cartItems[] = [
                    'product_id' => explode('_', $key)[0],
                    'name' => $item['name'],
                    'image' => $item['image'],
                    'price' => $item['price'],
                    'discount_price' => $item['discount_price'],
                    'qty' => $item['quantity'],
                    'size' => $item['size']
                ];
            }
        }
    }

    // Lưu đơn hàng vào cơ sở dữ liệu
    $orderCode = generateOrderCode($conn);
    $query = "INSERT INTO orders (order_code, user_id, full_name, email, phone, address, delivery_method, store_id, payment_method, order_note, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Đang chờ')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sissssssss', $orderCode, $userId, $fullName, $email, $phone, $address, $deliveryMethodEnum, $storeId, $paymentMethodEnum, $orderNote);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Lỗi lưu đơn hàng: " . mysqli_stmt_error($stmt);
        exit();
    }
    $orderId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Lưu chi tiết sản phẩm vào order_items
    foreach ($cartItems as $item) {
        $productId = (int)$item['product_id'];
        $quantity = (int)$item['qty'];
        $price = (float)$item['price'];
        $discountPrice = (float)$item['discount_price'];
        $size = mysqli_real_escape_string($conn, $item['size']);

        $query = "INSERT INTO order_items (order_id, product_id, quantity, price, discount_price, size) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iiidds', $orderId, $productId, $quantity, $price, $discountPrice, $size);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Xóa sản phẩm đã thanh toán khỏi giỏ hàng
    removeCartItems($conn, $userId, $selectedProducts);

    // Chuyển hướng đến trang xác nhận
    header('Location: order_confirmation.php?order_code=' . urlencode($orderCode));
    exit();
}

// Lấy thông tin sản phẩm để hiển thị
$selectedProducts = [];
if (isset($_POST['selected_products'])) {
    if (is_array($_POST['selected_products'])) {
        $selectedProducts = $_POST['selected_products'];
    } elseif (is_string($_POST['selected_products'])) {
        $selectedProducts = json_decode($_POST['selected_products'], true);
    }
} elseif (isset($_SESSION['selected_products'])) {
    $selectedProducts = $_SESSION['selected_products'];
    unset($_SESSION['selected_products']); // Xóa sau khi sử dụng
}

$cartItems = [];

if ($userId) {
    foreach ($selectedProducts as $key) {
        list($productId, $size) = explode('_', $key);
        $productId = (int)$productId;
        $size = mysqli_real_escape_string($conn, $size);

        $query = "SELECT ci.*, p.name, p.image, p.price, p.discount 
                  FROM cart_items ci 
                  JOIN products p ON ci.product_id = p.id 
                  WHERE ci.cart_id = (SELECT id FROM cart WHERE user_id = ?) 
                  AND ci.product_id = ? AND ci.size = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iis', $userId, $productId, $size);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $discounted_price = !empty($row['discount']) ? $row['price'] * (1 - $row['discount'] / 100) : $row['price'];
            $cartItems[] = [
                'product_id' => $row['product_id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'discount_price' => $discounted_price,
                'qty' => $row['qty'],
                'size' => $row['size']
            ];
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $cart = isset($_SESSION['guest_cart']) ? $_SESSION['guest_cart'] : [];
    foreach ($selectedProducts as $key) {
        if (isset($cart[$key])) {
            $item = $cart[$key];
            $cartItems[] = [
                'product_id' => explode('_', $key)[0],
                'name' => $item['name'],
                'image' => $item['image'],
                'price' => $item['price'],
                'discount_price' => $item['discount_price'],
                'qty' => $item['quantity'],
                'size' => $item['size']
            ];
        }
    }
}

if (empty($selectedProducts)) {
    echo 'Không có sản phẩm nào được chọn để thanh toán. Vui lòng quay lại giỏ hàng.';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/checkout.css" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
    <title>Soccer Shoes Store</title>
</head>
<body>
    <div id="wrapper">
        <div class="checkout-container">
            <div class="shipping-info">
                <h2>Thông tin giao hàng</h2>
                <form id="shippingForm" method="POST" action="checkout.php">
                    <?php foreach ($selectedProducts as $key): ?>
                        <input type="hidden" name="selected_products[]" value="<?= htmlspecialchars($key) ?>">
                    <?php endforeach; ?>
                    <label for="fullName">Họ và tên:</label>
                    <input type="text" id="fullName" name="fullName" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" required>
                    <label>Chọn phương thức giao hàng:</label>
                    <div class="delivery-method">
                        <div class="radio-option">
                            <input type="radio" id="homeDelivery" name="deliveryMethod" value="homeDelivery" required>
                            <label for="homeDelivery">Giao hàng tận nơi</label>
                        </div>
                        <div id="addressFields" class="address-fields" style="display: none;">
                            <label for="addressDetails">Chi tiết địa chỉ:</label>
                            <input type="text" id="addressDetails" name="addressDetails">
                            <label for="commune">Xã:</label>
                            <input type="text" id="commune" name="commune">
                            <label for="district">Huyện:</label>
                            <input type="text" id="district" name="district">
                            <label for="province">Tỉnh:</label>
                            <input type="text" id="province" name="province">
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="storePickup" name="deliveryMethod" value="storePickup">
                            <label for="storePickup">Nhận tại cửa hàng</label>
                        </div>
                        <div id="storeSelection" class="store-selection" style="display: none;">
                            <label for="store">Chọn cửa hàng nhận:</label>
                            <select id="store" name="store">
                                <option value="store1">Cửa hàng 1</option>
                                <option value="store2">Cửa hàng 2</option>
                                <option value="store3">Cửa hàng 3</option>
                            </select>
                        </div>
                    </div>
                    <label>Chọn phương thanh toán:</label>
                    <div class="payment-method">
                        <div class="radio-option">
                            <input type="radio" id="cod" name="paymentMethod" value="cod" required>
                            <label for="cod">Thanh toán khi nhận hàng (COD)</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="bankTransfer" name="paymentMethod" value="bankTransfer">
                            <label for="bankTransfer">Chuyển khoản ngân hàng</label>
                        </div>
                        <div id="bankTransferDetails" class="bank-transfer-details" style="display: none;">
                            <p>Quý khách vui lòng chuyển khoản kèm nội dung là <strong>Số điện thoại mua hàng</strong></p>
                            <p><strong>Ngân hàng: Vietcombank</strong></p>
                            <p><strong>Số tài khoản:</strong> 233029569</p>
                            <p><strong>Chủ tài khoản:</strong> Nguyễn Tài Trí</p>
                            <p>Đơn hàng thanh toán chuyển khoản sẽ được <strong>miễn phí vận chuyển</strong> qua Giaohangtietkiem. Quý khách vui lòng chỉ chuyển khoản tiền sản phẩm.</p>
                            <p>Cảm ơn quý khách rất nhiều.</p>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="creditCard" name="paymentMethod" value="creditCard">
                            <label for="creditCard">Thẻ tín dụng/ghi nợ</label>
                        </div>
                    </div>
                    <div class="order-note">
                        <p>Ghi chú đơn hàng:</p>
                        <textarea rows="4" name="orderNote" placeholder="Nhập ghi chú cho đơn hàng của bạn"></textarea>
                    </div>
                    <div class="button">
                        <button type="button" onclick="checkout()" class="cart-button">Giỏ hàng</button>
                        <button type="submit" id="continuePaymentButton" class="continue-button">Hoàn tất thanh toán</button>
                    </div>
                </form>
            </div>
            <div class="order-summary">
                <div class="product-header">
                    <h1>Giày đá bóng chính hãng</h1>
                </div>
                <div class="list-products">
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="product-details">
                                <div class="product-image">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                                <div class="product-info">
                                    <h2><?= htmlspecialchars($item['name']) ?></h2>
                                    <p>Size: <?= htmlspecialchars($item['size']) ?></p>
                                    <p>Số lượng: <?= htmlspecialchars($item['qty']) ?></p>
                                </div>
                                <div class="price-product">
                                    <?php
                                    $priceToUse = !empty($item['discount_price']) ? $item['discount_price'] : $item['price'];
                                    $totalPrice = $priceToUse * $item['qty'];
                                    ?>
                                    <p><?= number_format($totalPrice, 0, ',', '.') ?>₫</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có sản phẩm nào được chọn để thanh toán.</p>
                    <?php endif; ?>
                </div>
                <div class="discount-container">
                    <input type="text" class="discount-input" placeholder="Nhập mã giảm giá">
                    <button class="apply-btn">Áp dụng</button>
                </div>
                <?php
                $subtotal = 0;
                foreach ($cartItems as $item) {
                    $priceToUse = !empty($item['discount_price']) ? $item['discount_price'] : $item['price'];
                    $subtotal += $item['qty'] * $priceToUse;
                }
                $shipping = 30000;
                $total = $subtotal + $shipping;
                ?>
                <div class="bill-info">
                    <span class="left">Tạm tính:</span>
                    <span class="right"><?= number_format($subtotal, 0, ',', '.') ?>₫</span>
                </div>
                <div class="bill-info">
                    <span class="left">Phí vận chuyển:</span>
                    <span class="right"><?= number_format($shipping, 0, ',', '.') ?>₫</span>
                </div>
                <div class="bill-sum">
                    <span class="left">Tổng cộng:</span>
                    <span class="right"><?= number_format($total, 0, ',', '.') ?>₫</span>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/scripts.js?v=1"></script>
    <script>
        document.querySelectorAll('input[name="deliveryMethod"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const addressFields = document.getElementById('addressFields');
                const storeSelection = document.getElementById('storeSelection');
                addressFields.style.display = this.value === 'homeDelivery' ? 'block' : 'none';
                storeSelection.style.display = this.value === 'storePickup' ? 'block' : 'none';
            });
        });

        document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const bankTransferDetails = document.getElementById('bankTransferDetails');
                bankTransferDetails.style.display = this.value === 'bankTransfer' ? 'block' : 'none';
            });
        });

        document.getElementById('shippingForm').addEventListener('submit', function(e) {
            const deliveryMethod = document.querySelector('input[name="deliveryMethod"]:checked');
            if (deliveryMethod && deliveryMethod.value === 'homeDelivery') {
                const addressFields = ['addressDetails', 'commune', 'district', 'province'];
                for (const field of addressFields) {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        e.preventDefault();
                        alert('Vui lòng điền đầy đủ thông tin địa chỉ!');
                        return;
                    }
                }
            } else if (deliveryMethod && deliveryMethod.value === 'storePickup') {
                const store = document.getElementById('store');
                if (!store.value) {
                    e.preventDefault();
                    alert('Vui lòng chọn cửa hàng nhận!');
                    return;
                }
            }
        });

        function checkout() {
            window.location.href = "cart.php";
        }
    </script>
</body>
</html>