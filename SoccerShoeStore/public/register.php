<?php
session_start();
$errorMsg = "";
require_once("../config/database.php");

if (isset($_POST["signup"])) {
    // Kiểm tra nếu có trường nào bị bỏ trống
    if (!isset($_POST["name"]) || trim($_POST["name"]) == "") {
        echo "<script>alert('Vui lòng nhập họ và tên!');</script>";
        exit();
    } elseif (!isset($_POST["email"]) || trim($_POST["email"]) == "") {
        echo "<script>alert('Vui lòng nhập email!');</script>";
        exit();
    } elseif (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
        echo "<script>alert('Vui lòng nhập mật khẩu!');</script>";
        exit();
    }

    // Lấy dữ liệu từ form và xử lý tránh SQL Injection
    $name = mysqli_real_escape_string($conn, trim($_POST["name"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $password = mysqli_real_escape_string($conn, trim($_POST["password"]));

    // Kiểm tra xem email đã tồn tại chưa
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email này đã được sử dụng!');</script>";
        exit();
    } else {
        // Chèn dữ liệu vào CSDL
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Đăng ký thành công!'); window.location.href='login.php';</script>";
            exit();
        } else {
            $errorMsg = "Đăng ký không hợp lệ, vui lòng thử lại.";
            require_once("includes/login-form.php");
            exit();
        }
    }
} else {
    require_once("includes/login-form.php");
}
?>
