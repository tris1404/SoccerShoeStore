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
    
    // Lấy URL ảnh từ form
    $image_url = $conn->real_escape_string($_POST['image']);
    
    // Kiểm tra xem URL ảnh có hợp lệ không (tùy chọn)
    if (filter_var($image_url, FILTER_VALIDATE_URL) === false) {
        echo "URL ảnh không hợp lệ.";
        exit();
    }

    // Tính giá sau khi giảm
    $discount_price = ($discount > 0) ? $price * (1 - $discount / 100) : NULL;

    // Chuẩn bị giá trị discount_price
    $discount_price_value = $discount_price ? "'$discount_price'" : "NULL";

    // Thêm vào bảng products_admin
    $sql_admin = "INSERT INTO products_admin (name, size, price, category, shoe_type, quantity, image, discount, discount_price, product_type) 
                  VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_url', '$discount', $discount_price_value, '$product_type')";

    // Thêm vào bảng products (cho khách hàng)
    $sql_customer = "INSERT INTO products (name, size, price, brand, shoe_type, quantity, image, discount, discount_price, product_type) 
                     VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_url', '$discount', $discount_price_value, '$product_type')";

    if (mysqli_query($conn, $sql_admin) && mysqli_query($conn, $sql_customer)) {
        header("Location: ../products.php");
        exit();
    } else {
        echo "Lỗi SQL: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>