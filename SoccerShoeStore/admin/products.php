<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
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
        <button class="add-btn" onclick="showForm()">Thêm Sản phẩm</button>
            <div id="product-form" class="hidden">
                <h3>Nhập thông tin sản phẩm</h3>
                <form id="add-product-form" method="POST" action="XuLy_Products/add.php" enctype="multipart/form-data">
                    <label for="product-name">Tên Sản phẩm:</label>
                    <input type="text" id="product-name" name="product_name" required>

                    <label for="size">Size:</label>
                    <input type="text" id="size" name="size" required>

                    <label for="price">Giá:</label>
                    <input type="number" id="price" name="price" required>

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
                    <input type="number" id="quantity" name="quantity" required>

                    <label for="image">Hình ảnh:</label>
                    <input type="file" id="image" name="image" required>

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
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "soccershoestore");
                if ($conn->connect_error) {
                    die("Kết nối thất bại: " . $conn->connect_error);
                }
                $sql = "SELECT * FROM products_admin ORDER BY id DESC"; // Lấy sản phẩm mới nhất trước
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td><img src='uploads/" . $row['image'] . "' width='50'></td>";
                    echo "<td>" . number_format($row['price'], 0, ',', '.') . " VND</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['shoe_type'] . "</td>";
                    echo "<td>" . $row['size'] . "</td>"; 
                    echo "<td>" . $row['quantity'] . "</td>";  
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
</script>
</body>
</html>
