<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['category_name']);
    $slug = $conn->real_escape_string($_POST['category_slug']);
    $status = $conn->real_escape_string($_POST['category_status']);
    $parent_id = !empty($_POST['category_parent']) ? intval($_POST['category_parent']) : null;

    // Kiểm tra slug đã tồn tại chưa
    $check_sql = "SELECT id FROM categories WHERE slug = '$slug'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        die("Slug đã tồn tại. Vui lòng chọn slug khác.");
    }

    $sql = "INSERT INTO categories (name, slug, status, parent_id, created_at, updated_at) 
            VALUES ('$name', '$slug', '$status', " . ($parent_id ? $parent_id : 'NULL') . ", NOW(), NOW())";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../categories.php");
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

$conn->close();
?>