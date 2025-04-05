<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Xoá hình ảnh nếu cần
    $img_sql = "SELECT image FROM products_admin WHERE id = $id";
    $img_result = mysqli_query($conn, $img_sql);
    $img_row = mysqli_fetch_assoc($img_result);
    if ($img_row && file_exists("../uploads/" . $img_row['image'])) {
        unlink("../uploads/" . $img_row['image']);
    }

    // Xoá sản phẩm
    $sql = "DELETE FROM products_admin WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../products.php");
    } else {
        echo "Lỗi khi xoá: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
