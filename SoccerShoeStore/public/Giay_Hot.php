<?php
// Kết nối CSDL từ file cấu hình
include '../config/database.php';

// Kiểm tra nếu có request AJAX (ajax=1 trên URL)
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {

        // Đảm bảo lọc sản phẩm hot
        $product_type = 'hot';
        $where = "product_type = '$product_type'";

        // Lấy dữ liệu lọc từ URL (nếu có)
        $brand = $_GET['brand'] ?? '';
        $price = $_GET['price'] ?? '';
        $size = $_GET['size'] ?? '';

        // Tạo câu truy vấn cơ bản
        $sql = "SELECT * FROM products WHERE $where";

        // Nếu người dùng chọn thương hiệu
        if (!empty($brand)) {
            $brand = mysqli_real_escape_string($conn, $brand); // Tránh SQL injection
            $sql .= " AND brand = '$brand'";
        }

        // Nếu có lọc theo khoảng giá (VD: 500000-1000000)
        if (!empty($price)) {
            list($minPrice, $maxPrice) = explode('-', $price);
            $sql .= " AND (
                (discount > 0 AND discount_price BETWEEN $minPrice AND $maxPrice)
                OR (discount = 0 AND price BETWEEN $minPrice AND $maxPrice)
            )";
        }

        // Nếu có lọc theo size
        if (!empty($size)) {
            $size = mysqli_real_escape_string($conn, $size);
            $sql .= " AND FIND_IN_SET('$size', size)"; // size lưu dạng chuỗi cách nhau bằng dấu phẩy
        }

        // Chạy truy vấn
        $result = mysqli_query($conn, $sql);

        // Nếu có sản phẩm phù hợp, in ra HTML của từng sản phẩm
        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="mustbuy-item">
                <a href="product-detail.php?id=<?= $row['id'] ?>">
                        <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                        <h3><?= $row['name'] ?></h3>
                        <?php if ($row['discount'] > 0): ?>
                            <span class="label-sale">-<?= $row['discount'] ?>%</span>
                        <?php endif; ?>
                        <?php if (!empty($row['tag'])): ?>
                            <span class="sale-tag"><?= htmlspecialchars($row['tag']) ?></span>
                        <?php endif; ?>
                            <!-- Hiển thị product_type (Hot, New, Sale) -->
                            <?php if (isset($row['product_type']) && $row['product_type'] !== 'Normal'): ?>
                                <span class="product-type type-<?= strtolower($row['product_type']) ?>">
                                    <?= htmlspecialchars($row['product_type']) ?>
                                </span>
                            <?php endif; ?>
                        <div class="price-container">
                            <?php if (
                                isset($row['discount_price']) &&
                                isset($row['price']) &&
                                $row['discount'] > 0 &&
                                $row['discount_price'] < $row['price']
                            ): ?>
                                <span class="price"><?= number_format($row['discount_price']) ?>đ</span>
                                <span class="original-price"><?= number_format($row['price']) ?>đ</span>
                            <?php elseif (isset($row['price'])): ?>
                                <span class="price"><?= number_format($row['price']) ?>đ</span>
                            <?php else: ?>
                                <span class="price">Liên hệ</span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endwhile;
        else:
            echo '<p>KHÔNG TÌM THẤY SẢN PHẨM.</p>';
        endif;
    exit(); // Dừng code ở đây nếu là request ajax
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shop Giày Bóng Đá</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/css/Giay_Bong_Da.css">
    <link rel="stylesheet" href="../public/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
</head>
<body>
    <script>
        // Khi trang tải xong
        document.addEventListener('DOMContentLoaded', function () {
            const brandForm = document.getElementById('brandForm'); // form chọn thương hiệu
            const priceForm = document.getElementById('priceForm'); // form chọn khoảng giá
            const sizeForm = document.getElementById('sizeForm');   // form chọn size

            // Hàm gửi request lọc sản phẩm
            function filterProducts() {
                const brand = brandForm.querySelector('input[name="brand"]:checked')?.value || '';
                const price = priceForm.querySelector('input[name="price"]:checked')?.value || '';
                const size = sizeForm.querySelector('input[name="size"]:checked')?.value || '';

                // Tạo URL chứa thông tin lọc
                const params = new URLSearchParams({
                    ajax: '1',
                    brand: brand,
                    price: price,
                    size: size
                });

                // Gửi request đến chính file Giay_Bong_Da.php, lấy HTML sản phẩm về
                fetch(window.location.pathname + '?' + params.toString())
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('.product-list').innerHTML = html;
                    });
            }

            // Khi có thay đổi ở form lọc => gọi hàm filterProducts
            brandForm.addEventListener('change', filterProducts);
            priceForm.addEventListener('change', filterProducts);
            sizeForm.addEventListener('change', filterProducts);
        });
    </script>

    <div class="wrapper">
        <!-- HEADER -->
        <?php include 'includes/header.php'; ?>
        <div id="main">

            <div class="maincontent">
            <!-- Sidebar lọc sản phẩm -->
            <?php include 'includes/sidebar.php'; ?> <!-- Sidebar lọc sản phẩm -->
                <!-- Banner giới thiệu -->
                <div class="content">
                    <div class="product-intro">
                    <img src="../public/assets/img/banner/banner-chi-tiet-sp.webp" alt="Giày cỏ tự nhiên">
                    </div>
    
                    <!-- Giới thiệu mô tả -->
                    <div class="product-description">
                        <h2>GIÀY HOT - SỰ LỰA CHỌN HÀNG ĐẦU CỦA CÁC TÍN ĐỒ BÓNG ĐÁ</h2>
                        <p>
                            <strong>SOCCER SHOES STORE</strong> tự hào giới thiệu bộ sưu tập <strong>giày HOT</strong>, những sản phẩm được yêu thích nhất bởi các cầu thủ và người hâm mộ bóng đá. 
                            Với thiết kế đột phá, chất liệu cao cấp và công nghệ tiên tiến, những đôi giày này không chỉ mang lại hiệu suất vượt trội mà còn thể hiện phong cách và cá tính của bạn trên sân cỏ.<br><br>

                            Bộ sưu tập giày HOT tại <strong>SOCCER SHOES STORE</strong> bao gồm các sản phẩm từ những thương hiệu hàng đầu thế giới như <strong>NIKE, ADIDAS, PUMA, MIZUNO</strong>. 
                            Chúng tôi mang đến sự đa dạng về kiểu dáng, màu sắc và công nghệ, đáp ứng mọi nhu cầu của người chơi, từ nghiệp dư đến chuyên nghiệp.<br><br>

                            Hãy khám phá ngay bộ sưu tập giày HOT tại <strong>SOCCER SHOES STORE</strong> để sở hữu những đôi giày đẳng cấp, giúp bạn tự tin tỏa sáng trên sân cỏ!
                        </p>
                    </div>
    
                    <!-- Danh sách sản phẩm ban đầu (khi không lọc) -->
                    <div class="product-list">
                        <?php
                            $sql = "SELECT * FROM products WHERE product_type = 'hot'"; // Lấy tất cả sản phẩm hot 
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)): ?>
                                    <div class="mustbuy-item">
                                        <a href="product-detail.php?id=<?= $row['id'] ?>">
                                            <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                                            <h3><?= $row['name'] ?></h3>
                                            <?php if ($row['discount'] > 0): ?>
                                                <span class="label-sale">-<?= $row['discount'] ?>%</span>
                                            <?php endif; ?>
                                            <?php if (!empty($row['tag'])): ?>
                                                <span class="sale-tag"><?= htmlspecialchars($row['tag']) ?></span>
                                            <?php endif; ?>
                                            <!-- Hiển thị product_type (Hot, New, Sale) -->
                                            <?php if (isset($row['product_type']) && $row['product_type'] !== 'Normal'): ?>
                                                <span class="product-type type-<?= strtolower($row['product_type']) ?>">
                                                    <?= htmlspecialchars($row['product_type']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <div class="price-container">
                                                <?php if (
                                                    isset($row['discount_price']) &&
                                                    isset($row['price']) &&
                                                    $row['discount'] > 0 &&
                                                    $row['discount_price'] < $row['price']
                                                ): ?>
                                                    <span class="price"><?= number_format($row['discount_price']) ?>đ</span>
                                                    <span class="original-price"><?= number_format($row['price']) ?>đ</span>
                                                <?php elseif (isset($row['price'])): ?>
                                                    <span class="price"><?= number_format($row['price']) ?>đ</span>
                                                <?php else: ?>
                                                    <span class="price">Liên hệ</span>
                                                <?php endif; ?>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile;
                            else:
                                echo '<p>KHÔNG CÓ SẢN PHẨM NÀO.</p>';
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FOOTER -->
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>

