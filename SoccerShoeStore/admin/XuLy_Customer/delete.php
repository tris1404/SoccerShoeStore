<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../customer.php"); // chuyển hướng về trang danh sách khách hàng
    } else {
        echo "Lỗi khi xoá: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
