<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $size = $conn->real_escape_string($_POST['size']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $shoe_type = $conn->real_escape_string($_POST['shoe_type']);
    $quantity = intval($_POST['quantity']);
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $product_type = $conn->real_escape_string($_POST['product_type']);
    
    // Lấy URL ảnh từ form
    $image_url = isset($_POST['image']) ? trim($conn->real_escape_string($_POST['image'])) : '';

    // Kiểm tra dữ liệu đầu vào
    if (empty($product_name) || empty($size) || empty($price) || empty($category) || empty($shoe_type) || empty($quantity) || empty($image_url)) {
        echo "Vui lòng điền đầy đủ các trường bắt buộc, bao gồm URL hình ảnh.";
        exit();
    }

    // Kiểm tra URL ảnh (nới lỏng kiểm tra)
    if (!preg_match('/^(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[\w\-\.\/]*)*\.(jpg|jpeg|png|gif)$/i', $image_url)) {
        echo "URL ảnh không hợp lệ. Vui lòng nhập URL dẫn đến file ảnh (jpg, jpeg, png, gif).";
        exit();
    }

    // Tính giá sau khi giảm
    $discount_price = ($discount > 0) ? $price * (1 - $discount / 100) : $price;

    // Sử dụng prepared statements để chèn vào products_admin
    $sql_admin = "INSERT INTO products_admin (name, size, price, category, shoe_type, quantity, image, discount, discount_price, product_type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("ssdsdsidds", $product_name, $size, $price, $category, $shoe_type, $quantity, $image_url, $discount, $discount_price, $product_type);
    
    // Thực thi câu lệnh cho products_admin
    if ($stmt_admin->execute()) {
        // Lấy id của bản ghi vừa chèn
        $product_id = $conn->insert_id;

        // Sử dụng prepared statements để chèn vào products
        $sql_customer = "INSERT INTO products (id, name, size, price, brand, shoe_type, quantity, image, discount, discount_price, product_type) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_customer = $conn->prepare($sql_customer);
        $stmt_customer->bind_param("issdsdsidds", $product_id, $product_name, $size, $price, $category, $shoe_type, $quantity, $image_url, $discount, $discount_price, $product_type);

        // Thực thi câu lệnh cho products
        if ($stmt_customer->execute()) {
            header("Location: ../products.php");
            exit();
        } else {
            echo "Lỗi khi thêm vào bảng products: " . $stmt_customer->error;
        }

        $stmt_customer->close();
    } else {
        echo "Lỗi khi thêm vào bảng products_admin: " . $stmt_admin->error;
    }

    $stmt_admin->close();
    $conn->close();
}
?>