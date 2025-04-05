<?php
    $conn = new mysqli("localhost", "root", "", "soccershoestore");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $shoe_type = $_POST['shoe_type'];
    $quantity = $_POST['quantity'];

    // Xử lý ảnh tải lên
    $target_dir = "../uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Thêm sản phẩm vào database
    $sql = "INSERT INTO products_admin (name, size, price, category, shoe_type, quantity, image) 
            VALUES ('$product_name', '$size', '$price', '$category', '$shoe_type', '$quantity', '$image_name')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../products.php");
     } else {
         echo "Lỗi SQL: " . mysqli_error($conn);
     }
    
    
    mysqli_close($conn);
    }
?>
