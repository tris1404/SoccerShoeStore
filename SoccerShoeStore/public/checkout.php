<?php
session_start();
require_once '../config/database.php';

// Lấy danh sách sản phẩm được chọn
$selectedProducts = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];
if (empty($selectedProducts)) {
    echo 'Không có sản phẩm nào được chọn để thanh toán.';
    exit();
}

// Lấy thông tin sản phẩm
$userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
$cartItems = [];

if ($userId) {
    // Người dùng đã đăng nhập: Lấy từ cơ sở dữ liệu
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
    // Người dùng chưa đăng nhập: Lấy từ session
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

// Hiển thị giao diện thanh toán
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/checkout.css" type="text/css">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Soccer Shoes Store</title>
</head>
<body>
    <div id="wrapper">
        <!-- Wrapper-container -->
        <div class="checkout-container">
            <!-- Phần trái: Thông tin giao hàng -->
            <div class="shipping-info">
                <h2>Thông tin giao hàng</h2>
                <form id="shippingForm">
                    <label for="fullName">Họ và tên:</label>
                    <input type="text" id="fullName" name="fullName" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" required>

                    <label>Chọn phương thức giao hàng:</label>
                    <div class="delivery-method">
                        <div class="radio-option">
                            <input type="radio" id="homeDelivery" name="deliveryMethod" value="homeDelivery">
                            <label for="homeDelivery">Giao hàng tận nơi</label>
                        </div>
                        <div id="addressFields" class="address-fields">
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
                        <div id="storeSelection" class="store-selection">
                            <label for="store">Chọn cửa hàng nhận:</label>
                            <select id="store" name="store">
                                <option value="store1">Cửa hàng 1</option>
                                <option value="store2">Cửa hàng 2</option>
                                <option value="store3">Cửa hàng 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="payment-method">
                        <h3>Chọn phương thức thanh toán:</h3>
                        <div class="radio-option">
                            <input type="radio" id="cod" name="paymentMethod" value="cod" required>
                            <label for="cod">Thanh toán khi nhận hàng (COD)</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="bankTransfer" name="paymentMethod" value="bankTransfer">
                            <label for="bankTransfer">Chuyển khoản ngân hàng</label>
                        </div>
                        <div id="bankTransferDetails" class="bank-transfer-details">
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

                    <div class="button">
                        <button type="button" onclick="checkout()" class="cart-button">Giỏ hàng</button>
                        <button type="button" id="continuePaymentButton" class="continue-button">Hoàn tất thanh toán</button>
                    </div>
                </form>
            </div>

            <!-- Phần phải: Thông tin sản phẩm và tổng tiền -->
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
                <div class="order-note">
                    <p>Ghi chú đơn hàng:</p>
                    <textarea rows="4" placeholder="Nhập ghi chú cho đơn hàng của bạn"></textarea>
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

                if (this.value === 'homeDelivery') {
                    addressFields.style.display = 'block';
                    storeSelection.style.display = 'none';
                } else if (this.value === 'storePickup') {
                    addressFields.style.display = 'none';
                    storeSelection.style.display = 'block';
                }
            });
        });

        document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const bankTransferDetails = document.getElementById('bankTransferDetails');
                bankTransferDetails.style.display = this.value === 'bankTransfer' ? 'block' : 'none';
            });
        });

        document.getElementById('continuePaymentButton').addEventListener('click', function(event) {
            const form = document.querySelector('form');
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

            if (!paymentMethod) {
                alert('Vui lòng chọn phương thức thanh toán!');
                return;
            }

            if (form.checkValidity()) {
                alert('Đơn hàng đã được đặt: ' + paymentMethod.value);
                form.submit();
            } else {
                alert('Vui lòng điền đầy đủ thông tin!');
                form.reportValidity();
            }
        });

        function checkout() {
            window.location.href = "cart.php";
        }
    </script>
</body>
</html>