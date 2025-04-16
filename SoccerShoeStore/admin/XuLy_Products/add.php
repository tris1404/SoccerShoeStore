<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $size = $conn->real_escape_string($_POST['size']);
    $price = floatval($conn->real_escape_string($_POST['price']));
    $category = $conn->real_escape_string($_POST['category']);
    $shoe_type = $conn->real_escape_string($_POST['shoe_type']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $discount = isset($_POST['discount']) ? floatval($conn->real_escape_string($_POST['discount'])) : 0;
    $product_type = $conn->real_escape_string($_POST['product_type']);

    // Tính giá sau khi giảm
    $discount_price = ($discount > 0) ? $price * (1 - $discount / 100) : NULL;

    // Xử lý ảnh tải lên
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Thêm sản phẩm vào bảng products_admin
            $sql_admin = "INSERT INTO products_admin (name, size, price, category, shoe_type, quantity, image, discount, discount_price, product_type) 
                          VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_name', '$discount', " . ($discount_price ? "'$discount_price'" : "NULL") . ", '$product_type')";
            
            // Thêm sản phẩm vào bảng products (cho khách hàng)
            $sql_customer = "INSERT INTO products (name, size, price, brand, shoe_type, quantity, image, discount, discount_price, product_type) 
                             VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_name', '$discount', " . ($discount_price ? "'$discount_price'" : "NULL") . ", '$product_type')";
            
            if (mysqli_query($conn, $sql_admin) && mysqli_query($conn, $sql_customer)) {
                header("Location: ../products.php");
                exit();
            } else {
                echo "Lỗi SQL: " . mysqli_error($conn);
            }
        } else {
            echo "Lỗi khi tải ảnh lên. Kiểm tra quyền thư mục uploads hoặc định dạng file.";
        }
    } else {
        echo "Không có tệp ảnh nào được tải lên hoặc có lỗi trong quá trình tải lên.";
    }
    
    mysqli_close($conn);
}
?>