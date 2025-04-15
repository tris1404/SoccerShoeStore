<?php
ob_start(); // Bắt đầu output buffering

$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$product = null;

// LẤY THÔNG TIN SẢN PHẨM TRƯỚC KHI VÀO HTML
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM products_admin WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<h3>Không tìm thấy sản phẩm!</h3>";
        exit();
    }
} else {
    echo "<h3>Thiếu ID sản phẩm!</h3>";
    exit();
}

// Lấy thông tin discount từ bảng products_admin (sau khi đã thêm cột discount)
$discount = isset($product['discount']) ? $product['discount'] : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/products.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            margin: 20px;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        form {
            width: 100%;
            max-width: 700px;
            background-color: rgb(245, 236, 213);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: auto auto;
            gap: 15px 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group:nth-child(odd) {
            grid-column: 1;
        }

        .form-group:nth-child(even) {
            grid-column: 2;
        }

        .form-group:nth-child(7), /* Số lượng */
        .form-group:nth-child(8), /* Giảm giá */
        .form-group:nth-child(9) /* Hình ảnh */ {
            grid-column: 1 / span 2;
        }

        /* Đảm bảo tiêu đề "Sửa sản phẩm" chiếm cả hai cột */
        .form-group.form-title {
            grid-column: 1 / span 2;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-group.form-title h2 {
            color: rgb(39, 83, 150);
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: #495057;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 0;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgb(39, 83, 150);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-control::placeholder,
        .form-select::placeholder {
            color: #6c757d;
            font-size: 14px;
        }

        button[type="submit"] {
            grid-column: 1 / span 2;
            padding: 10px 15px;
            border-radius: 4px;
            border: none;
            background-color: rgb(39, 83, 150);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: rgb(25, 94, 198);
        }

        .container {
            padding: 0;
        }

        /* Style cho input file */
        input[type="file"] {
            padding: 6px 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            margin-bottom: 0;
            box-sizing: border-box;
        }

        input[type="file"]::file-selector-button {
            background-color: #e9ecef;
            color: #495057;
            border: none;
            border-right: 1px solid #ced4da;
            padding: 6px 10px;
            margin-right: 10px;
            border-radius: 4px 0 0 4px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #dee2e6;
        }
    </style>
</head>
<body>
    <form method="POST" action="edit.php?id=<?= $product['id'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <!-- Tiêu đề "Sửa sản phẩm" được đưa vào bên trong form -->
        <div class="form-group form-title">
            <h2>Sửa sản phẩm</h2>
        </div>

        <div class="form-group">
            <label class="form-label">Tên sản phẩm:</label>
            <input type="text" class="form-control" name="product_name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Size:</label>
            <input type="text" class="form-control" name="size" value="<?= htmlspecialchars($product['size']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Giá:</label>
            <input type="number" class="form-control" name="price" value="<?= $product['price'] ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Danh mục:</label>
            <select class="form-select" name="category" required>
                <option value="Adidas" <?= $product['category'] == 'Adidas' ? 'selected' : '' ?>>Adidas</option>
                <option value="Nike" <?= $product['category'] == 'Nike' ? 'selected' : '' ?>>Nike</option>
                <option value="Puma" <?= $product['category'] == 'Puma' ? 'selected' : '' ?>>Puma</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Loại giày:</label>
            <select class="form-select" name="shoe_type" required>
                <option value="Sân tự nhiên" <?= $product['shoe_type'] == 'Sân tự nhiên' ? 'selected' : '' ?>>Giày sân tự nhiên</option>
                <option value="Sân nhân tạo" <?= $product['shoe_type'] == 'Sân nhân tạo' ? 'selected' : '' ?>>Giày sân nhân tạo</option>
                <option value="Futsal" <?= $product['shoe_type'] == 'Futsal' ? 'selected' : '' ?>>Giày Futsal</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Số lượng:</label>
            <input type="number" class="form-control" name="quantity" value="<?= $product['quantity'] ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Giảm giá (%):</label>
            <input type="number" class="form-control" name="discount" min="0" max="100" value="<?= $discount ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Hình ảnh mới (URL hoặc tải lên):</label>
            <input type="text" class="form-control" name="image_url" placeholder="Nhập URL hình ảnh mới...">
            <p>Hình ảnh hiện tại: <img src="<?= htmlspecialchars($product['image']) ?>" width="50"></p>
        </div>

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>

<?php
// Xử lý cập nhật khi submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['product_name']);
    $size = $conn->real_escape_string($_POST['size']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $shoe_type = $conn->real_escape_string($_POST['shoe_type']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $discount = isset($_POST['discount']) ? $conn->real_escape_string($_POST['discount']) : 0;

    // Kiểm tra nếu có URL hình ảnh mới
    if (!empty($_POST['image_url'])) {
        $image_name = $conn->real_escape_string($_POST['image_url']);
    } elseif (!empty($_FILES['image_file']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = basename($_FILES["image_file"]["name"]);
        $target_file = $target_dir . $image_name;
        if (!move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            echo "Lỗi khi tải ảnh lên.";
            exit();
        }
    } else {
        $image_name = $product['image']; // Giữ nguyên hình ảnh cũ nếu không upload hình mới
    }

    // Cập nhật bảng products_admin
    $sql_admin = "UPDATE products_admin SET 
                  name='$name', size='$size', price='$price', 
                  category='$category', shoe_type='$shoe_type',
                  quantity='$quantity', image='$image_name', discount='$discount' 
                  WHERE id = $id";

    // Cập nhật bảng products (cho khách hàng)
    $sql_customer = "UPDATE products SET 
                     name='$name', size='$size', price='$price', 
                     brand='$category', shoe_type='$shoe_type',
                     quantity='$quantity', image='$image_name', discount='$discount' 
                     WHERE id = $id";

    if (mysqli_query($conn, $sql_admin) && mysqli_query($conn, $sql_customer)) {
        echo "<script>alert('Cập nhật thành công!');</script>";
        header("Location: ../products.php");
        exit();
    } else {
        echo "Lỗi cập nhật: " . mysqli_error($conn);
    }
}

$conn->close();
ob_end_flush(); // Gửi output sau khi đã xử lý
?>