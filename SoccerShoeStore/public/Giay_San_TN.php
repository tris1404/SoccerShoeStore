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

// Đảm bảo lọc sản phẩm theo Sân Tự Nhiên
$shoeType = 'Sân Tự Nhiên';
$where = "shoe_type = '$shoeType'";

// Tính tổng số sản phẩm để xác định số trang
$sqlCount = "SELECT COUNT(*) as total FROM products WHERE $where";
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
    $sql = "SELECT * FROM products WHERE $where";

    // Nếu người dùng chọn thương hiệu
    if (!empty($brand)) {
        $brand = mysqli_real_escape_string($conn, $brand);
        $sql .= " AND brand = '$brand'";
    }

    // Nếu lọc theo khoảng giá
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
        // Khi trang tải xong
        document.addEventListener('DOMContentLoaded', function () {
            const brandForm = document.getElementById('brandForm');
            const priceForm = document.getElementById('priceForm');
            const sizeForm = document.getElementById('sizeForm');

            // Hàm gửi request lọc sản phẩm
            function filterProducts(page = 1) {
                const brand = brandForm.querySelector('input[name="brand"]:checked')?.value || '';
                const price = priceForm.querySelector('input[name="price"]:checked')?.value || '';
                const size = sizeForm.querySelector('input[name="size"]:checked')?.value || '';

                // Tạo URL chứa thông tin lọc và trang
                const params = new URLSearchParams({
                    ajax: '1',
                    brand: brand,
                    price: price,
                    size: size,
                    page: page
                });

                // Gửi request và cập nhật danh sách sản phẩm + phân trang
                fetch(window.location.pathname + '?' + params.toString())
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.product-list').innerHTML = data.products;
                        document.querySelector('.pagination').outerHTML = data.pagination || '';
                        // Cập nhật URL mà không tải lại trang
                        const newUrl = `${window.location.pathname}?page=${page}${brand ? '&brand=' + brand : ''}${price ? '&price=' + price : ''}${size ? '&size=' + size : ''}`;
                        history.pushState({}, '', newUrl);

                        // Gắn lại sự kiện click cho các nút phân trang mới
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

            // Khi có thay đổi ở form lọc => gọi hàm filterProducts
            brandForm.addEventListener('change', () => filterProducts(1));
            priceForm.addEventListener('change', () => filterProducts(1));
            sizeForm.addEventListener('change', () => filterProducts(1));

            // Xử lý khi người dùng nhấn vào nút phân trang
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    filterProducts(page);
                    document.querySelector('.product-list').scrollIntoView({ behavior: 'smooth' });
                });
            });
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
                        <img src="https://pos.nvncdn.com/b0b717-26181/pc/20241111_xTNeUnPh.gif" alt="Giày cỏ tự nhiên">
                    </div>
    
                    <!-- Giới thiệu mô tả -->
                    <div class="product-description">
                        <h2>GIÀY SÂN CỎ TỰ NHIÊN</h2>
                        <p>
                            <strong>SOCCER SHOES STORE</strong> tự hào là địa chỉ cung cấp giày bóng đá chính hãng và phụ kiện bóng đá hàng đầu tại Việt Nam. 
                            Chúng tôi mang đến những sản phẩm chất lượng cao từ các thương hiệu nổi tiếng như <strong>NIKE, ADIDAS, PUMA, MIZUNO</strong> và nhiều thương hiệu khác, 
                            đáp ứng nhu cầu của mọi cầu thủ từ nghiệp dư đến chuyên nghiệp.<br><br>

                            <strong>Giày sân cỏ tự nhiên</strong> được thiết kế đặc biệt với đinh FG, AG, hoặc MG, phù hợp cho mặt sân cỏ tự nhiên 11 người. 
                            Những đôi giày này không chỉ mang lại độ bám tối ưu mà còn đảm bảo sự thoải mái và hỗ trợ tốt nhất cho bàn chân trong suốt trận đấu. 
                            Với công nghệ tiên tiến, giày sân cỏ tự nhiên giúp bạn phát huy tối đa tốc độ, sự linh hoạt và khả năng kiểm soát bóng.<br><br>

                            Hãy khám phá bộ sưu tập giày sân cỏ tự nhiên tại <strong>SOCCER SHOES STORE</strong> để tìm cho mình đôi giày hoàn hảo, 
                            đồng hành cùng bạn trên mọi sân cỏ!
                        </p>
                    </div>
    
                    <!-- Danh sách sản phẩm ban đầu (khi không lọc) -->
                    <div class="product-list">
                        <?php
                            $sql = "SELECT * FROM products WHERE shoe_type = 'Sân Tự Nhiên' LIMIT $offset, $productsPerPage";
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