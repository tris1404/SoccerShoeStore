ádadsad 
# ⚽ Soccer Shoe Store - Hệ Thống Quản Lý Cửa Hàng Giày Bóng Đá

<div align="center">

![Soccer Shoe Store](public/assets/img/logo.png)

**Nền tảng thương mại điện tử chuyên nghiệp dành cho giày bóng đá**

[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://html.spec.whatwg.org/)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3.org/Style/CSS/)

</div>

---

## 📋 Mục Lục

- [Giới Thiệu](#-giới-thiệu)
- [Tính Năng](#-tính-năng)
- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Cấu Trúc Dự Án](#-cấu-trúc-dự-án)
- [Cài Đặt](#-cài-đặt)
- [Hướng Dẫn Sử Dụng](#-hướng-dẫn-sử-dụng)
- [Ảnh Giao Diện](#-ảnh-giao-diện)
- [Tài Khoản Demo](#-tài-khoản-demo)
- [Đóng Góp](#-đóng-góp)
- [Liên Hệ](#-liên-hệ)

---

## 🎯 Giới Thiệu

**Soccer Shoe Store** là một hệ thống thương mại điện tử hoàn chỉnh được phát triển bằng PHP thuần, chuyên cung cấp giày bóng đá chất lượng cao. Dự án được xây dựng với mục tiêu tạo ra một nền tảng mua sắm trực tuyến thân thiện, dễ sử dụng và đầy đủ tính năng cho cả khách hàng và quản trị viên.

### 🌟 Điểm Nổi Bật

- ✅ **Giao diện thân thiện**: Thiết kế hiện đại, responsive trên mọi thiết bị
- ✅ **Quản lý đa cấp**: Hệ thống phân quyền Admin và Staff
- ✅ **Thanh toán linh hoạt**: Hỗ trợ nhiều hình thức thanh toán
- ✅ **Theo dõi đơn hàng**: Khách hàng có thể dễ dàng theo dõi trạng thái đơn hàng
- ✅ **Danh mục đa dạng**: Phân loại sản phẩm theo loại sân, thương hiệu, độ tuổi
- ✅ **Tìm kiếm thông minh**: Lọc và tìm kiếm sản phẩm nhanh chóng

---

## 🚀 Tính Năng

### 👥 Dành Cho Khách Hàng

#### 🛍️ Mua Sắm
- **Duyệt sản phẩm**: Xem danh sách sản phẩm với hình ảnh, giá cả, giảm giá
- **Tìm kiếm & Lọc**: Tìm kiếm theo tên, lọc theo danh mục, thương hiệu, loại sân
- **Chi tiết sản phẩm**: Xem thông tin đầy đủ, hình ảnh, size, giá
- **Sản phẩm đặc biệt**: Nhãn HOT, NEW, SALE cho các sản phẩm nổi bật

#### 🛒 Giỏ Hàng & Thanh Toán
- **Quản lý giỏ hàng**: Thêm, xóa, cập nhật số lượng và size
- **Giỏ hàng khách**: Hỗ trợ giỏ hàng cho người dùng chưa đăng nhập (session)
- **Giỏ hàng đăng nhập**: Lưu trữ vĩnh viễn trong database, tự động merge khi đăng nhập
- **Thanh toán**: Nhập thông tin giao hàng, chọn phương thức thanh toán
- **Xác nhận đơn hàng**: Hiển thị chi tiết đơn hàng sau khi đặt thành công

#### 📦 Quản Lý Đơn Hàng
- **Theo dõi đơn hàng**: Xem danh sách đơn hàng đã đặt với trạng thái real-time
- **Tìm kiếm đơn hàng**: Tra cứu đơn hàng bằng mã đơn hàng
- **Chi tiết đơn hàng**: Xem thông tin chi tiết từng đơn hàng

#### ❤️ Danh Sách Yêu Thích
- **Lưu sản phẩm yêu thích**: Thêm/xóa sản phẩm vào danh sách yêu thích
- **Quản lý wishlist**: Xem và quản lý các sản phẩm đã lưu

#### 👤 Tài Khoản
- **Đăng ký**: Tạo tài khoản mới với email, mật khẩu, thông tin cá nhân
- **Đăng nhập**: Xác thực người dùng an toàn với session
- **Đăng xuất**: Kết thúc phiên làm việc

### 🔐 Dành Cho Quản Trị Viên (Admin/Staff)

#### 📊 Dashboard
- **Thống kê tổng quan**: Hiển thị tổng số sản phẩm, khách hàng, danh mục, đơn hàng
- **Biểu đồ trực quan**: Cards hiển thị số liệu quan trọng

#### 📦 Quản Lý Sản Phẩm
- **CRUD sản phẩm**: Thêm, sửa, xóa sản phẩm
- **Thông tin sản phẩm**: Tên, giá, size, danh mục, loại giày, số lượng, giảm giá, hình ảnh, loại (HOT/NEW/SALE)
- **Tìm kiếm & Lọc**: Tìm kiếm theo tên, lọc theo danh mục, sắp xếp theo giá/ngày
- **Upload hình ảnh**: Quản lý hình ảnh sản phẩm

#### 📂 Quản Lý Danh Mục
- **CRUD danh mục**: Thêm, sửa, xóa danh mục
- **Danh mục phân cấp**: Hỗ trợ danh mục cha-con (parent_id)
- **Trạng thái**: Active/Inactive

#### 👥 Quản Lý Khách Hàng
- **Xem danh sách**: Hiển thị tất cả khách hàng đã đăng ký
- **Tìm kiếm khách hàng**: Tìm theo tên, email, số điện thoại
- **Sửa/Xóa khách hàng**: Quản lý thông tin khách hàng
- **Thông tin chi tiết**: Tên, email, số điện thoại, địa chỉ, ngày đăng ký

#### 📋 Quản Lý Đơn Hàng
- **Xem danh sách đơn hàng**: Tất cả đơn hàng với thông tin chi tiết
- **Chi tiết đơn hàng**: Xem sản phẩm, số lượng, giá, thông tin khách hàng
- **Cập nhật trạng thái**: Thay đổi trạng thái đơn hàng (Chờ xác nhận, Đang xử lý, Đang giao hàng, Hoàn thành, Đã hủy)
- **Xóa đơn hàng**: Quản lý đơn hàng không hợp lệ

#### 🔐 Phân Quyền
- **Admin**: Toàn quyền truy cập và quản lý hệ thống
- **Staff**: Quyền hạn giới hạn, hỗ trợ quản lý cơ bản

---

## 🛠️ Công Nghệ Sử Dụng

### Backend
| Công Nghệ | Phiên Bản | Mô Tả |
|-----------|-----------|-------|
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white) | 7.4+ | Ngôn ngữ lập trình backend chính |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) | 8.0+ | Hệ quản trị cơ sở dữ liệu |
| ![mysqli](https://img.shields.io/badge/MySQLi-orange?style=flat) | Extension | Thư viện kết nối MySQL |

### Frontend
| Công Nghệ | Phiên Bản | Mô Tả |
|-----------|-----------|-------|
| ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white) | 5 | Cấu trúc giao diện |
| ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white) | 3 | Styling và responsive design |
| ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black) | ES6+ | Tương tác động |
| ![Font Awesome](https://img.shields.io/badge/Font_Awesome-339AF0?style=flat&logo=fontawesome&logoColor=white) | 6.0+ | Icon library |

### Kiến Trúc
- **Pattern**: MVC (Model-View-Controller) tùy chỉnh
- **Session Management**: PHP Session cho authentication
- **Security**: mysqli_real_escape_string, prepared statements

---

## 📁 Cấu Trúc Dự Án

```
SoccerShoeStore/
│
├── 📁 config/                          # Cấu hình hệ thống
│   └── database.php                   # Kết nối database
│
├── 📁 public/                         # Giao diện khách hàng
│   ├── index.php                      # Trang chủ
│   ├── login.php                      # Đăng nhập
│   ├── register.php                   # Đăng ký
│   ├── product-detail.php             # Chi tiết sản phẩm
│   ├── cart.php                       # Giỏ hàng
│   ├── checkout.php                   # Thanh toán
│   ├── order_confirmation.php         # Xác nhận đơn hàng
│   ├── track_order.php                # Theo dõi đơn hàng
│   ├── favorite.php                   # Danh sách yêu thích
│   ├── Giay_Bong_Da.php              # Giày bóng đá
│   ├── Giay_Futsal.php               # Giày Futsal
│   ├── Giay_San_TN.php               # Giày sân tự nhiên
│   ├── Giay_San_NT.php               # Giày sân nhân tạo
│   ├── Giay_Tre_Em.php               # Giày trẻ em
│   ├── Giay_Hot.php                  # Sản phẩm HOT
│   ├── Giay_Sale.php                 # Sản phẩm SALE
│   ├── Hang_Moi_Ve.php               # Hàng mới về
│   ├── get_product_details.php       # API lấy chi tiết sản phẩm
│   ├── logout.php                    # Đăng xuất
│   │
│   ├── 📁 assets/                    # Tài nguyên frontend
│   │   ├── 📁 css/                   # Stylesheet
│   │   │   ├── styles.css           # Style chính
│   │   │   ├── cart.css             # Style giỏ hàng
│   │   │   ├── checkout.css         # Style thanh toán
│   │   │   ├── product-detail.css   # Style chi tiết SP
│   │   │   ├── login.css            # Style đăng nhập
│   │   │   ├── track_order.css      # Style theo dõi
│   │   │   ├── order_confirmation.css
│   │   │   └── Giay_Bong_Da.css     # Style danh mục
│   │   │
│   │   ├── 📁 js/                    # JavaScript files
│   │   │   ├── scripts.js           # Script chính
│   │   │   └── login.js             # Script đăng nhập
│   │   │
│   │   └── 📁 img/                   # Hình ảnh
│   │       ├── 📁 banner/            # Banner slides
│   │       ├── 📁 banner-title/      # Banner danh mục
│   │       ├── 📁 San_TuNhien/       # Hình sản phẩm
│   │       ├── 📁 San_NhanTao/       # Hình sản phẩm
│   │       ├── 📁 table-size/        # Bảng size
│   │       ├── logo.png             # Logo
│   │       └── football-shoes.png   # Favicon
│   │
│   └── 📁 includes/                  # Template components
│       ├── header.php               # Header
│       ├── footer.php               # Footer
│       ├── sidebar.php              # Sidebar
│       └── login-form.php           # Form đăng nhập
│
├── 📁 admin/                         # Trang quản trị
│   ├── index.php                    # Dashboard
│   ├── products.php                 # Quản lý sản phẩm
│   ├── categories.php               # Quản lý danh mục
│   ├── customer.php                 # Quản lý khách hàng
│   ├── orders.php                   # Quản lý đơn hàng
│   │
│   ├── 📁 assets/                   # Tài nguyên admin
│   │   ├── 📁 css/                  # Stylesheet admin
│   │   │   ├── styles_admin.css    # Style chính
│   │   │   ├── index.css           # Style dashboard
│   │   │   ├── products.css        # Style sản phẩm
│   │   │   ├── categories.css      # Style danh mục
│   │   │   ├── customer.css        # Style khách hàng
│   │   │   └── order.css           # Style đơn hàng
│   │   │
│   │   ├── 📁 js/                   # JavaScript admin
│   │   │   └── scripts.js
│   │   │
│   │   └── 📁 img/                  # Hình ảnh admin
│   │       └── ava.jfif            # Avatar mặc định
│   │
│   ├── 📁 template/                 # Template admin
│   │   ├── header.php              # Header admin
│   │   ├── footer.php              # Footer admin
│   │   ├── sidebar.php             # Sidebar menu
│   │   ├── dashboard.php           # Dashboard template
│   │   └── main.php                # Main layout
│   │
│   ├── 📁 XuLy_Products/            # Xử lý sản phẩm
│   │   ├── add.php                 # Thêm sản phẩm
│   │   ├── edit.php                # Sửa sản phẩm
│   │   └── delete.php              # Xóa sản phẩm
│   │
│   ├── 📁 XuLy_Categories/          # Xử lý danh mục
│   │   ├── add.php                 # Thêm danh mục
│   │   ├── edit.php                # Sửa danh mục
│   │   └── delete.php              # Xóa danh mục
│   │
│   ├── 📁 XuLy_Customer/            # Xử lý khách hàng
│   │   ├── edit.php                # Sửa khách hàng
│   │   ├── delete.php              # Xóa khách hàng
│   │   └── seach.php               # Tìm kiếm KH
│   │
│   ├── 📁 XuLy_Order/               # Xử lý đơn hàng
│   │   ├── view.php                # Xem chi tiết
│   │   ├── update_status.php       # Cập nhật trạng thái
│   │   └── delete.php              # Xóa đơn hàng
│   │
│   └── 📁 uploads/                  # Thư mục upload
│
└── soccershoestore.sql              # Database SQL dump
```

---

## 💾 Database Schema

### 📊 Các Bảng Chính

#### 1️⃣ **users** - Thông tin khách hàng
```sql
- id (PK)
- name
- email (UNIQUE)
- password
- phone
- address
- created_at
- updated_at
```

#### 2️⃣ **admins** - Quản trị viên
```sql
- id (PK)
- name
- email (UNIQUE)
- password
- phone
- address
- role (Admin/Staff)
- status (Active/Inactive)
- created_at
- updated_at
```

#### 3️⃣ **products** - Sản phẩm
```sql
- id (PK)
- name
- slug
- price
- discount
- size
- category
- shoe_type
- quantity
- image
- product_type (hot/new/sale/normal)
- created_at
- updated_at
```

#### 4️⃣ **products_admin** - Sản phẩm quản trị
```sql
- id (PK)
- name
- price
- size
- category
- shoe_type
- quantity
- discount
- image
- product_type
- created_at
- updated_at
```

#### 5️⃣ **categories** - Danh mục
```sql
- id (PK)
- name
- slug
- parent_id (FK)
- status (Active/Inactive)
- created_at
- updated_at
```

#### 6️⃣ **brands** - Thương hiệu
```sql
- id (PK)
- name
- slug
- status
- created_at
- updated_at
```

#### 7️⃣ **cart** - Giỏ hàng
```sql
- id (PK)
- user_id (FK)
- created_at
- updated_at
```

#### 8️⃣ **cart_items** - Sản phẩm trong giỏ
```sql
- id (PK)
- cart_id (FK)
- product_id (FK)
- qty
- price
- discount_price
- size
- created_at
- updated_at
```

#### 9️⃣ **orders** - Đơn hàng
```sql
- id (PK)
- user_id (FK)
- total_amount
- payment_method
- status (Chờ xác nhận/Đang xử lý/Đang giao hàng/Hoàn thành/Đã hủy)
- customer_name
- customer_email
- customer_phone
- shipping_address
- created_at
- updated_at
```

#### 🔟 **order_items** - Chi tiết đơn hàng
```sql
- id (PK)
- order_id (FK)
- product_id (FK)
- quantity
- price
- discount_price
- size
- created_at
- updated_at
```

#### 1️⃣1️⃣ **favorites** - Danh sách yêu thích
```sql
- id (PK)
- user_id (FK)
- product_id (FK)
- created_at
```

### 🔗 Quan Hệ Giữa Các Bảng

```
users (1) -----> (N) cart
cart (1) -----> (N) cart_items
products (1) -----> (N) cart_items

users (1) -----> (N) orders
orders (1) -----> (N) order_items
products (1) -----> (N) order_items

users (1) -----> (N) favorites
products (1) -----> (N) favorites

categories (1) -----> (N) categories (parent-child)
```

---

## 🔧 Cài Đặt

### Yêu Cầu Hệ Thống

- **Web Server**: Apache 2.4+ hoặc Nginx
- **PHP**: 7.4 hoặc cao hơn
- **MySQL**: 8.0 hoặc cao hơn
- **phpMyAdmin**: (Khuyến nghị)

### Các Bước Cài Đặt

#### 1️⃣ Clone Repository

```bash
git clone https://github.com/yourusername/soccershoestore.git
cd soccershoestore
```

#### 2️⃣ Cấu Hình Database

**Tạo Database:**
```sql
CREATE DATABASE soccershoestore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Import Database:**
```bash
# Sử dụng phpMyAdmin hoặc command line
mysql -u root -p soccershoestore < soccershoestore.sql
```

**Hoặc qua phpMyAdmin:**
1. Mở phpMyAdmin
2. Tạo database mới: `soccershoestore`
3. Import file `soccershoestore.sql`

#### 3️⃣ Cấu Hình Kết Nối

Mở file `config/database.php` và chỉnh sửa:

```php
<?php
  $db_server = "localhost";      // Địa chỉ MySQL server
  $db_user = "root";             // Username MySQL
  $db_pass = "";                 // Password MySQL (để trống nếu localhost)
  $db_name = "soccershoestore";  // Tên database

  $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
  if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
  }
?>
```

#### 4️⃣ Cấu Hình Virtual Host (Tùy chọn)

**Apache:**
```apache
<VirtualHost *:80>
    ServerName soccershoestore.local
    DocumentRoot "C:/xampp/htdocs/soccershoestore/public"
    <Directory "C:/xampp/htdocs/soccershoestore/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Hosts file** (`C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1 soccershoestore.local
```

#### 5️⃣ Khởi Động Server

**XAMPP/WAMP:**
1. Khởi động Apache và MySQL
2. Truy cập: `http://localhost/soccershoestore/public/`

**PHP Built-in Server** (Development):
```bash
cd public
php -S localhost:8000
```

#### 6️⃣ Kiểm Tra Cài Đặt

- **Trang chủ**: `http://localhost/soccershoestore/public/`
- **Admin**: `http://localhost/soccershoestore/admin/`

---

## 📖 Hướng Dẫn Sử Dụng

### 👤 Dành Cho Khách Hàng

#### 🛍️ Mua Sắm

1. **Duyệt sản phẩm**
   - Truy cập trang chủ để xem các sản phẩm nổi bật
   - Sử dụng menu để chọn danh mục: Giày sân tự nhiên, sân nhân tạo, Futsal, trẻ em
   - Xem các sản phẩm HOT, NEW, SALE

2. **Tìm kiếm sản phẩm**
   - Sử dụng thanh tìm kiếm ở header
   - Lọc theo thương hiệu: Nike, Adidas, Puma, Mizuno, Kamito
   - Lọc theo giá, loại sản phẩm

3. **Xem chi tiết sản phẩm**
   - Click vào sản phẩm để xem thông tin chi tiết
   - Xem hình ảnh, giá, size có sẵn, mô tả
   - Chọn size phù hợp
   - Thêm vào giỏ hàng hoặc danh sách yêu thích

#### 🛒 Giỏ Hàng & Thanh Toán

1. **Quản lý giỏ hàng**
   - Click icon giỏ hàng để xem sản phẩm đã chọn
   - Điều chỉnh số lượng hoặc xóa sản phẩm
   - Xem tổng tiền tạm tính

2. **Đặt hàng**
   - Click "Thanh toán"
   - Đăng nhập hoặc tiếp tục với tư cách khách
   - Nhập thông tin giao hàng: Tên, email, SĐT, địa chỉ
   - Chọn phương thức thanh toán
   - Xác nhận đơn hàng

3. **Theo dõi đơn hàng**
   - Đăng nhập để xem lịch sử đơn hàng
   - Hoặc tra cứu bằng mã đơn hàng
   - Xem trạng thái: Chờ xác nhận → Đang xử lý → Đang giao hàng → Hoàn thành

#### 👤 Quản Lý Tài Khoản

1. **Đăng ký**
   - Click "Đăng ký" ở header
   - Điền thông tin: Tên, email, mật khẩu, SĐT, địa chỉ
   - Xác nhận đăng ký

2. **Đăng nhập**
   - Nhập email và mật khẩu
   - Chọn vai trò: Khách hàng / Admin / Staff
   - Click "Đăng nhập"

3. **Danh sách yêu thích**
   - Click icon trái tim ở sản phẩm để thêm vào yêu thích
   - Vào trang "Yêu thích" để xem tất cả

### 🔐 Dành Cho Quản Trị Viên

#### 📊 Dashboard

1. **Truy cập**
   - Đăng nhập với tài khoản Admin/Staff
   - Tự động chuyển đến `/admin/index.php`

2. **Xem tổng quan**
   - Tổng số sản phẩm
   - Tổng số khách hàng
   - Tổng số danh mục
   - Tổng số đơn hàng

#### 📦 Quản Lý Sản Phẩm

1. **Thêm sản phẩm**
   - Click "Thêm Sản phẩm"
   - Điền thông tin: Tên, giá, size, danh mục, loại giày, số lượng, giảm giá, link hình ảnh, loại sản phẩm
   - Click "Thêm" để lưu

2. **Sửa sản phẩm**
   - Click icon "Sửa" ở sản phẩm muốn chỉnh sửa
   - Cập nhật thông tin
   - Click "Cập nhật"

3. **Xóa sản phẩm**
   - Click icon "Xóa"
   - Xác nhận xóa

4. **Tìm kiếm & Lọc**
   - Nhập tên sản phẩm vào ô tìm kiếm
   - Chọn danh mục
   - Sắp xếp theo: Mới nhất, giá tăng dần, giá giảm dần
   - Click "Lọc"

#### 📂 Quản Lý Danh Mục

1. **Thêm danh mục**
   - Click "Thêm Danh mục"
   - Nhập tên, slug, trạng thái, danh mục cha (nếu có)
   - Click "Thêm"

2. **Sửa/Xóa danh mục**
   - Tương tự như quản lý sản phẩm

#### 👥 Quản Lý Khách Hàng

1. **Xem danh sách**
   - Vào trang "Khách hàng"
   - Xem tất cả thông tin: ID, tên, email, SĐT, địa chỉ, ngày đăng ký

2. **Tìm kiếm**
   - Nhập tên, email hoặc SĐT
   - Click "Tìm kiếm"

3. **Sửa/Xóa khách hàng**
   - Click icon tương ứng
   - Cập nhật hoặc xác nhận xóa

#### 📋 Quản Lý Đơn Hàng

1. **Xem đơn hàng**
   - Vào trang "Đơn hàng"
   - Xem danh sách với mã đơn, khách hàng, tổng tiền, trạng thái, ngày đặt

2. **Xem chi tiết**
   - Click "Xem" để xem sản phẩm trong đơn
   - Xem thông tin giao hàng

3. **Cập nhật trạng thái**
   - Click "Cập nhật trạng thái"
   - Chọn trạng thái mới:
     - Chờ xác nhận
     - Đang xử lý
     - Đang giao hàng
     - Hoàn thành
     - Đã hủy
   - Click "Cập nhật"

4. **Xóa đơn hàng**
   - Click icon "Xóa"
   - Xác nhận

---

## 🎨 Ảnh Giao Diện

### 🏠 Trang Khách Hàng

#### Trang Chủ
![Homepage](docs/images/homepage.png)
*Banner slideshow với các thương hiệu nổi tiếng và bộ sưu tập mới*

#### Danh Sách Sản Phẩm
![Product List](docs/images/product-list.png)
*Hiển thị sản phẩm với hình ảnh, giá, giảm giá và nhãn HOT/NEW/SALE*

#### Chi Tiết Sản Phẩm
![Product Detail](docs/images/product-detail.png)
*Thông tin chi tiết sản phẩm với hình ảnh lớn, mô tả, size và nút thêm giỏ hàng*

#### Giỏ Hàng
![Cart](docs/images/cart.png)
*Quản lý sản phẩm trong giỏ với tính năng cập nhật số lượng và xóa*

#### Thanh Toán
![Checkout](docs/images/checkout.png)
*Form nhập thông tin giao hàng và phương thức thanh toán*

#### Theo Dõi Đơn Hàng
![Order Tracking](docs/images/order-tracking.png)
*Tra cứu và theo dõi trạng thái đơn hàng*

### 🔐 Trang Quản Trị

#### Dashboard
![Admin Dashboard](docs/images/admin-dashboard.png)
*Tổng quan thống kê với cards hiển thị số liệu quan trọng*

#### Quản Lý Sản Phẩm
![Admin Products](docs/images/admin-products.png)
*Bảng danh sách sản phẩm với chức năng CRUD và tìm kiếm*

#### Quản Lý Đơn Hàng
![Admin Orders](docs/images/admin-orders.png)
*Danh sách đơn hàng với khả năng xem chi tiết và cập nhật trạng thái*

> **Lưu ý**: Các ảnh minh họa sẽ được thêm vào thư mục `docs/images/` sau khi chụp màn hình thực tế.

---

## 🔑 Tài Khoản Demo

### 👑 Admin
```
Email: admin@gmail.com
Password: admin
```

### 👔 Staff
```
Email: staff@gmail.com
Password: staff
```

### 👤 Khách Hàng
Bạn có thể tự đăng ký tài khoản mới hoặc sử dụng tài khoản có sẵn trong database.

---

## 🔐 Bảo Mật

### Các Biện Pháp Bảo Mật Hiện Tại

✅ **Session Management**: Sử dụng PHP session để quản lý đăng nhập
✅ **SQL Injection Prevention**: Sử dụng `mysqli_real_escape_string()` và prepared statements
✅ **XSS Prevention**: Sử dụng `htmlspecialchars()` khi hiển thị dữ liệu
✅ **Role-Based Access Control**: Phân quyền Admin/Staff/User

### ⚠️ Khuyến Nghị Cải Thiện

- [ ] Mã hóa mật khẩu bằng `password_hash()` và `password_verify()`
- [ ] Thêm CSRF token cho forms
- [ ] Implement rate limiting cho login
- [ ] Sử dụng HTTPS trong production
- [ ] Thêm validation phía server đầy đủ
- [ ] Implement session timeout
- [ ] Thêm captcha cho form đăng nhập/đăng ký
- [ ] Log các hoạt động nhạy cảm

---

## 🚀 Roadmap & Tính Năng Tương Lai

### Phase 1: Security & Performance
- [ ] Mã hóa mật khẩu với bcrypt
- [ ] Implement CSRF protection
- [ ] Optimize database queries
- [ ] Add caching layer (Redis/Memcached)
- [ ] Minify CSS/JS

### Phase 2: Features Enhancement
- [ ] Đánh giá & bình luận sản phẩm
- [ ] Hệ thống thông báo real-time
- [ ] Tích hợp thanh toán online (VNPay, MoMo, ZaloPay)
- [ ] Email notification cho đơn hàng
- [ ] Xuất hóa đơn PDF
- [ ] Quản lý kho hàng (inventory management)
- [ ] Báo cáo thống kê nâng cao (charts)
- [ ] Chương trình khuyến mãi & mã giảm giá (vouchers)

### Phase 3: Mobile & UX
- [ ] Progressive Web App (PWA)
- [ ] Mobile app (React Native/Flutter)
- [ ] Cải thiện responsive design
- [ ] Tối ưu SEO
- [ ] Tích hợp Google Analytics
- [ ] Live chat support

### Phase 4: Advanced Features
- [ ] AI recommendation system
- [ ] Multi-language support
- [ ] Multi-currency support
- [ ] Social media integration
- [ ] API RESTful cho mobile/third-party
- [ ] GraphQL API
- [ ] Microservices architecture

---

## 🐛 Báo Lỗi & Đóng Góp

### 🐞 Báo Lỗi (Bug Report)

Nếu bạn phát hiện lỗi, vui lòng tạo issue với thông tin sau:

1. **Mô tả lỗi**: Mô tả chi tiết lỗi
2. **Các bước tái hiện**: Làm thế nào để tái hiện lỗi
3. **Kết quả mong đợi**: Điều bạn mong đợi xảy ra
4. **Kết quả thực tế**: Điều thực sự xảy ra
5. **Screenshots**: Ảnh chụp màn hình (nếu có)
6. **Môi trường**: PHP version, MySQL version, OS, Browser

### 🎯 Đề Xuất Tính Năng (Feature Request)

1. **Mô tả tính năng**: Tính năng bạn muốn thêm
2. **Lý do**: Tại sao tính năng này hữu ích
3. **Giải pháp đề xuất**: Cách bạn nghĩ nó nên hoạt động

### 🤝 Đóng Góp Code

#### Fork & Pull Request Workflow

1. **Fork repository**
   ```bash
   # Fork trên GitHub, sau đó clone
   git clone https://github.com/YOUR-USERNAME/soccershoestore.git
   cd soccershoestore
   ```

2. **Tạo branch mới**
   ```bash
   git checkout -b feature/ten-tinh-nang
   # hoặc
   git checkout -b fix/ten-loi
   ```

3. **Commit changes**
   ```bash
   git add .
   git commit -m "feat: thêm tính năng XYZ"
   # hoặc
   git commit -m "fix: sửa lỗi ABC"
   ```

4. **Push & tạo Pull Request**
   ```bash
   git push origin feature/ten-tinh-nang
   ```

#### Coding Standards

- **PHP**: PSR-12 coding standard
- **JavaScript**: ESLint + Airbnb style guide
- **CSS**: BEM methodology
- **Commit messages**: Conventional Commits
  - `feat:` Tính năng mới
  - `fix:` Sửa lỗi
  - `docs:` Cập nhật documentation
  - `style:` Format code
  - `refactor:` Refactor code
  - `test:` Thêm tests
  - `chore:` Các thay đổi khác

---

## 📝 Changelog

### Version 1.0.0 (2025-04-27)

#### ✨ Features
- ✅ Hệ thống đăng nhập/đăng ký
- ✅ Quản lý sản phẩm đầy đủ (CRUD)
- ✅ Giỏ hàng với session và database
- ✅ Thanh toán và xác nhận đơn hàng
- ✅ Theo dõi trạng thái đơn hàng
- ✅ Dashboard admin với thống kê
- ✅ Quản lý danh mục phân cấp
- ✅ Quản lý khách hàng
- ✅ Quản lý đơn hàng với cập nhật trạng thái
- ✅ Danh sách yêu thích
- ✅ Tìm kiếm và lọc sản phẩm
- ✅ Responsive design

#### 🐛 Bug Fixes
- Sửa lỗi merge giỏ hàng khi đăng nhập
- Sửa lỗi hiển thị giá giảm
- Sửa lỗi cập nhật số lượng trong giỏ

#### 📚 Documentation
- Thêm README.md hoàn chỉnh
- Thêm database schema
- Thêm hướng dẫn cài đặt

---

## 📜 License

```
MIT License

Copyright (c) 2025 Soccer Shoe Store

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 👨‍💻 Tác Giả & Đóng Góp

### 👤 Lead Developer
**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com
- LinkedIn: [Your LinkedIn](https://linkedin.com/in/yourprofile)

### 🌟 Contributors

Cảm ơn những người đã đóng góp cho dự án này!

<a href="https://github.com/yourusername/soccershoestore/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=yourusername/soccershoestore" />
</a>

---

## 📞 Liên Hệ & Hỗ Trợ

### 📧 Email Support
- **General**: support@soccershoestore.com
- **Technical**: dev@soccershoestore.com
- **Business**: business@soccershoestore.com

### 💬 Social Media
- **Facebook**: [Soccer Shoe Store](https://facebook.com/soccershoestore)
- **Instagram**: [@soccershoestore](https://instagram.com/soccershoestore)
- **Twitter**: [@soccershoestore](https://twitter.com/soccershoestore)

### 🌐 Website
- **Homepage**: https://soccershoestore.com
- **Blog**: https://blog.soccershoestore.com
- **Docs**: https://docs.soccershoestore.com

### 🔗 Resources
- **Issues**: [GitHub Issues](https://github.com/yourusername/soccershoestore/issues)
- **Discussions**: [GitHub Discussions](https://github.com/yourusername/soccershoestore/discussions)
- **Wiki**: [Project Wiki](https://github.com/yourusername/soccershoestore/wiki)

---

## ⭐ Star History

[![Star History Chart](https://api.star-history.com/svg?repos=yourusername/soccershoestore&type=Date)](https://star-history.com/#yourusername/soccershoestore&Date)

---

## 🙏 Cảm Ơn

Xin chân thành cảm ơn:

- **Font Awesome** - Icon library tuyệt vời
- **PHP Community** - Hỗ trợ và tài liệu
- **MySQL** - Hệ quản trị cơ sở dữ liệu mạnh mẽ
- **Stack Overflow** - Giải đáp thắc mắc
- **GitHub** - Nền tảng lưu trữ code
- **Tất cả contributors** - Đã đóng góp cho dự án

---

<div align="center">

### ⚽ Made with ❤️ for Football Lovers

**Nếu bạn thấy dự án hữu ích, hãy cho một ⭐ trên GitHub!**

[![GitHub Stars](https://img.shields.io/github/stars/yourusername/soccershoestore?style=social)](https://github.com/yourusername/soccershoestore/stargazers)
[![GitHub Forks](https://img.shields.io/github/forks/yourusername/soccershoestore?style=social)](https://github.com/yourusername/soccershoestore/network/members)
[![GitHub Watchers](https://img.shields.io/github/watchers/yourusername/soccershoestore?style=social)](https://github.com/yourusername/soccershoestore/watchers)

---

**[⬆ Về đầu trang](#-soccer-shoe-store---hệ-thống-quản-lý-cửa-hàng-giày-bóng-đá)**

</div>



