<?php
session_start();
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    echo "<script>alert('$message');</script>";
    unset($_SESSION['success']);
}
// Xóa session khi người dùng vào trang admin.php


// Kiểm tra nếu chưa đăng nhập hoặc không phải admin/staff
if (
    !isset($_SESSION['user']) || !isset($_SESSION['role']) ||
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')
) {

    // Chuyển về trang login
    header("Location: ../public/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="wrapper">
        <!-- menu -->
        <?php include 'template/sidebar.php'; ?>
        <!-- End menu -->
        <!-- header -->
        <?php include 'template/header.php'; ?>
        <!-- end header -->
        <!-- Nội dung chính -->
        <main class="main-content">
            <h2>Quản lý Sản phẩm</h2>

            <!-- Tìm kiếm và lọc -->
            <div class="filter-section">
                <input type="text" id="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select id="category-filter" name="category">
                    <option value="">Tất cả danh mục</option>
                    <option value="Adidas" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Adidas') ? 'selected' : ''; ?>>Adidas</option>
                    <option value="Nike" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Nike') ? 'selected' : ''; ?>>Nike</option>
                    <option value="Puma" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Puma') ? 'selected' : ''; ?>>Puma</option>
                </select>
                <select id="sort" name="sort">
                    <option value="id-desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'id-desc') ? 'selected' : ''; ?>>Mới nhất</option>
                    <option value="price-asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-asc') ? 'selected' : ''; ?>>Giá tăng dần</option>
                    <option value="price-desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price-desc') ? 'selected' : ''; ?>>Giá giảm dần</option>
                </select>
                <button onclick="applyFilter()">Lọc</button>
            </div>

            <button class="add-btn" onclick="showForm()">Thêm Sản phẩm</button>
            <div id="product-form" class="hidden">
                <h3>Nhập thông tin sản phẩm</h3>
                <form id="add-product-form" method="POST" action="XuLy_Products/add.php" enctype="multipart/form-data">
                    <label for="product-name">Tên Sản phẩm:</label>
                    <input type="text" id="product-name" name="product_name" required>

                    <label for="size">Size:</label>
                    <input type="text" id="size" name="size" required pattern="[0-9]+(,[0-9]+)*" title="Vui lòng nhập kích cỡ dạng số, ví dụ: 39,40">

                    <label for="price">Giá:</label>
                    <input type="number" id="price" name="price" required min="0">

                    <label for="category">Danh mục:</label>
                    <select id="category" name="category" required>
                        <option value="Adidas">Adidas</option>
                        <option value="Nike">Nike</option>
                        <option value="Puma">Puma</option>
                    </select>

                    <label for="shoe-type">Loại giày:</label>
                    <select id="shoe-type" name="shoe_type" required>
                        <option value="Sân tự nhiên">Giày sân tự nhiên</option>
                        <option value="Sân nhân tạo">Giày sân nhân tạo</option>
                        <option value="Futsal">Giày Futsal</option>
                    </select>

                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" required min="0">

                    <label for="discount">Giảm giá (%):</label>
                    <input type="number" id="discount" name="discount" min="0" max="100" value="0">

                    <label for="image">Link hình ảnh:</label>
                    <input type="text" id="image" name="image" placeholder="Nhập URL hình ảnh..." required>

                    <label for="product_type">Loại sản phẩm:</label>
                    <select id="product_type" name="product_type" required>
                        <option value="normal">Bình thường</option>
                        <option value="new">Mới</option>
                        <option value="sale">Giảm giá</option>
                        <option value="hot">Hot</option>
                    </select>

                    <button type="submit" class="submit-btn">Thêm</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Giá</th>
                        <th>Danh mục</th>
                        <th>Loại Giày</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Giảm giá (%)</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "soccershoestore");
                    if ($conn->connect_error) {
                        die("Kết nối thất bại: " . $conn->connect_error);
                    }

                    // Xây dựng câu truy vấn
                    $sql = "SELECT * FROM products_admin WHERE 1=1";
                    
                    $params = [];

                    // Tìm kiếm
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = $conn->real_escape_string($_GET['search']);
                        $sql .= " AND name LIKE '%$search%'";
                    }

                    // Lọc theo danh mục
                    if (isset($_GET['category']) && !empty($_GET['category'])) {
                        $category = $conn->real_escape_string($_GET['category']);
                        $sql .= " AND category = '$category'";
                    }

                    // Sắp xếp
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id-desc';
                    if ($sort == 'price-asc') {
                        $sql .= " ORDER BY price ASC";
                    } elseif ($sort == 'price-desc') {
                        $sql .= " ORDER BY price DESC";
                    } else {
                        $sql .= " ORDER BY id DESC";
                    }

                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td><img src='" . $row['image'] . "' width='50'></td>";
                        echo "<td>" . number_format($row['price'], 0, ',', '.') . " VND</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['shoe_type'] . "</td>";
                        echo "<td>" . $row['size'] . "</td>";
                        // Cảnh báo số lượng thấp
                        $quantity_class = $row['quantity'] <= 5 ? 'low-stock' : '';
                        echo "<td class='$quantity_class'>" . $row['quantity'] . "</td>";
                        echo "<td>" . $row['discount'] . "</td>";
                        $productTypeLabel = '';
                        if (isset($row['product_type'])) {
                            switch ($row['product_type']) {
                                case 'new':
                                    $productTypeLabel = 'NEW';
                                    break;
                                case 'sale':
                                    $productTypeLabel = 'SALE';
                                    break;
                                case 'hot':
                                    $productTypeLabel = 'HOT';
                                    break;
                                default:
                                    $productTypeLabel = 'NORMAL';
                                    break;
                            }
                        } else {
                            $productTypeLabel = 'Không xác định';
                        }
                        echo "<td>" . $productTypeLabel . "</td>";
                        // Thao tác sửa/xóa
                        echo "<td>
                                <a class='edit-btn' href='XuLy_Products/edit.php?id=" . $row['id'] . "'>Sửa</a>
                                <a class='delete-btn' href='XuLy_Products/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                            </td>";
                        echo "</tr>";
                    }
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </main>
        <!-- end Nội dung chính -->
        <!-- Footer -->
        <?php include 'template/footer.php'; ?>
        <!-- End Footer -->
    </div>
    <script>
        function showForm() {
            var form = document.getElementById('product-form');
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

        function applyFilter() {
            const search = document.getElementById('search').value;
            const category = document.getElementById('category-filter').value;
            const sort = document.getElementById('sort').value;
            let url = 'products.php?';
            let params = [];
            if (search) params.push('search=' + encodeURIComponent(search));
            if (category) params.push('category=' + encodeURIComponent(category));
            if (sort) params.push('sort=' + encodeURIComponent(sort));
            url += params.join('&');
            window.location.href = url;
        }

        // Tìm kiếm khi nhấn Enter
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilter();
            }
        });
    </script>
</body>

</html>