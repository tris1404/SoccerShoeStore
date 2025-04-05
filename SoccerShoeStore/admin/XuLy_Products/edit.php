<?php
ob_start(); // Bắt đầu output buffering

$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$product = null;

// LẤY THÔNG TIN SẢN PHẨM TRƯỚC KHI VÀO HTML
if (isset($_GET['id'])) {
    $id = $_GET['id'];
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

        h2 {
            text-align: left; /* Căn trái tiêu đề */
            color:rgb(39, 83, 150);
            margin-bottom: 20px; /* Giảm margin bottom */
            font-size: 24px; /* Nhỏ hơn */
            font-weight: bold;
        }

        form {
            width: 100%;
            max-width: 700px; /* Điều chỉnh chiều rộng form */
            background-color:rgb(245, 236, 213);
            padding: 20px; /* Giảm padding */
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); /* Bóng đổ nhẹ hơn */
            display: grid; /* Sử dụng grid layout */
            grid-template-columns: auto auto; /* Hai cột bằng nhau */
            gap: 15px 20px; /* Khoảng cách giữa các item */
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group:nth-child(odd) {
            grid-column: 1; /* Các label/input ở cột lẻ */
        }

        .form-group:nth-child(even) {
            grid-column: 2; /* Các label/input ở cột chẵn */
        }

        .form-group:nth-child(7), /* Số lượng */
        .form-group:nth-child(8) /* Hình ảnh */ {
            grid-column: 1 / span 2; /* Chiếm cả hai cột */
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px; /* Giảm margin bottom */
            display: block;
            color: #495057;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            width: calc(100% - 12px); /* Tính toán để trừ border */
            padding: 8px; /* Giảm padding */
            margin-bottom: 0; /* Loại bỏ margin bottom ở đây */
            border: 1px solid #ced4da;
            border-radius: 4px; /* Giảm bo tròn */
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
            grid-column: 1 / span 2; /* Chiếm cả hai cột */
            padding: 10px 15px;
            border-radius: 4px; /* Giảm bo tròn */
            border: none;
            background-color: rgb(39, 83, 150);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            margin-top: 20px; /* Thêm margin top cho nút */
        }

        button[type="submit"]:hover {
            background-color:rgb(25, 94, 198);
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
    <h2>Sửa sản phẩm</h2>
    <form method="POST" action="edit.php?id=<?= $product['id'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <label>Tên sản phẩm:</label>
        <input type="text" name="product_name" value="<?= $product['name'] ?>" required>

        <label>Size:</label>
        <input type="text" name="size" value="<?= $product['size'] ?>" required>

        <label>Giá:</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" required>

        <label>Danh mục:</label>
        <select name="category">
            <option value="Adidas" <?= $product['category'] == 'Adidas' ? 'selected' : '' ?>>Adidas</option>
            <option value="Nike" <?= $product['category'] == 'Nike' ? 'selected' : '' ?>>Nike</option>
            <option value="Puma" <?= $product['category'] == 'Puma' ? 'selected' : '' ?>>Puma</option>
        </select>

        <label>Loại giày:</label>
        <select name="shoe_type">
            <option value="Sân tự nhiên" <?= $product['shoe_type'] == 'Sân tự nhiên' ? 'selected' : '' ?>>Giày sân tự nhiên</option>
            <option value="Sân nhân tạo" <?= $product['shoe_type'] == 'Sân nhân tạo' ? 'selected' : '' ?>>Giày sân nhân tạo</option>
            <option value="Futsal" <?= $product['shoe_type'] == 'Futsal' ? 'selected' : '' ?>>Giày Futsal</option>
        </select>

        <label>Số lượng:</label>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>

        <label>Hình ảnh mới (nếu muốn đổi):</label>
        <input type="file" name="image">

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>

<?php
// Xử lý cập nhật khi submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['product_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $shoe_type = $_POST['shoe_type'];
    $quantity = $_POST['quantity'];

    // Kiểm tra nếu có ảnh mới
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $sql = "UPDATE products_admin SET 
                name='$name', size='$size', price='$price', 
                category='$category', shoe_type='$shoe_type',
                quantity='$quantity', image='$image_name' 
                WHERE id = $id";
    } else {
        $sql = "UPDATE products_admin SET 
                name='$name', size='$size', price='$price', 
                category='$category', shoe_type='$shoe_type',
                quantity='$quantity' 
                WHERE id = $id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ../products.php");
        exit();
    } else {
        echo "Lỗi cập nhật: " . mysqli_error($conn);
    }
}

ob_end_flush(); // Gửi output sau khi đã xử lý
?>
