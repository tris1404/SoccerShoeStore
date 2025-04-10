<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID sản phẩm cần xóa
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin hình ảnh để xóa file
$sql = "SELECT image FROM products_admin WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image_name = $row['image'];
    $image_path = "../uploads/" . $image_name;

    // Xóa sản phẩm từ bảng products_admin
    $sql_admin = "DELETE FROM products_admin WHERE id = $id";
    
    // Xóa sản phẩm từ bảng products (cho khách hàng)
    $sql_customer = "DELETE FROM products WHERE id = $id";

    if (mysqli_query($conn, $sql_admin) && mysqli_query($conn, $sql_customer)) {
        // Xóa file hình ảnh (nếu tồn tại)
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        header("Location: ../products.php");
        exit();
    } else {
        echo "Lỗi SQL: " . mysqli_error($conn);
    }
} else {
    echo "Không tìm thấy sản phẩm!";
}

$conn->close();
?>