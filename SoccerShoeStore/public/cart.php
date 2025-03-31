<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css?v=2" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Soccer Shoes Store</title>
</head>

<style>
.wrapper-cart {
    background-color: #fff;
    padding: 20px 150px 0px;
    margin: 0 auto;
    display: flex
}

.shopping-cart {
    width: 800px;
    height: 50px;
    background-color: antiquewhite;

}

.bill {
    width: 380px;
    height: 50px;
    background-color: aqua;

}

.title {
    margin-top: 10px;
    margin-left: 10px;
    font-size: 30px;
    float: left;
}

.numbers-product {
    margin: 20px;
    float: right;
}
</style>
<body>
    <div id="wrapper">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>
        <!-- End header -->

        <!-- Wrapper-container -->
        <div id="wrapper-container">
            <!-- Content -->
            <div class="content">
            <div class="wrapper-cart">
                    <div class="shopping-cart">
                        <div class="title"><h4>Shopping Cart</h4></div>
                        <div class="numbers-product">Sản phẩm: 3</div>
                        <div class="product">
                            <div class="product-items">

                            </div>
                        </div>
                    </div> 
                    <div class="bill">

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