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
        <button class="add-btn">Thêm Sản phẩm</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Danh mục</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Giày Adidas Ultraboost</td>
                    <td><img src="../assets/img/ultraboost.jpg" alt="Giày Adidas"></td>
                    <td>2,500,000 VND</td>
                    <td>Adidas</td>
                    <td>
                        <button class="edit-btn">Sửa</button>
                        <button class="delete-btn">Xóa</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Giày Nike Air Force 1</td>
                    <td><img src="../assets/img/airforce1.jpg" alt="Giày Nike"></td>
                    <td>3,000,000 VND</td>
                    <td>Nike</td>
                    <td>
                        <button class="edit-btn">Sửa</button>
                        <button class="delete-btn">Xóa</button>
                    </td>
                </tr>
            </tbody>
        </table>
        </main>
        <!-- end Nội dung chính -->
        <!-- Footer -->
        <?php include 'template/footer.php'; ?> 
        <!-- End Footer --> 
    </div>
</body>
</html>
