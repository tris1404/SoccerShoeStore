<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Soccer Shoes Store</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: white;
    }

    .checkout-container {
        display: flex;
        justify-content: center;
        padding: 20px;
        margin-top: 50px;
    }

    .shipping-info,
    .order-summary {
        width: 45%;
        background-color: #ffffff;
        padding: 20px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .shipping-info h2,
    .order-summary h2 {
        margin-top: 0;
    }

    .shipping-info label,
    .address-fields label,
    .store-selection label {
        display: block;
        margin: 8px 0 4px;
    }

    .shipping-info input,
    .shipping-info select,
    .address-fields input,
    .store-selection select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .radio-option input {
        width: auto;
    }

    .button {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
    }

    .cart-button,
    .continue-button {
        width: 40%;
        padding: 10px;
        margin-top: 10px;
        background-color: #dedede4b;
        color: black;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }

    .cart-button:hover,
    .continue-button:hover {
        background-color: black;
        color: white;
    }

    .delivery-method {
        padding: 15px;
        margin-top: 10px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .radio-option label {
        margin: initial;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .address-fields,
    .store-selection {
        display: none;
        margin: 10px 0px;
        padding: 10px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .order-summary {
        background-color: #F8F8F8;
    }

    .order-summary ul {
        list-style: none;
        padding: 0;
    }

    .order-summary li {
        margin-bottom: 10px;
    }

    .order-summary p {
        font-size: 14px;
        font-weight: bold;
    }

    .product-header h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .product-details,
    .discount-container,
    .bill-info {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }

    .product-image img {
        max-width: 100px;
    }

    .product-info h2 {
        width: 400px;
        font-size: 15px;
        margin-bottom: 10px;
    }

    .product-info .price {
        font-size: 14px;
        font-weight: bold;
        color: #007bff;
    }

    .price-product {
        margin-block: auto;
        font-size: 14px;
    }

    .bill-info {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .discount-input {
        width: 80%;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .apply-btn {
        background-color: #dedede4b;
        color: black;
        padding: 8px 12px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .apply-btn:hover {
        background-color: black;
        color: white;
    }

    .bill-sum {
        font-size: 20px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        padding: 10px;

    }
</style>

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

                    <div class="button">
                        <button type="button" onclick="checkout()" class="cart-button">Giỏ hàng</button>
                        <button type="button" id="continuePaymentButton" class="continue-button">Tiếp tục thanh toán</button>
                    </div>
                </form>
            </div>

            <!-- Phần phải: Thông tin sản phẩm và tổng tiền -->
            <div class="order-summary">
                <div class="product-header">
                    <h1>Giày đá banh chính hãng</h1>
                </div>
                <div class="list-product">
                    <div class="product-details">
                        <div class="product-image">
                            <img src="https://www.sport9.vn/images/thumbs/002/0024851_nike-air-zoom-mercurial-vapor-16-pro-tf-xamhong-fq8687-301_1000.jpeg" alt="NIKE ZOOM MERCURIAL VAPOR 16 PRO TF">
                        </div>
                        <div class="product-info">
                            <h2>NIKE ZOOM MERCURIAL VAPOR 16 PRO TF - FQ8687-301 - XÁM XANH / HỒNG</h2>
                            <p>Size: 38.5</p>
                        </div>
                        <div class="price-product">
                            <p>2,850,000₫</p>
                        </div>
                    </div>

                    <div class="product-details">
                        <div class="product-image">
                            <img src="https://www.sport9.vn/images/thumbs/002/0024851_nike-air-zoom-mercurial-vapor-16-pro-tf-xamhong-fq8687-301_1000.jpeg" alt="NIKE ZOOM MERCURIAL VAPOR 16 PRO TF">
                        </div>
                        <div class="product-info">
                            <h2>NIKE ZOOM MERCURIAL VAPOR 16 PRO TF - FQ8687-301 - XÁM XANH / HỒNG</h2>
                            <p>Size: 38.5</p>
                        </div>
                        <div class="price-product">
                            <p>2,850,000₫</p>
                        </div>
                    </div>

                    
                <div class="discount-container">
                    <input type="text" class="discount-input" placeholder="Nhập mã giảm giá">
                    <button class="apply-btn">Áp dụng</button>
                </div>
                <div class="bill-info">
                    <span class="left">Tạm tính:</span>
                    <span class="right">2.850.000₫</span>
                </div>
                <div class="bill-info">
                    <span class="lefr">Phí vận chuyển:</span>
                    <span class="right">30.000₫</span>
                </div>

                <div class="bill-sum">
                    <span class="lefr">Tổng cộng:</span>
                    <span class="right">2.880.000₫</span>
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


        document.getElementById('continuePaymentButton').addEventListener('click', function(event) {
            const form = document.querySelector('form');

            if (form.checkValidity()) {
                alert('Tiến hành thanh toán!');
                form.submit(); // Nếu biểu mẫu hợp lệ, tiến hành gửi biểu mẫu
            } else {
                alert('Vui lòng điền đầy đủ thông tin!');
                form.reportValidity(); // Hiển thị cảnh báo của trình duyệt về các trường bị thiếu
            }
        });

        function checkout() {
            window.location.href = "cart.php"
        }
    </script>
</body>

</html>