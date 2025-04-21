<?php
$conn = new mysqli("localhost", "root", "", "soccershoestore");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM categories WHERE id = $id";
    $result = $conn->query($sql);
    $category = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['category_name']);
    $slug = $conn->real_escape_string($_POST['category_slug']);
    $status = $conn->real_escape_string($_POST['category_status']);
    $parent_id = !empty($_POST['category_parent']) ? intval($_POST['category_parent']) : null;

    // Kiểm tra slug đã tồn tại chưa (trừ chính danh mục đang sửa)
    $check_sql = "SELECT id FROM categories WHERE slug = '$slug' AND id != $id";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        die("Slug đã tồn tại. Vui lòng chọn slug khác.");
    }

    $sql = "UPDATE categories 
            SET name='$name', slug='$slug', status='$status', parent_id=" . ($parent_id ? $parent_id : 'NULL') . ", updated_at=NOW() 
            WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../categories.php");
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Danh mục</title>
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

        /* Đảm bảo tiêu đề "Sửa Danh mục" chiếm cả hai cột */
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

        .form-label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
            color: var(--text-color);
            font-size: 14px;
        }

        .form-control,
        .form-select {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 0;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.5);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 8px rgba(230, 194, 0, 0.3);
        }

        .form-control::placeholder,
        .form-select::placeholder {
            color: var(--text-muted);
            font-size: 14px;
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

            .form-group:nth-child(odd),
            .form-group:nth-child(even) {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
        <div class="form-group form-title">
            <h2>Sửa Danh mục</h2>
        </div>

        <div class="form-group">
            <label class="form-label">Tên Danh mục:</label>
            <input type="text" class="form-control" id="category-name" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required onkeyup="generateSlug(this.value)">
        </div>

        <div class="form-group">
            <label class="form-label">Slug:</label>
            <input type="text" class="form-control" id="category-slug" name="category_slug" value="<?php echo htmlspecialchars($category['slug']); ?>" required readonly>
        </div>

        <div class="form-group">
            <label class="form-label">Trạng thái:</label>
            <select class="form-select" id="category-status" name="category_status" required>
                <option value="Active" <?php echo $category['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="Inactive" <?php echo $category['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Danh mục cha:</label>
            <select class="form-select" id="category-parent" name="category_parent">
                <option value="">Không có</option>
                <?php
                $conn = new mysqli("localhost", "root", "", "soccershoestore");
                $sql = "SELECT * FROM categories WHERE parent_id IS NULL AND id != " . $category['id'];
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['id'] == $category['parent_id']) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                }
                $conn->close();
                ?>
            </select>
        </div>

        <button type="submit">Cập nhật</button>
    </form>

    <script>
    function generateSlug(name) {
        let slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('category-slug').value = slug;
    }
    </script>
</body>
</html>