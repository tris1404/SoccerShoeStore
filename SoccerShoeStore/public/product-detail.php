<?php
// Giả sử bạn có một mảng sản phẩm (hoặc truy vấn từ database)
$products = [
    1 => [
        'name' => 'Nike Zoom Mercurial Superfly 9 Elite "Marcus Rashford"',
        'price' => '2.500.000đ',
        'image' => 'https://static.nike.com/a/images/c_limit,w_592,f_auto/t_product_v1/8e644f9b-4db8-4d24-91d3-1edf45ae1e3c/ZOOM+SUPERFLY+9+ELITE+MR+FG.png',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
    2 => [
        'name' => 'PUMA ULTRA 5 MATCH VOL. UP TT',
        'price' => '2.500.000đ',
        'image' => 'https://product.hstatic.net/200000740801/product/lux_galaxy01015_ac8ffab58496489c889b55161689e6e6_master.jpg',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
    3 => [
        'name' => 'ADIDAS F50 PRO TF - IE1220 - TRẮNG/ĐỎ',
        'price' => '2.500.000đ',
        'image' => 'https://assets.adidas.com/images/w_600,f_auto,q_auto/74600fef74e8434ba613699779d2f806_9366/Giay_DJa_Bong_Turf_F50_League_trang_IE1231_22_model.jpg',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
    4 => [
        'name' => 'MIZUNO A JAPAN',
        'price' => '2.500.000đ',
        'image' => 'https://product.hstatic.net/1000313927/product/sh_p1ga236009_06_d1246c9c1033489ab7787adc851cc503_large.jpg',
        'description' => 'Mô tả chi tiết sản phẩm Nike...',
    ],
];

// Lấy ID từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Kiểm tra sản phẩm có tồn tại
if ($id && isset($products[$id])) {
    $product = $products[$id];
} else {
    die('Sản phẩm không tồn tại!');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=1" type="text/css"> <!-- CSS của trang chủ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/product-detail.css?v=1" type="text/css">
    <!-- CSS riêng cho trang chi tiết -->
    <title><?php echo $product['name']; ?> - Soccer Shoes Store</title>
</head>

<body>
    <div id="wrapper">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- Header giống trang chủ -->
        <!-- End header -->

        <!-- Nội dung chi tiết sản phẩm -->
        <div class="product-detail-container">
            <div class="product-image">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="product-info">
                <h1><?php echo $product['name']; ?></h1>
                <p class="price"><?php echo $product['price']; ?></p>
                <p class="description"><?php echo $product['description']; ?></p>
                <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
            </div>
        </div>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- Footer giống trang chủ -->
        <!-- End footer -->
    </div>
</body>

</html>