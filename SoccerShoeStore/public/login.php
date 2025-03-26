<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];

    $sql = "SELECT * FROM admins WHERE email = ? AND type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Tài khoản không tồn tại"]);
        exit();
    }

    $user = $result->fetch_assoc();

    // Kiểm tra trạng thái tài khoản
    if ($user["status"] == 0) {
        echo json_encode(["success" => false, "message" => "Tài khoản của bạn đã bị khóa"]);
        exit();
    }

    // Kiểm tra mật khẩu
    if (password_verify($password, $user["password"])) {
        session_start();
        $_SESSION["admin_id"] = $user["id"];
        $_SESSION["type"] = $user["type"];

        echo json_encode(["success" => true, "type" => $user["type"]]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Sai mật khẩu"]);
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];

    $sql = "SELECT * FROM admins WHERE email = ? AND type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Debug: Kiểm tra mật khẩu nhập vào và mật khẩu trong DB
        echo "Nhập vào: " . $password . "<br>";
        echo "Trong DB: " . $user["password"] . "<br>";

        if (password_verify($password, $user["password"])) {
            echo " Mật khẩu đúng!";
        } else {
            echo " Sai mật khẩu!";
        }
    } else {
        echo " Không tìm thấy tài khoản!";
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Modern Login Page | DevyMae</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
        <form method="POST" action="register.php">
            <h1>Create Account</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>or use your email for registeration</span>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
       

        </div>
        <div class="form-container sign-in">
         <form method="POST" action="login.php">
            <h1>Sign In</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>or use your email password</span>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="type" id="type">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <a href="#">Forget Your Password?</a>
            <button type="submit">Sign In</button>
        </form>

        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/login.js"></script>
</body>

</html>
 