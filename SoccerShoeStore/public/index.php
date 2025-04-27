<?php
// Include the database connection file
require_once("../config/database.php");

session_start();
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    echo "<script>alert('$message');</script>";
    unset($_SESSION['success']);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    echo "<script>alert('Đăng xuất thành công');</script>";
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=3" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/img/football-shoes.png">
    <title>Soccer Shoes Store</title>
</head>

<body>
    <div id="wrapper">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- End header -->

        <!-- Wrapper-container -->
        <div id="wrapper-container">
            <!-- Content -->
            <div class="content">
                <div class="slide-show">
                    <div class="list-img">
                        <a href="#"><img src="assets/img/banner/adidas.png" alt="Banner1"></a>
                        <a href="#"><img src="assets/img/banner/nike.png" alt="Banner2"></a>
                        <a href="#"><img src="assets/img/banner/puma.png" alt="Banner3"></a>
                        <a href="#"><img src="assets/img/banner/mizuno.jpg" alt="Banner4"></a>
                        <a href="#"><img src="assets/img/banner/kamito.png" alt="Banner5"></a>
                    </div>
                    <div class="btns">
                        <div class="btn-left"><i class="fa-solid fa-angle-left"></i></div>
                        <div class="btn-right"><i class="fa-solid fa-angle-right"></i></div>
                    </div>
                    <div class="index-img">
                        <div class="index-item index-item-0 active"></div>
                        <div class="index-item index-item-1"></div>
                        <div class="index-item index-item-2"></div>
                        <div class="index-item index-item-3"></div>
                        <div class="index-item index-item-4"></div>
                    </div>
                </div>

                <div class="mustbuy">
                    <div class="ega-container">
                        <div class="mustbuy-title">
                            <h2>BỘ SƯU TẬP MỚI</h2>
                        </div>
                        <div class="mustbuy-img">
                            <a href="Giay_Bong_Da.php">
                                <img src="assets/img/banner-title/nike.jpg" alt="Bộ sưu tập mới">
                            </a>
                            <?php
                            // Kết nối cơ sở dữ liệu
                            require_once("../config/database.php");

                            // Câu lệnh SQL để lấy dữ liệu
                            $sql = "SELECT * FROM products WHERE product_type = 'hot' ORDER BY created_at DESC LIMIT 4";

                            // Thực thi truy vấn
                            $result = mysqli_query($conn, $sql);

                            // Kiểm tra nếu có dữ liệu trả về
                            if ($result && $result->num_rows > 0): ?>
                                <div class="mustbuy-product">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <div class="mustbuy-item">
                                            <a href="product-detail.php?id=<?= $row['id'] ?>&source=home">
                                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                                                <h3><?= htmlspecialchars($row['name']) ?></h3>
                                                <?php if ($row['discount'] > 0): ?>
                                                    <span class="label-sale">-<?= $row['discount'] ?>%</span>
                                                <?php endif; ?>
                                                <span class="sale-tag" style="display: <?= htmlspecialchars($row['product_type']) === 'normal' ? 'none' : 'inline-block' ?>;">
                                                    <?= htmlspecialchars($row['product_type']) === 'hot' ? 'HOT' : (htmlspecialchars($row['product_type']) === 'new' ? 'NEW' : (htmlspecialchars($row['product_type']) === 'sale' ? 'SALE' : '')) ?>
                                                </span> 
                                                   
                                                <div class="price-container">
                                                    <span class="price">
                                                        <?= number_format($row['price'] * (1 - $row['discount'] / 100), 0, ',', '.') ?>
                                                    </span>
                                                    <span class="original-price">
                                                        <?= number_format($row['price'], 0, ',', '.') ?>đ
                                                    </span>
                                                    <button class="favorite-btn">
                                                        <i class="fa-regular fa-heart"></i>
                                                    </button>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="care-about">
                    <div class="ega-container">
                        <div class="care-about-title">
                            <h2>BẠN ĐANG QUAN TÂM ĐẾN - ƯU ĐÃI HẤP DẪN</h2>
                        </div>
                        <div class="care-about-img">
                            <a href="">
                            </a>
                            <div class="mustbuy-product">
                                <div class="mustbuy-item">
                                    <a href="Giay_San_NT.php">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/newcoll_1_img_large.jpg?v=2118"
                                            alt="Nike Air Max">
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Futsal.php">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/newcoll_2_img_large.jpg?v=2118"
                                            alt="Nike ZoomX">
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Sale.php">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/newcoll_4_img_large.jpg?v=2118"
                                            alt="Nike Air Force 1">
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="index.php">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/newcoll_3_img_large.jpg?v=2118"
                                            alt="Nike Air Force 1">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-brand">
                    <div class="ega-container">
                        <div class="list-brand-title">
                            <h2>THƯƠNG HIỆU</h2>
                        </div>
                        <div class="list-brand-img">
                            <a href="">
                            </a>
                            <div class="mustbuy-product">
                                <div class="mustbuy-item">
                                <a href="Giay_Bong_Da.php?brand=NIKE" class="brand-link" data-brand="NIKE">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/check_use_icon_1_large.jpg?v=2118"
                                            alt="Nike Air Max">
                                        <h3>NIKE</h3>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Bong_Da.php?brand=ADIDAS" class="brand-link" data-brand="ADIDAS">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/check_use_icon_2_large.jpg?v=2118"
                                            alt="Nike ZoomX">
                                        <h3>ADIDAS</h3>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Bong_Da.php?brand=MIZUNO" class="brand-link" data-brand="MIZUNO">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/check_use_icon_3_large.jpg?v=2118"
                                            alt="Nike Air Force 1">
                                        <h3>MIZUNO</h3>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Bong_Da.php?brand=PUMA" class="brand-link" data-brand="PUMA">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/check_use_icon_4_large.jpg?v=2118"
                                            alt="Nike Air Force 1">
                                        <h3>PUMA</h3>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Bong_Da.php?brand=KAMITO" class="brand-link" data-brand="KAMITO">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/check_use_icon_8_large.jpg?v=2118"
                                            alt="Nike Air Force 1">
                                        <h3>KAMITO</h3>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="Giay_Bong_Da.php?brand=ZOCKER" class="brand-link" data-brand="ZOCKER">
                                        <img src="https://theme.hstatic.net/200000278317/1000929405/14/check_use_icon_9_large.jpg?v=2118"
                                            alt="Nike Air Force 1">
                                        <h3>ZOCKER</h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="list-sole">
                    <div class="ega-container">
                        <div class="list-sole-title">
                            <h2>LỰA CHỌN PHÙ HỢP - THỐNG TRỊ MỌI MẶT CỎ</h2>
                        </div>
                        <div class="list-sole-img">
                            <a href="">
                            </a>
                            <div class="mustbuy-product">
                                <div class="mustbuy-item">
                                    <a href="">
                                        <img src="https://brand.assets.adidas.com/image/upload/f_auto,q_auto,fl_lossy/global_main_pack_1_pure_victory_football_ss25_launch_plp_statement_card_1fg_d_992232ca56.jpg"
                                            alt="Nike Air Max">
                                        <h3>FIRM GROUND (FG)</h3>
                                        <p>Đế chuyên dụng cho sân cỏ tự nhiên khô ráo, có đinh dài giúp bám sân tốt</p>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="">
                                        <img src="https://brand.assets.adidas.com/image/upload/f_auto,q_auto,fl_lossy/global_main_pack_1_pure_victory_football_ss25_launch_plp_statement_card_6ag_d_e4cc4b01aa.jpg"
                                            alt="Nike ZoomX">
                                        <h3>Artificial Grass (AG)</h3>
                                        <p>Đế dành cho sân cỏ nhân tạo, đinh ngắn và nhiều hơn để giảm áp lực lên chân.
                                        </p>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="">
                                        <img src="https://brand.assets.adidas.com/image/upload/f_auto,q_auto,fl_lossy/global_main_pack_1_pure_victory_football_ss25_launch_plp_statement_card_3mg_d_995c1a9a37.jpg"
                                            alt="Nike Air Force 1">
                                        <h3>Multi-Ground (MG)</h3>
                                        <p>Đế đa dụng, kết hợp giữa FG và AG, phù hợp cho cả sân cỏ tự nhiên lẫn nhân
                                            tạo</p>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="">
                                        <img src="https://brand.assets.adidas.com/image/upload/f_auto,q_auto,fl_lossy/global_main_pack_1_pure_victory_football_ss25_launch_plp_statement_card_5sg_d_e4249b8c10.jpg"
                                            alt="Nike Air Force 1">
                                        <h3>Soft Ground (SG)</h3>
                                        <p>Đế dành cho sân cỏ tự nhiên mềm, thường có đinh kim loại giúp bám sân trơn
                                            trượt.</p>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="">
                                        <img src="https://brand.assets.adidas.com/image/upload/f_auto,q_auto,fl_lossy/global_main_pack_1_pure_victory_football_ss25_launch_plp_statement_card_4in_d_a2aec953c6.jpg"
                                            alt="Nike Air Force 1">
                                        <h3>Indoor (IN)</h3>
                                        <p>Đế bằng, chuyên dùng cho sân futsal trong nhà hoặc mặt sân cứng như bê tông.
                                        </p>
                                    </a>
                                </div>

                                <div class="mustbuy-item">
                                    <a href="">
                                        <img src="https://brand.assets.adidas.com/image/upload/f_auto,q_auto,fl_lossy/global_main_pack_1_pure_victory_football_ss25_launch_plp_statement_card_2tf_d_e7c204e02b.jpg"
                                            alt="Nike Air Force 1">
                                        <h3>Turf (TF)</h3>
                                        <p>Đế đinh dăm, thiết kế cho sân cỏ nhân tạo cứng hoặc sân đất, giúp di chuyển
                                            linh hoạt.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="hot">
                    <div class="ega-container">
                        <div class="hot-title">
                            <h2>SẢN PHẨM MỚI</h2>
                        </div>
                        <div class="hot-img">
                            <a href="">
                            </a>

                            <?php
                            // Kết nối cơ sở dữ liệu
                            require_once("../config/database.php");

                            // Câu lệnh SQL để lấy dữ liệu
                            $sql = "SELECT * FROM products WHERE product_type = 'new' ORDER BY created_at DESC LIMIT 4";
                            // Thực thi truy vấn
                            $result = mysqli_query($conn, $sql);

                            // Kiểm tra nếu có dữ liệu trả về
                            if ($result && $result->num_rows > 0): ?>
                                <div class="mustbuy-product">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <div class="mustbuy-item">
                                            <a href="product-detail.php?id=<?= $row['id'] ?>&source=home">
                                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                                                <h3><?= htmlspecialchars($row['name']) ?></h3>
                                                <?php if ($row['discount'] > 0): ?>
                                                    <span class="label-sale">-<?= $row['discount'] ?>%</span>
                                                <?php endif; ?>
                                                <span class="sale-tag" style="display: <?= htmlspecialchars($row['product_type']) === 'normal' ? 'none' : 'inline-block' ?>;">
                                                    <?= htmlspecialchars($row['product_type']) === 'hot' ? 'HOT' : (htmlspecialchars($row['product_type']) === 'new' ? 'NEW' : (htmlspecialchars($row['product_type']) === 'sale' ? 'SALE' : '')) ?>
                                                </span>
                                                <div class="price-container">
                                                    <span class="price">
                                                        <?= number_format($row['price'] * (1 - $row['discount'] / 100), 0, ',', '.') ?>
                                                    </span>
                                                    <span class="original-price">
                                                        <?= number_format($row['price'], 0, ',', '.') ?>đ
                                                    </span>
                                                    <button class="favorite-btn">
                                                        <i class="fa-regular fa-heart"></i>
                                                    </button>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>


                        </div>
                        <button id="btn-xem-them-hot">Xem thêm</button>
                    </div>
                </div>

                <div class="list-product">
                    <div class="ega-container">
                        <div class="list-product-title">
                            <h2>Giày sân cỏ tự nhiên</h2>
                        </div>
                        <div class="list-product-img">
                            <a href="">
                            </a>
                            <?php
                            // Kết nối cơ sở dữ liệu
                            require_once("../config/database.php");

                            // Câu lệnh SQL để lấy dữ liệu
                            $sql = "SELECT * FROM products WHERE shoe_type = 'Sân tự nhiên' ORDER BY created_at DESC LIMIT 4";
                            // Thực thi truy vấn
                            $result = mysqli_query($conn, $sql);

                            // Kiểm tra nếu có dữ liệu trả về
                            if ($result && $result->num_rows > 0): ?>
                                <div class="mustbuy-product">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <div class="mustbuy-item">
                                            <a href="product-detail.php?id=<?= $row['id'] ?>&source=home">
                                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                                                <h3><?= htmlspecialchars($row['name']) ?></h3>
                                                <?php if ($row['discount'] > 0): ?>
                                                    <span class="label-sale">-<?= $row['discount'] ?>%</span>
                                                <?php endif; ?>
                                                <span class="sale-tag" style="display: <?= htmlspecialchars($row['product_type']) === 'normal' ? 'none' : 'inline-block' ?>;">
                                                    <?= htmlspecialchars($row['product_type']) === 'hot' ? 'HOT' : (htmlspecialchars($row['product_type']) === 'new' ? 'NEW' : (htmlspecialchars($row['product_type']) === 'sale' ? 'SALE' : '')) ?>
                                                </span>
                                                <div class="price-container">
                                                    <span class="price">
                                                        <?= number_format($row['price'] * (1 - $row['discount'] / 100), 0, ',', '.') ?>
                                                    </span>
                                                    <span class="original-price">
                                                        <?= number_format($row['price'], 0, ',', '.') ?>đ
                                                    </span>
                                                    <button class="favorite-btn">
                                                        <i class="fa-regular fa-heart"></i>
                                                    </button>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button id="btn-xem-them-tu-nhien">Xem thêm</button>
                    </div>
                </div>
            </div>
            <!-- End content -->
            <!-- Sidebar -->
            <!-- End sidebar -->
        </div>
        <!-- End wrapper-container -->

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <!-- End footer -->
    </div>
    <button id="backToTop"><i class="fa-solid fa-angle-up"></i></button>
    <button id="zaloChat" onclick="window.open('https://zalo.me/09xxxxxxxx', '_blank')">
        <img src="https://stc-zaloprofile.zdn.vn/pc/v1/images/zalo_sharelogo.png" alt="Chat Zalo">
    </button>

    <script src="assets/js/scripts.js?v=1"></script>
</body>

</html>