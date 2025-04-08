<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giày Cỏ Tự Nhiên</title>
    <link rel="stylesheet" href="assets/css/giay_san_tu_nhien.css">
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/scripts.js?v=1"></script>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- End header -->
    <div class="container">
        <aside class="sidebar">
            <h3>Tìm Theo</h3>
            <table class="filter-table">
                <tr class="brand-toggle">
                    <th>Thương Hiệu <i class="fas fa-chevron-down"></i></th>
                </tr>
                <tr class="brand-options" style="display: none;">
                    <td>
                        <form method="GET" action="">
                            <?php
                            $brands = ["Nike", "Adidas", "Puma", "Mizuno"];
                            foreach ($brands as $brand) {
                                $checked = (isset($_GET['brand']) && in_array($brand, $_GET['brand'])) ? "checked" : "";
                                echo "<label><input type='checkbox' name='brand[]' value='$brand' $checked onchange='this.form.submit()'> $brand</label><br>";
                            }
                            ?>
                        </form>
                    </td>
                </tr>
                <tr class="price-toggle">
                    <th>Khoảng Giá <i class="fas fa-chevron-down"></i></th>
                </tr>
                <tr class="price-options" style="display: none;">
                    <td>
                        <form method="GET" action="">
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
                    </td>
                </tr>
            </table>
        </aside>

        <main class="main-content">
            <!-- Banner -->
            <div class="banner">
                <img src="assets/img/San_TuNhien/adidas_banner.webp" alt="Banner Giày Cỏ Tự Nhiên">
            </div>

            <div class="description">
                <h2>Giày Cỏ Tự Nhiên</h2>
                <p>Giày đá bóng sân cỏ tự nhiên là loại giày có thiết kế đinh đặc biệt (FG, AG-PRO, MG) để hỗ trợ chơi
                    bóng trên sân cỏ thật 11 người.

                    Đến với SoccerShoeStore bạn có thể dễ dàng trải nghiệm những mẫu giày cỏ tự nhiên mới nhất và được
                    săn đón nhiều nhất từ các thương hiệu hàng đầu trong và ngoài nước hiện nay như Nike, Adidas, Puma,
                    Mizuno.</p>
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
                $conn = new mysqli("localhost", "root", "", "soccershoestore");
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }

                // Chỉ lấy sản phẩm có shoe_type là "Sân tự nhiên"
                $sql = "SELECT * FROM products WHERE shoe_type = 'Sân tự nhiên'";

                // Lọc theo thương hiệu
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

                // Sắp xếp mặc định theo sản phẩm mới nhất
                $sql .= " ORDER BY id DESC";

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='product' data-price='{$row['price']}'>";
                        if ($row['discount'] > 0) {
                            echo "<span class='discount'>-{$row['discount']}%</span>";
                        }
                        // Kiểm tra đường dẫn hình ảnh
                        $image_path = '../admin/uploads/' . $row['image'];
                        if (file_exists($image_path)) {
                            echo "<img src='$image_path' alt='{$row['name']}'>";
                        } else {
                            echo "<p>Hình ảnh không tồn tại: $image_path</p>";
                        }
                        echo "<p>{$row['name']}</p>";
                        echo "<span class='price'>" . number_format($row['price'], 0, ',', '.') . "đ</span>";

                        // Thêm biểu tượng "Xem nhanh" & "Thêm vào giỏ hàng"
                        echo "<div class='product-icons'>";
                        echo "<a href='javascript:void(0);' onclick='openPopup({$row['id']})' title='Xem nhanh'><i class='fas fa-eye'></i></a>";
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

    <!-- Popup sản phẩm -->
    <div id="productPopup" class="cart-popup" style="display: none;">
        <div id="popupDetails">
            <!-- Nội dung chi tiết sản phẩm sẽ được tải bằng AJAX -->
        </div>
    </div>

    <button id="scrollToTopBtn" onclick="scrollToTop()">
        ▲
    </button>
    <button id="zaloChat" onclick="window.open('https://zalo.me/09xxxxxxxx', '_blank')">
        <img src="https://stc-zaloprofile.zdn.vn/pc/v1/images/zalo_sharelogo.png" alt="Chat Zalo">
    </button>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    <!-- End footer -->

    <script>
    function openPopup(productId) {
        fetch("get_product_details.php?id=" + productId)
            .then(response => response.text())
            .then(data => {
                document.getElementById("popupDetails").innerHTML = data;
                document.getElementById("productPopup").style.display = "flex";
            })
            .catch(error => {
                console.error("Lỗi khi lấy chi tiết sản phẩm:", error);
                document.getElementById("popupDetails").innerHTML = "<p>Đã xảy ra lỗi khi tải chi tiết sản phẩm.</p>";
            });
    }

    function closePopup() {
        document.getElementById("productPopup").style.display = "none";
    }

    function addToCart(productId) {
        const quantity = document.getElementById(`popup-quantity-${productId}`).value;
        const size = document.getElementById(`popup-size-${productId}`).value;
        alert(`Đã thêm sản phẩm ID ${productId} vào giỏ hàng với số lượng ${quantity} và kích cỡ ${size}`);
        closePopup();
    }

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

    // Nút cuộn lên đầu trang
    window.onscroll = function() {
        let button = document.getElementById("scrollToTopBtn");
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            button.style.display = "block";
        } else {
            button.style.display = "none";
        }
    };

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }

    $(document).ready(function() {
        $(".brand-toggle").click(function() {
            $(this).next(".brand-options").stop(true, true).slideToggle();
            $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
        });

        $(".price-toggle").click(function() {
            $(this).next(".price-options").stop(true, true).slideToggle();
            $(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
        });
    });
    </script>
</body>
</html>