<?php
// Giữ nguyên mảng $products hiện tại
$products = [
    1 => [
        'name' => 'Nike Zoom Mercurial Superfly 9 Elite "Marcus Rashford"',
        'price' => '2.500.000',
        'image' => 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/8e644f9b-4db8-4d24-91d3-1edf45ae1e3c/ZOOM+SUPERFLY+9+ELITE+MR+FG.png',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
    2 => [
        'name' => 'PUMA ULTRA 5 MATCH VOL. UP TT',
        'price' => '2.500.000',
        'image' => 'https://product.hstatic.net/200000740801/product/lux_galaxy01015_ac8ffab58496489c889b55161689e6e6_master.jpg',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
    3 => [
        'name' => 'ADIDAS F50 PRO TF - IE1220 - TRẮNG/ĐỎ',
        'price' => '2.500.000',
        'image' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/74600fef74e8434ba613699779d2f806_9366/Giay_DJa_Bong_Turf_F50_League_trang_IE1231_22_model.jpg',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
    4 => [
        'name' => 'MIZUNO A JAPAN',
        'price' => '2.500.000',
        'image' => 'https://product.hstatic.net/1000313927/product/sh_p1ga236009_06_d1246c9c1033489ab7787adc851cc503_large.jpg',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
];

// Lấy ID và nguồn từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$source = isset($_GET['source']) ? $_GET['source'] : 'home'; // Mặc định là 'home' nếu không có source

// Khởi tạo biến $product
$product = null;

// Nếu nguồn là 'home', lấy từ mảng $products
if ($source === 'home') {
    if ($id && isset($products[$id])) {
        $product = $products[$id];
    } else {
        die('Sản phẩm không tồn tại trong danh sách trang chủ!');
    }
}
// Nếu nguồn là 'natural', lấy từ database
elseif ($source === 'natural') {
    // Bổ sung kết nối database
    $conn = new mysqli("localhost", "root", "", "soccershoestore");
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Truy vấn sản phẩm từ database
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product_from_db = $result->fetch_assoc();
        $product = [
            'name' => $product_from_db['name'],
            'price' => number_format($product_from_db['price'], 0, ',', '.') . 'đ',
            'image' => '../admin/uploads/' . $product_from_db['image'],
            'description' => $product_from_db['description'] ?? 'Không có mô tả',
            'size' => $product_from_db['size'] ?? ''
        ];
    } else {
        die('Sản phẩm không tồn tại trong cơ sở dữ liệu!');
    }

    $conn->close();
} else {
    die('Nguồn dữ liệu không hợp lệ!');
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css"> <!-- CSS của trang chủ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/product-detail.css?v=1" type="text/css">
    <!-- CSS riêng cho trang chi tiết -->
    <title><?php echo htmlspecialchars($product['name']); ?> - Soccer Shoes Store</title>
</head>

<body>
    <div id="wrapper">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- End header -->

        <!-- Nội dung chi tiết sản phẩm -->
        <div id="wrapper-container">
            <!-- Content -->
            <div class="content">
                <div class="product-detail-container">
                    <div class="product-image">
                        <?php
                        // Kiểm tra xem hình ảnh có phải từ database không
                        if ($source === 'natural' && file_exists($product['image'])) {
                            echo "<img src='{$product['image']}' alt='" . htmlspecialchars($product['name']) . "'>";
                        } else {
                            echo "<img src='{$product['image']}' alt='" . htmlspecialchars($product['name']) . "'>";
                        }
                        ?>
                    </div>
                    <div class="product-info">
                        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p class="price"><?php echo $product['price']; ?></p>
                        <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="size-container">
                            <label for="size">Chọn kích thước:</label>
                            <div class="size-options">
                                <?php
                                // Bổ sung logic để hiển thị kích thước từ database nếu có
                                if ($source === 'natural' && isset($product['size']) && !empty($product['size'])) {
                                    $sizes = explode(",", $product['size']);
                                    foreach ($sizes as $size) {
                                        echo "<button class='size-btn' onclick='selectSize($size)'>$size</button>";
                                    }
                                } else {
                                    // Giữ nguyên logic cũ nếu không có size trong database hoặc source là 'home'
                                    for ($size = 38; $size <= 45; $size++) {
                                        echo "<button class='size-btn' onclick='selectSize($size)'>$size</button>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="quantity-cart-container">
                            <div class="quantity-container">
                                <button class="quantity-btn" onclick="changeQuantity(-1, 'quantity1')">-</button>
                                <input type="text" id="quantity1" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="changeQuantity(1, 'quantity1')">+</button>
                            </div>
                            <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                            <button class="favorite-btn">Thêm vào yêu thích
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>
                        <button class="buy-now-btn">Mua ngay</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- End footer -->
    </div>

    <script>
        function changeQuantity(amount, inputId) {
            const input = document.getElementById(inputId);
            let currentValue = parseInt(input.value);
            if (!isNaN(currentValue)) {
                currentValue += amount;
                if (currentValue < 1) {
                    currentValue = 1; // Không cho phép giá trị nhỏ hơn 1
                }
                input.value = currentValue;
            }
        }

        function selectSize(size) {
            alert('Bạn đã chọn size ' + size);
        }
    </script>
</body>

</html>