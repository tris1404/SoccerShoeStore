<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giày Cỏ Tự Nhiên</title>
    <link rel="stylesheet" href="assets/css/giay_san_tu_nhien.css">
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    <!-- End header -->

    <div class="container">
        <aside class="sidebar">
            <h3>Tìm Theo</h3>
            <form method="GET" action="">
                <h4>Thương Hiệu</h4>
                <?php
                $brands = ["Nike", "Adidas", "Puma", "Mizuno"];
                foreach ($brands as $brand) {
                    $checked = (isset($_GET['brand']) && in_array($brand, $_GET['brand'])) ? "checked" : "";
                    echo "<label><input type='checkbox' name='brand[]' value='$brand' $checked onchange='this.form.submit()'> $brand</label><br>";
                }
                ?>
                <h4>Khoảng Giá</h4>
                <?php
                $priceRanges = [
                    "1000000-2000000" => "1.000.000 - 2.000.000",
                    "2000000-3000000" => "2.000.000 - 3.000.000",
                    "3000000-4000000" => "3.000.000 - 4.000.000",
                    "4000000-5000000" => "4.000.000 - 5.000.000"
                ];
                foreach ($priceRanges as $range => $label) {
                    $checked = (isset($_GET['price']) && in_array($range, $_GET['price'])) ? "checked" : "";
                    echo "<label><input type='checkbox' name='price[]' value='$range' $checked onchange='this.form.submit()'> $label</label><br>";
                }
                ?>
            </form>
        </aside>

        <main class="main-content">
            <!-- Banner -->
            <div class="banner">
                <img src="assets/img/San_TuNhien/adidas_banner.webp" alt="Banner Giày Cỏ Tự Nhiên">
            </div>

            <div class="description">
                <h2>Giày Cỏ Tự Nhiên</h2>
                <p>Giày đá bóng sân cỏ tự nhiên là loại giày có thiết kế đinh đặc biệt (FG, AG-PRO, MG) để hỗ trợ chơi bóng trên sân cỏ thật 11 người.

Đến với SoccerShoeStore bạn có thể dễ dàng trải nghiệm những mẫu giày cỏ tự nhiên mới nhất và được săn đón nhiều nhất từ các thương hiệu hàng đầu trong và ngoài nước hiện nay như Nike, Adidas, Puma, Mizuno.</p>
            </div>

            <div class="sort-filter">
                <label for="sort">Sắp xếp theo:</label>
                <select id="sort" onchange="sortProducts()">
                    <option value="new">Mới nhất</option>
                    <option value="asc">Giá tăng dần</option>
                    <option value="desc">Giá giảm dần</option>
                </select>
            </div>

            <div class="product-list" id="product-list">
                <?php
                $conn = new mysqli("localhost", "root", "", "shoe_store");
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM products WHERE 1";


                // lọc theo thương hiệu
                if (!empty($_GET['brand'])) {
                    $brands = array_map([$conn, 'real_escape_string'], $_GET['brand']);
                    $brands_placeholder = "'" . implode("','", $brands) . "'";
                    $sql .= " AND brand IN ($brands_placeholder)";
                }
                // Lọc theo khoảng giá
                if (!empty($_GET['price'])) {
                    $priceConditions = [];
                    foreach ($_GET['price'] as $range) {
                        list($min, $max) = explode("-", $range);
                        $priceConditions[] = "(price BETWEEN $min AND $max)";
                    }
                    $sql .= " AND (" . implode(" OR ", $priceConditions) . ")";
                }    
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product' data-price='{$row['price']}'>";
                        echo "<span class='discount'>-{$row['discount']}%</span>";
                        echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                        echo "<p>{$row['name']}</p>";
                        echo "<span class='price'>" . number_format($row['price'], 0, ',', '.') . "đ</span>";
                    
                        // Thêm biểu tượng "Xem nhanh" & "Thêm vào giỏ hàng"
                        echo "<div class='product-icons'>";
                        echo "<a href='#' title='Xem nhanh'><i class='fas fa-eye'></i></a>";
                        echo "<a href='javascript:void(0);' onclick='openPopup({$row['id']})' title='Thêm vào giỏ hàng'>";
                        echo "<i class='fas fa-shopping-cart'></i>";
                        echo "</a>";
                        echo "</div>";
                    
                        echo "</div>";
                    }
                    
                } else {
                    echo "<p>Không tìm thấy sản phẩm nào!</p>";
                }

                $conn->close();
                ?>
            </div>
        </main>
    </div>

    <script>
        function sortProducts() {
            let sortType = document.getElementById("sort").value;
            let productList = document.getElementById("product-list");
            let products = Array.from(productList.getElementsByClassName("product"));
            
            products.sort((a, b) => {
                let priceA = parseInt(a.getAttribute("data-price"));
                let priceB = parseInt(b.getAttribute("data-price"));
                if (sortType === "asc") return priceA - priceB;
                if (sortType === "desc") return priceB - priceA;
                return 0;
            });
            
            productList.innerHTML = "";
            products.forEach(product => productList.appendChild(product));
        }

        //nút cuộn lên đầu trang
        window.onscroll = function () {
            let button = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                button.style.display = "block"; // Hiện nút khi cuộn xuống
            } else {
                button.style.display = "none"; // Ẩn nút khi ở đầu trang
            }
        };

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
        
    </script>

    <button id="scrollToTopBtn" onclick="scrollToTop()">
        &#x25B2;
    </button>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <!-- End footer -->

<!-- Popup sản phẩm -->
<div id="productPopup" class="popup-container" style="display: none;">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <div id="popupDetails">
            <!-- Nội dung chi tiết sản phẩm sẽ được tải bằng AJAX -->
        </div>
    </div>
</div>

<style>
    /* Popup container */
    .popup-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Nội dung popup */
    .popup-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        max-width: 500px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        position: relative;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Hiệu ứng xuất hiện */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Nút đóng */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        color: red;
        font-weight: bold;
    }

    /* Hình ảnh sản phẩm */
    .popup-content img {
        width: 100%;
        border-radius: 5px;
    }

    /* Thông tin sản phẩm */
    .popup-content h2 {
        font-size: 20px;
        margin-top: 10px;
    }

    /* Giá */
    .popup-content p strong {
        color: red;
        font-size: 18px;
    }

    /* Nút thêm vào giỏ hàng */
    .popup-content button {
        width: 100%;
        background: #28a745;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .popup-content button:hover {
        background: #218838;
    }

</style>

<script>
    function openPopup(productId) {
        fetch("get_product_details.php?id=" + productId)
            .then(response => response.text())
            .then(data => {
                document.getElementById("popupDetails").innerHTML = data;
                document.getElementById("productPopup").style.display = "flex";
            });
    }

    function closePopup() {
        document.getElementById("productPopup").style.display = "none";
    }
</script>


</body>
</html>
