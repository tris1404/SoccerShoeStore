<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    header("Location: ../orders.php");
    exit();
} else {
    echo "Dữ liệu không hợp lệ.";
}
?>
