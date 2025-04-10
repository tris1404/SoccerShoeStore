<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $size = $conn->real_escape_string($_POST['size']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $shoe_type = $conn->real_escape_string($_POST['shoe_type']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $discount = isset($_POST['discount']) ? $conn->real_escape_string($_POST['discount']) : 0; // Thêm giảm giá

    // Xử lý ảnh tải lên
    $target_dir = "../uploads/";
    // Đảm bảo thư mục uploads tồn tại
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Thêm sản phẩm vào bảng products_admin
        $sql_admin = "INSERT INTO products_admin (name, size, price, category, shoe_type, quantity, image, discount) 
                      VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_name', '$discount')";
        
        // Thêm sản phẩm vào bảng products (cho khách hàng)
        $sql_customer = "INSERT INTO products (name, size, price, brand, shoe_type, quantity, image, discount) 
                         VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_name', '$discount')";
        
        if (mysqli_query($conn, $sql_admin) && mysqli_query($conn, $sql_customer)) {
            header("Location: ../products.php");
            exit();
        } else {
            echo "Lỗi SQL: " . mysqli_error($conn);
        }
    } else {
        echo "Lỗi khi tải ảnh lên. Kiểm tra quyền thư mục uploads hoặc định dạng file.";
    }
    
    mysqli_close($conn);
}
?>