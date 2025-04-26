<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Đăng kí | Đăng nhập</title>
</head>


<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form method="post" action="login.php" onsubmit="return validateForm(event)">
                <h1>Đăng Kí</h1>
                <?php echo "<h4 style='color: red; font-size: 14px; font-weight: bold; margin-top: 5px;' class='invalid-feedback'>$errorMsg</h4>"; ?>
                <div class="social-icons">
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>hoặc sử dụng mật khẩu email của bạn</span>
                <select name="role" id="role" class="role">
                    <option value="" disabled selected hidden>Role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Nhân viên</option>
                    <option value="user">Người Dùng</option>
                </select>
                <span id="role-error" class="error-msg"></span>
                <input type="email" name="email" id="email" placeholder="Email">
                <span id="email-error" class="error-msg"></span>
                <div class="password-container">
                    <input type="password" id="login-password" name="password" placeholder="Mật khẩu">
                    <i class="fa-solid fa-eye" id="toggleLoginPassword" onclick="togglePassword('login-password', 'toggleLoginPassword')"></i>
                </div>  
                <span id="password-error" class="error-msg"></span>
                <a href="#">Quên mất khẩu?</a>
                <button name="signin">Đăng nhập</button>
            </form>
        </div>
        <div class="form-container sign-up">
            <form method="post" action="register.php" onsubmit="return validateForm(event)">
                <h1>Tạo Tài Khoản</h1>
                <?php echo "<h4 style='color: red; font-size: 14px; font-weight: bold; margin-top: 5px;' class='invalid-feedback'>$errorMsg</h4>"; ?>
                <div class="social-icons">
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>hoặc sử dụng email của bạn để đăng ký</span>
                <input type="text" name="name" id="name" placeholder="Name">
                <span id="name-error" class="error-msg"></span>

                <input type="email" name="email" id="email" placeholder="Email">
                <span id="email-error" class="error-msg"></span>

                <div class="password-container">
                    <input type="password" id="register-password" name="password" placeholder="Mật khẩu">
                    <i class="fa-solid fa-eye" id="toggleRegisterPassword" onclick="togglePassword('register-password', 'toggleRegisterPassword')"></i>
                </div>


                <span id="password-error" class="error-msg"></span>
                <button name="signup">Đăng Kí</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Xin Chào Bạn</h1>
                    <p>Đăng ký thông tin cá nhân của bạn để sử dụng tất cả các tính năng của trang web</p>
                    <button class="hidden" id="register">Đăng kí</button>
                </div>
                <div class="toggle-panel toggle-left">
                    <h1>Chào Bạn Đã Trở lại</h1>
                    <p>Nhập thông tin cá nhân của bạn để sử dụng tất cả các tính năng của trang web</p>
                    <button class="hidden" id="login">Đăng nhập</button>
                </div>
                
            </div>
        </div>
    </div>
    <script src="assets/js/login.js"></script>
</body>
</html>

