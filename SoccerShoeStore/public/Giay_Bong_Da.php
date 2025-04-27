<?php
// Kết nối CSDL từ file cấu hình
include '../config/database.php';

// Bật output buffering để ngăn lỗi header
ob_start();

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
    header('Content-Type: application/json');

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
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <?php if ($row['discount'] > 0): ?>
                        <span class="label-sale">-<?= $row['discount'] ?>%</span>
                    <?php endif; ?>
                    <div class="price-container">
                        <span class="price"><?= number_format($row['discount_price'] ?? $row['price']) ?>đ</span>
                        <?php if ($row['discount'] > 0): ?>
                            <span class="original-price"><?= number_format($row['price']) ?>đ</span>
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
    // Tạo HTML cho phân trang
    ob_start();
    if ($totalProductsFiltered > $productsPerPage):
        if ($totalPagesFiltered > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>"><</a>
                <?php else: ?>
                    <span class="disabled"><</span>
                <?php endif; ?>

                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPagesFiltered, $page + 2); // Cũng sửa thành totalPagesFiltered

                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPagesFiltered): ?>
                    <a href="?page=<?= $page + 1 ?>">></a>
                <?php else: ?>
                    <span class="disabled">></span>
                <?php endif; ?>
            </div>
        <?php endif;
    endif;
    $paginationHtml = ob_get_clean();

    // Trả về JSON chứa HTML của danh sách sản phẩm và phân trang
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

        // Hàm xử lý lọc sản phẩm
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Cập nhật danh sách sản phẩm
                    document.querySelector('.product-list').innerHTML = data.products;

                    // Xử lý phân trang
                    const paginationContainer = document.querySelector('.pagination');
                    if (paginationContainer) {
                        if (data.pagination.trim()) {
                            paginationContainer.outerHTML = data.pagination; // Nếu có phân trang mới
                        } else {
                            paginationContainer.remove(); // Nếu không còn phân trang => xóa luôn
                        }
                    } else {
                        if (data.pagination.trim()) {
                            document.querySelector('.product-list').insertAdjacentHTML('afterend', data.pagination); // Nếu cần thì thêm mới
                        }
                    }

                    // Gắn lại sự kiện click cho các liên kết phân trang
                    attachPaginationEvents();

                    // Cập nhật URL trên trình duyệt
                    const newUrl = `${window.location.pathname}?page=${page}${brand ? '&brand=' + brand : ''}${price ? '&price=' + price : ''}${size ? '&size=' + size : ''}`;
                    history.pushState({}, '', newUrl);
                })

                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        // Hàm gắn sự kiện click cho các liên kết phân trang
        function attachPaginationEvents() {
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault(); // Ngăn hành động mặc định của liên kết
                    const page = this.textContent.trim();
                    filterProducts(page);
                    document.querySelector('.product-list').scrollIntoView({ behavior: 'smooth' });
                });
            });
        }

        // Gắn sự kiện khi thay đổi bộ lọc
        brandForm.addEventListener('change', () => filterProducts(1));
        priceForm.addEventListener('change', () => filterProducts(1));
        sizeForm.addEventListener('change', () => filterProducts(1));

        // Gắn sự kiện click cho các liên kết phân trang ban đầu
        attachPaginationEvents();

        // Kiểm tra URL hiện tại để áp dụng bộ lọc từ URL
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Cập nhật danh sách sản phẩm
                    document.querySelector('.product-list').innerHTML = data.products;

                    // Cập nhật phân trang
                    if (data.pagination) {
                        document.querySelector('.pagination').outerHTML = data.pagination;
                    }

                    // Gắn lại sự kiện click cho các liên kết phân trang
                    attachPaginationEvents();
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    });
</script>
    <div class="wrapper">
        <!-- HEADER -->
        <?php include 'includes/header.php'; ?>

        <div id="main">
            <div class="maincontent">
                <!-- Sidebar -->
                <?php include 'includes/sidebar.php'; ?>

                <!-- Nội dung -->
                <div class="content">
                    <div class="product-intro">
                        <img src="../public/assets/img/banner/banner-chi-tiet-sp.webp" alt="Giày cỏ tự nhiên">
                    </div>

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

                    <div class="product-list">
                        <?php
                        $sql = "SELECT * FROM products LIMIT $offset, $productsPerPage";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <div class="mustbuy-item">
                                    <a href="product-detail.php?id=<?= $row['id'] ?>">
                                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                                        <?php if ($row['discount'] > 0): ?>
                                            <span class="label-sale">-<?= $row['discount'] ?>%</span>
                                        <?php endif; ?>
                                        <div class="price-container">
                                            <span class="price"><?= number_format($row['discount_price'] ?? $row['price']) ?>đ</span>
                                            <?php if ($row['discount'] > 0): ?>
                                                <span class="original-price"><?= number_format($row['price']) ?>đ</span>
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
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>"><</a>
                            <?php else: ?>
                                <span class="disabled"><</span>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);

                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?= $page + 1 ?>">></a>
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