<?php

include '../config/database.php';
$new_password = "admin2"; 
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE admins SET password = ? WHERE email = 'admin@example.com'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);
$stmt->execute();
echo "Mật khẩu đã được cập nhật lại!";

?>