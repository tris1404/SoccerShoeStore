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




<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            margin: 20px;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        form {
            width: 100%;
            max-width: 700px;
            background-color: rgb(245, 236, 213);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: auto auto;
            gap: 15px 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group:nth-child(odd) {
            grid-column: 1;
        }

        .form-group:nth-child(even) {
            grid-column: 2;
        }

        .form-group:nth-child(7), /* Số lượng */
        .form-group:nth-child(8), /* Giảm giá */
        .form-group:nth-child(9), /* Hình ảnh */
        .form-group:nth-child(10) /* Loại sản phẩm */ {
            grid-column: 1 / span 2;
        }

        /* Đảm bảo tiêu đề "Sửa sản phẩm" chiếm cả hai cột */
        .form-group.form-title {
            grid-column: 1 / span 2;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-group.form-title h2 {
            color: rgb(39, 83, 150);
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: #495057;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 0;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgb(39, 83, 150);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-control::placeholder,
        .form-select::placeholder {
            color: #6c757d;
            font-size: 14px;
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
            transition: background-color 0.2s ease-in-out;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: rgb(25, 94, 198);
        }

        .container {
            padding: 0;
        }

        /* Style cho input file */
        input[type="file"] {
            padding: 6px 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            margin-bottom: 0;
            box-sizing: border-box;
        }

        input[type="file"]::file-selector-button {
            background-color: #e9ecef;
            color: #495057;
            border: none;
            border-right: 1px solid #ced4da;
            padding: 6px 10px;
            margin-right: 10px;
            border-radius: 4px 0 0 4px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #dee2e6;
        }
    </style>