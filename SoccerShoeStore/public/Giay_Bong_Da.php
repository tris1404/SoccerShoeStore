<?php
// Kết nối CSDL từ file cấu hình
include '../config/database.php';

// Số sản phẩm mỗi trang
$productsPerPage = 12;

// Xác định trang hiện tại từ URL (mặc định là trang 1)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
$offset = ($page - 1) * $productsPerPage;

// Tính tổng số sản phẩm để xác định số trang
$sqlCount = "SELECT COUNT(*) as total FROM products";
$resultCount = mysqli_query($conn, $sqlCount);
$totalProducts = mysqli_fetch_assoc($resultCount)['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

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
        $brand = mysqli_real_escape_string($conn, $brand);
        $sql .= " AND brand = '$brand'";
    }

    // Nếu có lọc theo khoảng giá
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
        $sql .= " AND FIND_IN_SET('$size', size)";
    }

    // Cập nhật tổng số sản phẩm sau khi lọc
    $sqlCountFiltered = "SELECT COUNT(*) as total FROM products WHERE " . substr($sql, strpos($sql, "WHERE") + 6);
    $resultCountFiltered = mysqli_query($conn, $sqlCountFiltered);
    $totalProductsFiltered = mysqli_fetch_assoc($resultCountFiltered)['total'];
    $totalPagesFiltered = ceil($totalProductsFiltered / $productsPerPage);
    if ($page > $totalPagesFiltered && $totalPagesFiltered > 0) $page = $totalPagesFiltered;
    $offset = ($page - 1) * $productsPerPage;

    // Thêm giới hạn phân trang
    $sql .= " LIMIT $offset, $productsPerPage";

    // Chạy truy vấn
    $result = mysqli_query($conn, $sql);

    // Tạo HTML cho danh sách sản phẩm
    ob_start();
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
        echo '<p>KHÔNG TÌM THẤY SẢN PHẨM.</p>';
    endif;
    $productsHtml = ob_get_clean();

    // Tạo HTML cho phân trang
    ob_start();
    if ($totalPagesFiltered > 1): ?>
        <div class="pagination">
            <!-- Nút Previous -->
            <?php if ($page > 1): ?>
                <a href="#" data-page="<?= $page - 1 ?>"><</a>
            <?php else: ?>
                <span class="disabled"><</span>
            <?php endif; ?>

            <!-- Các nút số trang -->
            <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPagesFiltered, $page + 2);

                if ($endPage - $startPage + 1 < 5) {
                    if ($startPage == 1) {
                        $endPage = min($totalPagesFiltered, $startPage + 4);
                    } else {
                        $startPage = max(1, $endPage - 4);
                    }
                }

                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="#" data-page="<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <!-- Nút Next -->
            <?php if ($page < $totalPagesFiltered): ?>
                <a href="#" data-page="<?= $page + 1 ?>">></a>
            <?php else: ?>
                <span class="disabled">></span>
            <?php endif; ?>
        </div>
    <?php endif;
    $paginationHtml = ob_get_clean();

    // Trả về JSON chứa HTML của danh sách sản phẩm và phân trang
    header('Content-Type: application/json');
    echo json_encode([
        'products' => $productsHtml,
        'pagination' => $paginationHtml
    ]);
    exit();
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
        document.addEventListener('DOMContentLoaded', function () {
            const brandForm = document.getElementById('brandForm');
            const priceForm = document.getElementById('priceForm');
            const sizeForm = document.getElementById('sizeForm');

            function filterProducts(page = 1) {
                const brand = brandForm.querySelector('input[name="brand"]:checked')?.value || '';
                const price = priceForm.querySelector('input[name="price"]:checked')?.value || '';
                const size = sizeForm.querySelector('input[name="size"]:checked')?.value || '';

                const params = new URLSearchParams({
                    ajax: '1',
                    brand: brand,
                    price: price,
                    size: size,
                    page: page
                });

                fetch('Giay_Bong_Da.php?' + params.toString())
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.product-list').innerHTML = data.products;
                        document.querySelector('.pagination').outerHTML = data.pagination || '';
                        const newUrl = `${window.location.pathname}?page=${page}${brand ? '&brand=' + brand : ''}${price ? '&price=' + price : ''}${size ? '&size=' + size : ''}`;
                        history.pushState({}, '', newUrl);

                        document.querySelectorAll('.pagination a').forEach(link => {
                            link.addEventListener('click', function (e) {
                                e.preventDefault();
                                const page = this.getAttribute('data-page');
                                filterProducts(page);
                                document.querySelector('.product-list').scrollIntoView({ behavior: 'smooth' });
                            });
                        });
                    });
            }

            brandForm.addEventListener('change', () => filterProducts(1));
            priceForm.addEventListener('change', () => filterProducts(1));
            sizeForm.addEventListener('change', () => filterProducts(1));

            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    filterProducts(page);
                    document.querySelector('.product-list').scrollIntoView({ behavior: 'smooth' });
                });
            });

            const urlParams = new URLSearchParams(window.location.search);
            const brandFromUrl = urlParams.get('brand') || '';
            const priceFromUrl = urlParams.get('price') || '';
            const sizeFromUrl = urlParams.get('size') || '';
            const pageFromUrl = urlParams.get('page') || 1;

            if (brandFromUrl || priceFromUrl || sizeFromUrl || pageFromUrl != 1) {
                const params = new URLSearchParams({
                    ajax: '1',
                    brand: brandFromUrl,
                    price: priceFromUrl,
                    size: sizeFromUrl,
                    page: pageFromUrl
                });

                fetch('Giay_Bong_Da.php?' + params.toString())
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.product-list').innerHTML = data.products;
                        document.querySelector('.pagination').outerHTML = data.pagination || '';
                        document.querySelectorAll('.pagination a').forEach(link => {
                            link.addEventListener('click', function (e) {
                                e.preventDefault();
                                const page = this.getAttribute('data-page');
                                filterProducts(page);
                                document.querySelector('.product-list').scrollIntoView({ behavior: 'smooth' });
                            });
                        });
                    });
            }
        });
    </script>

    <div class="wrapper">
        <!-- HEADER -->
        <?php include 'includes/header.php'; ?>
    
        <div id="main">
            <div class="maincontent">
                <!-- Sidebar lọc sản phẩm -->
                <?php include 'includes/sidebar.php'; ?>
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
                            $sql = "SELECT * FROM products LIMIT $offset, $productsPerPage";
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

                    <!-- Phân trang -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <!-- Nút Previous -->
                            <?php if ($page > 1): ?>
                                <a href="#" data-page="<?= $page - 1 ?>"><</a>
                            <?php else: ?>
                                <span class="disabled"><</span>
                            <?php endif; ?>

                            <!-- Các nút số trang -->
                            <?php
                                $startPage = max(1, $page - 2);
                                $endPage = min($totalPages, $page + 2);

                                if ($endPage - $startPage + 1 < 5) {
                                    if ($startPage == 1) {
                                        $endPage = min($totalPages, $startPage + 4);
                                    } else {
                                        $startPage = max(1, $endPage - 4);
                                    }
                                }

                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="#" data-page="<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <!-- Nút Next -->
                            <?php if ($page < $totalPages): ?>
                                <a href="#" data-page="<?= $page + 1 ?>">></a>
                            <?php else: ?>
                                <span class="disabled">></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- FOOTER -->
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>