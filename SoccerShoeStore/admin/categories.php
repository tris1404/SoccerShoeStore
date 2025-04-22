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



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Danh mục</title>
    <link rel="stylesheet" href="assets/css/styles_admin.css?v=1">
    <link rel="stylesheet" href="assets/css/categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../public/assets/img/football-shoes.png">
</head>
<body>
    <div class="wrapper">
        <!-- Menu -->
        <?php include 'template/sidebar.php'; ?>
        <!-- End menu -->
        <!-- Header -->
        <?php include 'template/header.php'; ?>
        <!-- End header -->
        <!-- Nội dung chính -->
        <main class="main-content">
            <h2>Quản lý Danh mục</h2>

            <!-- Tìm kiếm và lọc -->
            <div class="filter-section">
                <input type="text" id="search" placeholder="Tìm kiếm danh mục..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select id="status-filter" name="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="Active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
                <button onclick="applyFilter()">Lọc</button>
            </div>

            <!-- Nút thêm danh mục -->
            <button class="add-btn" onclick="showForm()">Thêm Danh mục</button>

            <!-- Form thêm danh mục -->
            <div id="category-form" class="hidden">
                <h3>Thêm Danh mục mới</h3>
                <form id="add-category-form" method="POST" action="XuLy_Categories/add.php">
                    <label for="category-name">Tên Danh mục:</label>
                    <input type="text" id="category-name" name="category_name" required onkeyup="generateSlug(this.value)">

                    <label for="category-slug">Slug:</label>
                    <input type="text" id="category-slug" name="category_slug" required readonly>

                    <label for="category-status">Trạng thái:</label>
                    <select id="category-status" name="category_status" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <label for="category-parent">Danh mục cha:</label>
                    <select id="category-parent" name="category_parent">
                        <option value="">Không có</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "soccershoestore");
                        if ($conn->connect_error) {
                            die("Kết nối thất bại: " . $conn->connect_error);
                        }
                        $sql = "SELECT * FROM categories WHERE parent_id IS NULL";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>

                    <button type="submit" class="submit-btn">Thêm</button>
                </form>
            </div>

            <!-- Bảng danh sách danh mục -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Danh mục</th>
                        <th>Slug</th>
                        <th>Trạng thái</th>
                        <th>Danh mục cha</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Xử lý phân trang
                    $rows_per_page = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $rows_per_page;

                    // Đếm tổng số hàng
                    $count_sql = "SELECT COUNT(*) FROM categories c1 WHERE 1=1";
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = $conn->real_escape_string($_GET['search']);
                        $count_sql .= " AND c1.name LIKE '%$search%'";
                    }
                    if (isset($_GET['status']) && !empty($_GET['status'])) {
                        $status = $conn->real_escape_string($_GET['status']);
                        $count_sql .= " AND c1.status = '$status'";
                    }
                    $count_result = $conn->query($count_sql);
                    $total_rows = $count_result->fetch_array()[0];
                    $total_pages = ceil($total_rows / $rows_per_page);

                    // Truy vấn danh mục với phân trang
                    $sql = "SELECT c1.*, c2.name AS parent_name FROM categories c1 LEFT JOIN categories c2 ON c1.parent_id = c2.id WHERE 1=1";
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $sql .= " AND c1.name LIKE '%$search%'";
                    }
                    if (isset($_GET['status']) && !empty($_GET['status'])) {
                        $sql .= " AND c1.status = '$status'";
                    }
                    $sql .= " ORDER BY c1.id DESC LIMIT $offset, $rows_per_page";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['slug'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . ($row['parent_name'] ? $row['parent_name'] : 'Không có') . "</td>";
                        echo "<td>" . ($row['created_at'] ? $row['created_at'] : 'N/A') . "</td>";
                        echo "<td>" . ($row['updated_at'] ? $row['updated_at'] : 'N/A') . "</td>";
                        echo "<td>
                                <a class='edit-btn' href='XuLy_Categories/edit.php?id=" . $row['id'] . "'>Sửa</a>
                                <a class='delete-btn' href='XuLy_Categories/delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa? Xóa danh mục này sẽ xóa cả danh mục con.\")'>Xóa</a>
                            </td>";
                        echo "</tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <!-- Phân trang -->
            <div class="pagination">
                <?php
                // Nút "Trước"
                if ($page > 1) {
                    echo "<a href='categories.php?page=" . ($page - 1) . "&search=" . urlencode($_GET['search'] ?? '') . "&status=" . urlencode($_GET['status'] ?? '') . "'><</a>";
                } else {
                    echo "<a style='background: #e0e0e0; color: #666666; cursor: not-allowed;'><</a>";
                }

                // Hiển thị số trang
                $max_pages_to_show = 5;
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);

                if ($start_page > 1) {
                    echo "<a href='categories.php?page=1&search=" . urlencode($_GET['search'] ?? '') . "&status=" . urlencode($_GET['status'] ?? '') . "'>1</a>";
                    if ($start_page > 2) {
                        echo "<span class='dots'>...</span>";
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                        echo "<a class='active'>" . $i . "</a>";
                    } else {
                        echo "<a href='categories.php?page=" . $i . "&search=" . urlencode($_GET['search'] ?? '') . "&status=" . urlencode($_GET['status'] ?? '') . "'>" . $i . "</a>";
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo "<span class='dots'>...</span>";
                    }
                    echo "<a href='categories.php?page=" . $total_pages . "&search=" . urlencode($_GET['search'] ?? '') . "&status=" . urlencode($_GET['status'] ?? '') . "'>" . $total_pages . "</a>";
                }

                // Nút "Sau"
                if ($page < $total_pages) {
                    echo "<a href='categories.php?page=" . ($page + 1) . "&search=" . urlencode($_GET['search'] ?? '') . "&status=" . urlencode($_GET['status'] ?? '') . "'>></a>";
                } else {
                    echo "<a style='background: #e0e0e0; color: #666666; cursor: not-allowed;'>></a>";
                }
                ?>
            </div>
        </main>
        <!-- End Nội dung chính -->
        <!-- Footer -->
        <?php include 'template/footer.php'; ?>
        <!-- End Footer -->
    </div>

    <script>
    function showForm() {
        var form = document.getElementById('category-form');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }

    function generateSlug(name) {
        let slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Xóa ký tự đặc biệt
            .trim() // Xóa khoảng trắng thừa
            .replace(/\s+/g, '-') // Thay khoảng trắng bằng dấu gạch ngang
            .replace(/-+/g, '-'); // Xóa dấu gạch ngang thừa
        document.getElementById('category-slug').value = slug;
    }

    function applyFilter() {
        const search = document.getElementById('search').value;
        const status = document.getElementById('status-filter').value;
        let url = 'categories.php?';
        let params = [];
        if (search) params.push('search=' + encodeURIComponent(search));
        if (status) params.push('status=' + encodeURIComponent(status));
        url += params.join('&');
        window.location.href = url;
    }

    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilter();
        }
    });
    </script>
</body>
</html>