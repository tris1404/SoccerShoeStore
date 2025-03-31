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
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <label for="product-name">Tên Sản phẩm:</label>
                    <input type="text" id="product-name" name="product_name" required>
                    
                    <label for="size">Size:</label>
                    <input type="text" id="size" name="size" placeholder="VD: 38, 39, 40..." required>
                    
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
                    <input type="file" id="image" name="image" accept="image/*" required>
                    
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
                    <th>Size</th>
                    <th>Số lượng</th>
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
                    <td>36, 37, 40</td>
                    <td>2</td>
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
                    <td>36, 37</td>
                    <td>5</td>
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
