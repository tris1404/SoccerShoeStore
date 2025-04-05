<?php
ini_set('session.cookie_lifetime', 0); // Hết khi đóng trình duyệt
session_start();
$errorMsg = "";
$_SESSION['success'] = "Đăng nhập thành công!";



if (isset($_POST["signin"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $_SESSION['success'] = "Đăng nhập thành công!";


    // Kết nối cơ sở dữ liệu
    require_once("../config/database.php");

    // Câu lệnh truy vấn
    if ($role === "admin") {
        $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    } elseif ($role === "staff") {
        $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    } else {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    }

    // Thực thi câu lệnh
    $result = mysqli_query($conn, $sql);

    // Kiểm tra số lượng record trả về
    if (mysqli_num_rows($result) > 0) {
        // Lưu thông tin đăng nhập
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $row;
        $_SESSION['role'] = $role; // Lưu vai trò s


        // Lưu tên người dùng vào session để hiển thị sau khi đăng nhập thành công
        $_SESSION['user_name'] = $row['name']; // Giả sử cột tên là 'name'

        // Chuyển hướng đến trang quản trị
        if ($role === "admin" || $role === "staff") {
            header("Location: ../admin/admin.php");
        } else {
            header("Location: index.php");
        }
        exit(); // Dừng script sau khi chuyển hướng
    } else {
        $_SESSION['error'] = "Đăng nhập không hợp lệ, vui lòng thử lại";
        header("Location: login.php"); // Chuyển hướng về trang login
        exit();
    }
} else {
    require_once("includes/login-form.php");
}
?>
