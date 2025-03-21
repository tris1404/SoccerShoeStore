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

                if (!empty($_GET['brand'])) {
                    $brands = array_map([$conn, 'real_escape_string'], $_GET['brand']);
                    $brands_placeholder = "'" . implode("','", $brands) . "'";
                    $sql .= " AND brand IN ($brands_placeholder)";
                }

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product' data-price='{$row['price']}'>";
                        echo "<span class='discount'>-{$row['discount']}%</span>";
                        echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                        echo "<p>{$row['name']}</p>";
                        echo "<span class='price'>" . number_format($row['price'], 0, ',', '.') . "đ</span>";
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
    </script>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <!-- End footer -->
</body>
</html>
