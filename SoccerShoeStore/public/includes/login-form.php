<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Modern Login Page | AsmrProg</title>
</head>


<body>
    <div class="container" id="container">
        <div class="form-container sign-in">
            <form method="post" action="login.php" onsubmit="return validateForm(event)">
                <h1>Sign In</h1>
                <?php echo "<h4 style='color: red; font-size: 14px; font-weight: bold; margin-top: 5px;' class='invalid-feedback'>$errorMsg</h4>"; ?>
                <div class="social-icons">
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <select name="role" id="role" class="role">
                    <option value="" disabled selected hidden>Role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="user">User</option>
                </select>
                <span id="role-error" class="error-msg"></span>
                <input type="email" name="email" id="email" placeholder="Email">
                <span id="email-error" class="error-msg"></span>
                <div class="password-container">
                    <input type="password" id="login-password" name="password" placeholder="Mật khẩu">
                    <i class="fa-solid fa-eye" id="toggleLoginPassword" onclick="togglePassword('login-password', 'toggleLoginPassword')"></i>
                </div>  
                <span id="password-error" class="error-msg"></span>
                <a href="#">Forget Your Password?</a>
                <button name="signin">Sign In</button>
            </form>
        </div>
        <div class="form-container sign-up">
            <form method="post" action="register.php" onsubmit="return validateForm(event)">
                <h1>Create Account</h1>
                <?php echo "<h4 style='color: red; font-size: 14px; font-weight: bold; margin-top: 5px;' class='invalid-feedback'>$errorMsg</h4>"; ?>
                <div class="social-icons">
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" onclick="alert('Đang hiện thực.....')" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registeration</span>
                <input type="text" name="name" id="name" placeholder="Name">
                <span id="name-error" class="error-msg"></span>

                <input type="email" name="email" id="email" placeholder="Email">
                <span id="email-error" class="error-msg"></span>

                <div class="password-container">
                    <input type="password" id="register-password" name="password" placeholder="Mật khẩu">
                    <i class="fa-solid fa-eye" id="toggleRegisterPassword" onclick="togglePassword('register-password', 'toggleRegisterPassword')"></i>
                </div>


                <span id="password-error" class="error-msg"></span>
                <button name="signup">Sign Up</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                
            </div>
        </div>
    </div>
    <script src="assets/js/login.js?v=4"></script>
</body>
</html>

