<?php
session_start();
?>
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
    .wrapper-cart {
        background-color: white;
        margin: 20px 150px 0px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .shopping-cart {
        border-radius: 10px;
        background-color: white;
    }



    .top-bar {
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding: 20px;
    }


    .top-bar .title {
        flex-grow: 1;
        text-align: center;
        font-size: 30px;
    }

    .numbers-product {
        float: right;
    }

    .product-container {
        background-color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .product-section {
        width: 100%;
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin: 20px;
    }

    .product {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .product-items {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        border: 1px solid #ddd;
        border-width: 1px 0;
        /* Chỉ giữ viền trên & dưới, bỏ trái & phải */
        padding: 20px;
        text-align: left;
        background-color: #fff;
        gap: 25px;
    }

    .product-items img {
        width: 100px;
        height: auto;
        border-radius: 10px;
    }

    .product-info {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 100px;
        flex-grow: 1;
    }

    .product-items h3 {
        font-size: 18px;
        margin: 0;
    }

    .quantity-container {
        display: flex;
        align-items: center;
        gap: 15px;
        justify-content: center;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        font-size: 18px;
        border: none;
        background-color: #F0F0F0;
        color: black;
        cursor: pointer;
        border-radius: 5px;
    }

    .quantity-btn:hover {
        background-color: #E0E0E0;
    }

    .quantity-input {
        width: 40px;
        text-align: center;
        font-size: 18px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .price {
        font-size: 18px;
        font-weight: bold;
        color: black;
    }

    .function {
        display: flex;
    }

    .delete-btn {
        background-color: red;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    .delete-btn:hover {
        background-color: darkred;
    }

    .add-to-cart-btn {
        background-color: #28a745;
        /* Màu xanh lá cây */
        color: white;
        border: none;
        padding: 5px 15px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        margin-left: 10px;
        /* Cách nút xóa một chút */
    }

    .add-to-cart-btn:hover {
        background-color: #218838;
        /* Màu khi hover */
    }

    .bill {
        height: auto;
        background-color: #F8F8F8;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .bill h3 {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .bill-info {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .discount-input {
        width: 30%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }


    .checkout-btn {
        background-color: #E0E0E0;
        color: black;
        padding: 10px 15px;
        font-size: 18px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .checkout-btn:hover {
        background-color: black;
        color: white;
    }

    .cart-header {
        display: flex;
        gap: 190px;
        font-weight: bold;
        padding: 20px;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
    }

    .product-table th,
    .product-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .product-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .product-table img {
        width: 100px;
        height: auto;
        border-radius: 10px;
    }

    .favorite-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .favorite-table th,
    .favorite-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .favorite-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .favorite-table img {
        width: 100px;
        height: auto;
        border-radius: 10px;
    }

    .product-table td img, .favorite-table td img {
        display: inline-block;
        vertical-align: middle;
        margin: 0px 15px;
    }

    .product-table td h3, .favorite-table td h3 {
        display: inline-block;
        vertical-align: middle;
        margin: 0;
        text-align: left;
    }

    .product-table td:first-child, .favorite-table td:first-child {
        text-align: left;
    }

    .product-table td:not(:first-child), .favorite-table td:not(:first-child) {
        text-align: center;
    }
    
    
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
                            <div class="numbers-product">Tổng sản phẩm: 2</div>

                        </div>
                        <div class="product-container">
                            <div class="product-section">
                                <div class="section-title">Sản phẩm trong giỏ hàng</div>
                                <table class="product-table">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <img src="https://assets.adidas.com/images/w_766,h_766,f_auto,q_auto,fl_lossy,c_fill,g_auto/bbf580a0ef4e486ca861c49260565ade_9366/giay-da-bong-turf-predator-club.jpg" alt="">
                                                <h3>Adidas Turf Predator Club</h3>
                                                
                                            </td>
                                            <td>2.500.000</td>
                                            <td>
                                                <div class="quantity-container">
                                                    <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity1')">-</button>
                                                    <input type="text" id="quantity1" class="quantity-input" value="1" readonly>
                                                    <button class="quantity-btn" onclick="changeQuantity(1, 'quantity1')">+</button>
                                                </div>
                                            </td>
                                            <td>2.500.000</td>
                                            <td>
                                                <button class="delete-btn" onclick="deleteProduct(this)">Xóa</button>
                                            </td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>


                                    <tbody>
                                        <tr>
                                            <td>
                                                <img src="https://assets.adidas.com/images/w_766,h_766,f_auto,q_auto,fl_lossy,c_fill,g_auto/bbf580a0ef4e486ca861c49260565ade_9366/giay-da-bong-turf-predator-club.jpg" alt="">
                                                <h3>Adidas Turf Predator Club</h3>
                                            </td>
                                            <td>2.500.000</td>
                                            <td>
                                                <div class="quantity-container">
                                                    <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity1')">-</button>
                                                    <input type="text" id="quantity1" class="quantity-input" value="1" readonly>
                                                    <button class="quantity-btn" onclick="changeQuantity(1, 'quantity1')">+</button>
                                                </div>
                                            </td>
                                            <td>2.500.000</td>
                                            <td>
                                                <button class="delete-btn" onclick="deleteProduct(this)">Xóa</button>
                                            </td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: left; font-weight: bold;">Tổng tiền:</td>
                                            <td colspan="2" style="text-align: right; font-weight: bold; color:red" >5.000.000 VNĐ</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- <div class="product-section">
                                <div class="section-title">Sản phẩm yêu thích</div>
                                <div class="favorite-section">
                                    <table class="favorite-table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img src="https://assets.adidas.com/images/w_766,h_766,f_auto,q_auto,fl_lossy,c_fill,g_auto/bbf580a0ef4e486ca861c49260565ade_9366/giay-da-bong-turf-predator-club.jpg" alt="">
                                                    <h3>Adidas Turf Predator Club</h3>
                                                </td>
                                                <td>2.500.000</td>
                                                <td>
                                                    <div class="quantity-container">
                                                        <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity1')">-</button>
                                                        <input type="text" id="quantity1" class="quantity-input" value="1" readonly>
                                                        <button class="quantity-btn" onclick="changeQuantity(1, 'quantity1')">+</button>
                                                    </div>
                                                </td>
                                                <td>2.500.000</td>
                                                <td>
                                                    <button class="add-to-cart-btn" onclick="addToCart(this)">Thêm vào giỏ hàng</button>
                                                    <button class="delete-btn" onclick="deleteFavorite(this)">Xóa</button>
                                                </td>
                                            </tr>
                                             Add more rows as needed
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="bill">
                        <button class="checkout-btn" onclick="redirectToCheckout()">Thanh toán</button>
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
        function changeQuantity(amount, id) {
            let quantityInput = document.getElementById(id);
            let currentQuantity = parseInt(quantityInput.value);
            let newQuantity = currentQuantity + amount;
            if (newQuantity >= 1) {
                quantityInput.value = newQuantity;
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
</body>

</html>