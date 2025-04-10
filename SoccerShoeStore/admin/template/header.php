<!-- header -->
<style>
    .user-menu {
        position: relative;
        display: inline-block;
    }

    /* Ví dụ CSS */
    .dropdown-menu {
        display: none;
        width: 120px;
        position: absolute;
        right: 0;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        list-style: none;
    }

    .user-menu:hover .dropdown-menu {
        display: block;
    }


    .dropdown-menu li {
        color: black;
        padding: 8px 16px;

    }

    .dropdown-menu li a {
        color: black;
        text-decoration: none;
        display: block;
    }


    /* Hiển thị menu khi hover vào user-menu */
    .user-menu:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu li:hover {
        background-color: #e8e8e8;
    }
</style>

<header class="navbar">
    <div class="menu-toggle toggle-btn">
        <i class="fas fa-bars"></i>
    </div>
    <div class="nav-right">
        <!-- Thanh tìm kiếm -->
        <div class="search-bar">
            <input type="text" placeholder="Tìm kiếm...">
            <button><i class="fas fa-search"></i></button>
        </div>
        <!-- user profile -->
        <div class="user-profile">
            <img src="assets/img/ava.jfif" alt="User">
            <span><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Quản Trị Viên'; ?></span>
        </div>


        <div class="user-menu">
            <a href="#" class="global-navigation__cart__link">
                <i class="fa-solid fa-chevron-down"></i> </a>
            <ul class="dropdown-menu">
                <li><a href="">Đăng ký</a></li>
                <li><a href="../public/logout.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>
<!-- end header -->