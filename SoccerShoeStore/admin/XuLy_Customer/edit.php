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
    <link rel="stylesheet" href="../assets/css/customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables */
        :root {
            --primary-color: #e6c200; /* Vàng nhạt ánh kim */
            --background-color: #f5f5f5; /* Trắng ngọc trai */
            --card-background: rgba(255, 255, 255, 0.7); /* Kính mờ nhẹ */
            --text-color: #333333; /* Đen nhạt */
            --text-muted: #666666; /* Xám trung */
            --border-color: rgba(0, 0, 0, 0.1); /* Viền mờ */
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease-in-out;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            margin: 20px;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        form {
            width: 100%;
            max-width: 700px;
            background: var(--card-background);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
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

        /* Đảm bảo tiêu đề "Sửa khách hàng" chiếm cả hai cột */
        .form-group.form-title {
            grid-column: 1 / span 2;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-group.form-title h2 {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--text-color);
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.5);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        input:focus,
        select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 8px rgba(230, 194, 0, 0.3);
        }

        input::placeholder,
        select::placeholder {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group.full-width {
            position: relative;
        }

        #togglePassword {
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary-color);
        }

        button[type="submit"] {
            grid-column: 1 / span 2;
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, var(--primary-color), #d4af37);
            color: #ffffff;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #d4af37, var(--primary-color));
            box-shadow: 0 0 15px rgba(230, 194, 0, 0.5);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }

            .form-group {
                grid-column: 1 !important;
            }
        }
    </style>
</head>
<body>
    <form method="POST" action="edit.php?id=<?= $user['id'] ?>">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <!-- Tiêu đề "Sửa khách hàng" được đưa vào bên trong form -->
        <div class="form-group form-title">
            <h2>Sửa khách hàng</h2>
        </div>

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
                <option value="Active" <?= $user['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $user['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group full-width">
            <label>Mật khẩu:</label>
            <div style="position: relative;">
                <input type="password" name="password" id="password" value="<?= $user['password'] ?>" required>
                <i class="fa-solid fa-eye" id="togglePassword"></i>
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