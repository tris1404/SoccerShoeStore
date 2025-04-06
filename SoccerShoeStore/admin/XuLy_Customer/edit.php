<?php
ob_start();
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$user = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "<h3>Không tìm thấy khách hàng!</h3>";
        exit();
    }
} else {
    echo "<h3>Thiếu ID khách hàng!</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa khách hàng</title>
    <link rel="stylesheet" href="../assets/css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            margin: 20px;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        h2 {
            text-align: left;
            color: rgb(39, 83, 150);
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        form {
            width: 100%;
            max-width: 700px;
            background-color: rgb(245, 236, 213);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / span 2;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #495057;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
        }

        input:focus {
            border-color: rgb(39, 83, 150);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        button[type="submit"] {
            grid-column: 1 / span 2;
            padding: 10px 15px;
            border-radius: 4px;
            border: none;
            background-color: rgb(39, 83, 150);
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.2s ease-in-out;
        }

        button[type="submit"]:hover {
            background-color: rgb(25, 94, 198);
        }
    </style>
</head>
<body>
    <h2>Sửa khách hàng</h2>
    <form method="POST" action="edit.php?id=<?= $user['id'] ?>">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" name="name" value="<?= $user['name'] ?>" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="<?= $user['phone'] ?>" placeholder="Số điện thoại (tùy chọn)">
        </div>

        <div class="form-group">
            <label>Địa chỉ:</label>
            <input type="text" name="address" value="<?= $user['address'] ?>" placeholder="Địa chỉ (tùy chọn)">
        </div>
        
        <div class="form-group">
            <label>Trạng Thái:</label>
            <select name="status" id="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="form-group full-width">
            <label>Mật khẩu:</label>
            <div style="position: relative;">
                <input type="password" name="password" id="password" value="<?= $user['password'] ?>" required>
                <i class="fa-solid fa-eye" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999;"></i>
            </div>
        </div>

        <button type="submit">Cập nhật</button>
    </form>
    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = !empty($_POST['phone']) ? $_POST['phone'] : NULL;  
    $address = !empty($_POST['address']) ? $_POST['address'] : NULL; 
    $status = $_POST['status'];
    $password = $_POST['password'];

    $sql = "UPDATE users SET 
            name='$name',
            email='$email',
            phone=" . ($phone ? "'$phone'" : "NULL") . ",  
            address=" . ($address ? "'$address'" : "NULL") . ",
            status='$status',
            password='$password'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../customer.php");
        exit();
    } else {
        echo "Lỗi cập nhật: " . mysqli_error($conn);
    }
}
ob_end_flush();
?>
