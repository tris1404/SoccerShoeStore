<?php
// Kết nối CSDL từ file cấu hình
include '../config/database.php';

// Kiểm tra nếu có request AJAX (ajax=1 trên URL)
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // Lấy dữ liệu lọc từ URL (nếu có)
    $brand = $_GET['brand'] ?? '';
    $price = $_GET['price'] ?? '';
    $size = $_GET['size'] ?? '';

    // Tạo câu truy vấn cơ bản
    $sql = "SELECT * FROM products WHERE 1";

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

    // Nếu có lọc theo siz
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
                <a href="produc-detail.php?id=<?= $row['id'] ?>">
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
        document.addEventListener('DOMContentLoaded', function() {
            const brandForm = document.getElementById('brandForm'); // form chọn thương hiệu
            const priceForm = document.getElementById('priceForm'); // form chọn khoảng giá
            const sizeForm = document.getElementById('sizeForm'); // form chọn size

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
                fetch('Giay_Bong_Da.php?' + params.toString())
                    .then(response => response.text())
                    .then(html => {
                        // Thay nội dung phần sản phẩm bằng HTML mới
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
                        <h2>TẤT CẢ SẢN PHẨM</h2>
                        <p>
                            <strong>SOCCER SHOES STORE</strong> là hệ thống cung cấp giày bóng đá chính hãng và các phụ kiện bóng đá hàng đầu tại Việt Nam.
                            Với hơn 5 năm hình thành và phát triển, chúng tôi phục vụ hơn 20.000 khách hàng mỗi năm, trải dài khắp cả nước.
                            Chúng tôi là đại lý phân phối chính hãng các thương hiệu quốc tế <strong>NIKE, ADIDAS, PUMA, MIZUNO, DESPORTE, JOMA,...</strong>
                            và các thương hiệu Việt Nam <strong>NMS, ZOCKER, KAMITO…</strong><br><br>

                            <strong>Các sản phẩm chúng tôi phân phối bao gồm:</strong><br>
                            <strong>Giày cỏ tự nhiên:</strong> Là mẫu giày đinh FG, AG, MG dành cho mặt sân cỏ tự nhiên 11 người.
                            <strong>Giày cỏ nhân tạo:</strong> Là mẫu giày đinh TF dành cho mặt sân cỏ nhân tạo 5-7 người, đây là loại giày phổ biến nhất tại Việt Nam.
                            <strong>Giày Futsal:</strong> Là mẫu giày đế bằng IC dành cho mặt sân Futsal, sàn gỗ, sàn trong nhà.
                            <strong>Giày bóng đá trẻ em:</strong> Các mẫu giày bóng đá size nhỏ từ 28~38 dành cho trẻ em.
                            <strong>Giày bóng đá giá rẻ:</strong> Các mẫu giày bóng đá thương hiệu Việt Nam phân khúc giá dưới 1.000.000vnđ.
                            <strong>Giày bóng đá phiên bản giới hạn:</strong> Các mẫu giày bóng đá được sản xuất với số lượng giới hạn.<br><br>
                        </p>
                    </div>

                    <!-- Danh sách sản phẩm ban đầu (khi không lọc) -->
                    <div class="product-list">
                        <?php
                        $sql = "SELECT * FROM products"; // Lấy tất cả sản phẩm
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
                                            <button class="favorite-btn">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
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