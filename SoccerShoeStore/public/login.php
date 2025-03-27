<?php
session_start();
$errorMsg = "";
if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Kết nối cơ sở dữ liệu
    require_once("../config/database.php");
    // câu lệnh truy vấn
    $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    // thực thi câu lệnh
    $result = mysqli_query($conn, $sql);
    //kiểm tra số lượng record trả về: > 0: đăng nhập thành công
    if (mysqli_num_rows($result) > 0) {
        // echo "<h1>Đăng nhập thành công</h1>";
        // lưu tru thông tin dang nhap
        $row = mysqli_num_rows($result);
        $_SESSION['user'] = $row; 
        // chuyển đến trang quản trị
        header("Location: ../admin/admin.php");
    } else {
        $errorMsg = "Đăng nhập không hợp lệ, vui lòng thử lại";
        require_once("includes/login-form.php");
    }
} else {
    require_once("includes/login-form.php");
}
?>