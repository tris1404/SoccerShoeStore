<?php
// Lấy tên file hiện tại
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h3>DANH MỤC SẢN PHẨM</h3>
    <ul>
        <li><a href="Giay_Bong_Da.php">TẤT CẢ SẢN PHẨM</a></li>
        <li><a href="Giay_San_TN.php">GIÀY CỎ TỰ NHIÊN</a></li>
        <li><a href="Giay_San_NT.php">GIÀY CỎ NHÂN TẠO</a></li>
        <li><a href="Giay_Futsal.php">GIÀY FUTSAL</a></li>
        <li><a href="Giay_Tre_Em.php">GIÀY ĐÁ BÓNG TRẺ EM</a></li>
        <li><a href="Hang_Moi_Ve.php">HÀNG MỚI VỀ</a></li>
        <li><a href="Giay_Sale.php">SALE</a></li>
        <li><a href="Giay_Hot.php">HOT</a></li>
    </ul>

    <!-- Thương hiệu -->
    <div class="brand-filter">
        <h3>THƯƠNG HIỆU</h3>
        <form id="brandForm">
            <label><input type="radio" name="brand" value="" checked> Tất cả thương hiệu</label><br>
            <label><input type="radio" name="brand" value="nike"> Nike</label><br>
            <label><input type="radio" name="brand" value="adidas"> Adidas</label><br>
            <label><input type="radio" name="brand" value="mizuno"> Mizuno</label><br>
            <label><input type="radio" name="brand" value="puma"> Puma</label>
        </form>
    </div>

    <!-- Khoảng giá -->
    <div class="price-filter">
        <h3>KHOẢNG GIÁ</h3>
        <form id="priceForm">
            <label><input type="radio" name="price" value="" checked> Tất cả</label><br>
            <label><input type="radio" name="price" value="0-1000000"> 0 ~ 1.000.000 VNĐ</label><br>
            <label><input type="radio" name="price" value="1000000-2000000"> 1.000.000 ~ 2.000.000 VNĐ</label><br>
            <label><input type="radio" name="price" value="2000000-3000000"> 2.000.000 ~ 3.000.000 VNĐ</label><br>
            <label><input type="radio" name="price" value="3000000-5000000"> 3.000.000 ~ 5.000.000 VNĐ</label><br>
            <label><input type="radio" name="price" value="5000000-99999999"> Trên 5.000.000VNĐ</label>
        </form>
    </div>

    <!-- Size -->
    <div class="size-filter">
        <h3>SIZE</h3>
        <form id="sizeForm" class="size-grid">
            <div class="size-col">
                <?php
                if ($currentPage == 'Giay_Tre_Em.php') {
                    for ($size = 25; $size <= 35; $size++) {
                        echo '<label><input type="radio" name="size" value="' . $size . '"> ' . $size . '</label><br>';
                        // Khi tới size 30 thì đóng cột 1, mở cột 2
                        if ($size == 30) {
                            echo '</div><div class="size-col">';
                        }
                    }
                } else {
                    for ($size = 25; $size <= 45; $size++) {
                        echo '<label><input type="radio" name="size" value="' . $size . '"> ' . $size . '</label><br>';
                        // Khi tới size 35 thì đóng cột 1, mở cột 2
                        if ($size == 35) {
                            echo '</div><div class="size-col">';
                        }
                    }
                }
                ?>
            </div>
        </form>
    </div>
</div>
