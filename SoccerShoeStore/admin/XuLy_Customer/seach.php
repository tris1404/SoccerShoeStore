<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Nếu có từ khóa tìm kiếm, thay đổi câu truy vấn
$sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$output = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>";
        $output .= "<td>" . $row['id'] . "</td>";
        $output .= "<td>" . htmlspecialchars($row['name']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['email']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['phone']) . "</td>";
        $output .= "<td><input type='password' id='password-{$row['id']}' value='" . htmlspecialchars($row['password']) . "' disabled>
                    <i class='fa-solid fa-eye' id='togglePassword-{$row['id']}' style='cursor: pointer; margin-left: 10px;'></i></td>";
        $output .= "<td>" . htmlspecialchars($row['address']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['status']) . "</td>";
        $output .= "<td><a class='edit-btn' href='XuLy_Customer/edit.php?id=" . $row['id'] . "'>Sửa</a> |
                    <a class='delete-btn' href='XuLy_Customer/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa người dùng này?\")'>Xóa</a></td>";
        $output .= "</tr>";
    }
} else {
    $output = "<tr><td colspan='8'>Không tìm thấy khách hàng nào.</td></tr>";
}

mysqli_close($conn);

echo $output;
?>
