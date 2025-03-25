<?php
    if(isset($_POST['dangnhap'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        if($email == 'admin' && $password == '1'){
            $_SESSION['email'];
            header('location:/SoccerShoeStore/SoccerShoeStore/log/admin/');
        }

    }
?>
<html>
<head>
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form action ="login.php" method="post">
        <input type="text" name="email" >
        <input type="password" name="password" >
        <button type="submit" name="dangnhap">đangnhap</button>
    </form>
</body>
</html>
