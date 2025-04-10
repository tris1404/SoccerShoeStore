<?php
session_start();
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    echo "<script>alert('$message');</script>";
    unset($_SESSION['success']);
}
// Xóa session khi người dùng vào trang admin.php


// Kiểm tra nếu chưa đăng nhập hoặc không phải admin/staff
if (!isset($_SESSION['user']) || !isset($_SESSION['role']) || 
    ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    
    // Chuyển về trang login
    header("Location: ../public/login.php");
    exit();
}
?>

<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Nếu có từ khóa tìm kiếm, thay đổi câu truy vấn
$sql = "SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khách hàng</title>
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
    <link rel="stylesheet" href="assets/css/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <!-- menu -->
        <?php include 'template/sidebar.php'; ?>
        <!-- End menu -->
        
        <!-- header -->
        <?php include 'template/header.php'; ?>
        <!-- end header -->
        
        <!-- Nội dung chính -->
        <main class="main-content">
            <h2>Quản lý Khách hàng</h2>
            <form method="GET" action="customer.php" style="margin-bottom: 20px;">
                <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Tìm kiếm khách hàng" style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
                <button type="submit" style="padding: 8px 12px; border: 1px solid #ccc; background-color:#0b529e; color: white; border-radius: 4px; cursor: pointer;">Tìm kiếm</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Mật Khẩu</th>
                        <th>Địa Chỉ</th>
                        <th>Trạng Thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td>
                                    <input type='password' id='password-{$row['id']}' value='" . htmlspecialchars($row['password']) . "' disabled>
                                    <i class='fa-solid fa-eye' id='togglePassword-{$row['id']}' style='cursor: pointer; margin-left: 10px;'></i>
                                  </td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>
                                    <a class='edit-btn' href='XuLy_Customer/edit.php?id=" . $row['id'] . "'>Sửa</a> |
                                    <a class='delete-btn' href='XuLy_Customer/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa người dùng này?\")'>Xóa</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Không tìm thấy khách hàng nào.</td></tr>";
                    }

                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </main>
        
        <!-- Footer -->
        <?php include 'template/footer.php'; ?> 
        <!-- End Footer -->
    </div>

    <!-- Script cho toggle mật khẩu -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const togglePasswordIcons = document.querySelectorAll('[id^="togglePassword-"]');
            
            togglePasswordIcons.forEach(icon => {
                icon.addEventListener("click", function() {
                    const id = this.id.split('-')[1];
                    const passwordField = document.getElementById(`password-${id}`);
                    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                    passwordField.setAttribute("type", type);
                    
                    this.classList.toggle("fa-eye");
                    this.classList.toggle("fa-eye-slash");
                });
            });
        });
    </script>
</body>
</html>
