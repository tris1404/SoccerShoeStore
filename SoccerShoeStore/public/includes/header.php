<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
    <div class="top-bar-container">
        <nav class="top-bar__left">
            <a href="https://maps.app.goo.gl/ZM8L8w7GoELmbm1x6" class="top-bar__left__item" target="_blank">Soccer Shoes Store
                <i class="fa-solid fa-location-dot"></i>
            </a>
            <span class="pipe1">|</span>
            <a href="https://www.facebook.com/nttris1404" class="top-bar__left__item" target="_blank">Kết nối
                <i class="fa-brands fa-facebook"></i>
            </a>
        </nav>

        <!-- TOP-BAR RIGHT -->
        <nav class="top-bar__right">
            <a href="#" class="top-bar__right__item">Liên hệ</a>
            <span class="pipe2">|</span>
            <a href="track_order.php" class="top-bar__right__item">Theo dõi đơn hàng</a>
            <span class="pipe2">|</span>

            <?php
            if (isset($_SESSION['user_name'])) {
                echo '<span class="top-bar__right__item">Hi, ' . htmlspecialchars($_SESSION['user_name']) . '</span><span class="pipe2">|</span>';
                echo '<a href="logout.php" class="top-bar__right__item">Đăng xuất</a>';
            } else {
                echo '<a href="register.php" class="top-bar__right__item">Đăng ký</a><span class="pipe2">|</span>';
                echo '<a href="login.php" class="top-bar__right__item">Đăng nhập</a>';
            }
            ?>
        </nav>
    </div>

    <div class="navigation-container">
        <div class="logo">
            <a href="index.php" class="logo-link">
                <img src="assets/img/logo.png" alt="Soccer Shoes Store">
            </a>
        </div>

        <nav class="navigation-menu">
            <ul class="main-navigation__list">
                <li class="main-navigation__item">
                    <a href="index.php" class="main-navigation__link">Trang chủ</a>
                </li>
                <li class="main-navigation__item">
                    <a href="Giay_Bong_Da.php" class="main-navigation__link">Giày bóng đá</a>
                    <i class="fa-solid fa-angle-down"></i>
                    <ul class="sub-navigation__list">
                        <li><a href="Giay_Bong_Da.php" class="sub-navigation__link">Tất cả sản phẩm</a></li>
                        <li><a href="Giay_San_TN.php" class="sub-navigation__link">Giày sân tự nhiên</a></li>
                        <li><a href="Giay_SAN_NT.php" class="sub-navigation__link">Giày sân nhân tạo</a></li>
                        <li><a href="Giay_Futsal.php" class="sub-navigation__link">Giày Futsal</a></li>
                        <li><a href="Giay_Tre_Em.php" class="sub-navigation__link">Giày trẻ em</a></li>
                        <li><a href="Hang_Moi_Ve.php" class="sub-navigation__link">Hàng mới về</a></li>
                        <li><a href="Giay_Sale.php" class="sub-navigation__link">Sale</a></li>
                        <li><a href="Giay_Hot.php" class="sub-navigation__link">HOT</a></li>
                    </ul>
                </li>

                <li class="main-navigation__item">
                    <a href="Giay_San_TN.php" class="main-navigation__link">Giày cỏ tự nhiên</a>
                </li>
                <li class="main-navigation__item">
                    <a href="Giay_San_NT.php" class="main-navigation__link">Giày cỏ nhân tạo</a>
                </li>
                <li class="main-navigation__item">
                    <a href="Giay_Futsal.php" class="main-navigation__link">Giày FUTSAL</a>
                </li>
                <li>
                    <a href="Giay_Hot.php" class="main-navigation__link">HOT</a>
                </li>
                <li>
                    <a href="Giay_Sale.php" class="main-navigation__link">Sale</a>
                </li>
            </ul>
        </nav>

        <!-- SEARCH -->
        <div class="search-cart">
            <div class="search">
                <form action="index.php?pg=giay_san_tu_nhien" method="post" class="search-form">
                    <button type="submit" class="search-icon">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <input type="search" name="search" class="search-form__input" placeholder="Search">
                </form>
            </div>

            <div class="cart">
                <a href="favorite.php" class="global-navigation__cart__link">
                    <i class="fa-regular fa-heart"></i>
                    <span class="global-navigation__cart__link__count">0</span>
                </a>

                <?php
                // Lấy số lượng sản phẩm trong giỏ hàng
                $cartCount = 0;

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user']['id'])) {
                    // Người dùng đã đăng nhập, lấy số lượng từ cơ sở dữ liệu
                    require_once '../config/database.php';
                    $userId = $_SESSION['user']['id'];
                    $query = "SELECT SUM(qty) AS total_items FROM cart_items WHERE cart_id = (SELECT id FROM cart WHERE user_id = ?)";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, 'i', $userId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $cartCount = (int)$row['total_items'];
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    // Người dùng chưa đăng nhập, lấy số lượng từ session
                    if (isset($_SESSION['guest_cart'])) {
                        foreach ($_SESSION['guest_cart'] as $item) {
                            $cartCount += $item['quantity'];
                        }
                    }
                }
                ?>
                <a href="cart.php" class="global-navigation__cart__link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="global-navigation__cart__link__count"><?php echo $cartCount; ?></span>
                </a>

                <div class="user-menu">
                    <a href="#" class="global-navigation__cart__link">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="register.php">Đăng ký</a></li>
                        <li><a href="login.php">Đăng nhập</a></li>
                        <li><a href="logout.php">Đăng xuất</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End header -->

<style>

</style>