# La Cuisine Ngọt — Tài liệu dự án

Phiên bản Laravel: **Laravel 12** (PHP 8.2+).

LƯU Ý: Tệp này là tài liệu tổng quan cho dự án `Website-Ban-Banh-Kem-PHP_Laravel` — mô tả cấu trúc, tính năng và hướng dẫn cài đặt nhanh.

Giới thiệu
------------
La Cuisine Ngọt là một ứng dụng thương mại điện tử mẫu dành cho tiệm bánh cao cấp — được xây dựng bằng Laravel và tối ưu cho việc quản lý sản phẩm, đơn hàng và trải nghiệm người dùng. Mục tiêu của repo này là:
- Cung cấp một nền tảng quản trị (Admin) để CRUD sản phẩm, quản lý đơn hàng và người dùng.
- Hỗ trợ đầy đủ luồng mua hàng: duyệt sản phẩm, giỏ hàng, checkout, theo dõi trạng thái đơn.
- Triển khai các tính năng thực tế như khuyến mãi, upload ảnh, khiếu nại và đa vai trò (customer / staff / admin).
Ứng dụng được thiết kế để dễ mở rộng (API-first), dễ tích hợp với các dịch vụ bên thứ ba (gateway thanh toán, vector search/AI, CDN) và phù hợp làm demo hoặc khung để phát triển tiếp.

Tính năng chính
-----------------
- Đăng ký / đăng nhập / xác thực (session-based)
- Xem danh sách sản phẩm, bộ lọc tìm kiếm, trang chi tiết sản phẩm
- Giỏ hàng: thêm / cập nhật / xóa / thanh toán (tạo đơn)
- Quản lý đơn hàng: tạo, cập nhật trạng thái, lịch sử trạng thái
- Admin panel: CRUD sản phẩm, xem đơn, quản lý người dùng
- Upload ảnh sản phẩm, lưu trữ public (Laravel Storage)
- RESTful API cho frontend và SPA nếu cần

Cấu trúc dự án (chính)
-----------------------
Dưới đây là mô tả đầy đủ, có cấu trúc cây và giải thích cho từng thư mục/tệp quan trọng trong dự án `Website-Ban-Banh-Kem-PHP_Laravel`.

Cấu trúc (tóm tắt)
```
Nhom1_Ca4_CNPM_Laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   ├── OrderController.php
│   │   │   └── Admin/ (ProductController.php, OrderController.php, UserController.php)
│   │   ├── Middleware/
│   │   └── Requests/ (nếu có)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Cart.php
│   │   ├── Wishlist.php
│   │   ├── Review.php
│   │   └── ... (Promotion, Complaint, Contact, etc.)
│   └── Providers/
├── bootstrap/
├── config/ (app.php, auth.php, database.php, filesystems.php, etc.)
├── database/
│   ├── migrations/
│   ├── seeders/ (DatabaseSeeder.php)
│   └── database.sqlite (nếu dùng sqlite)
├── public/
│   ├── index.php
│   └── storage/ (link đến storage/app/public)
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── admin.blade.php
│   │   ├── auth/ (login/register)
│   │   ├── home.blade.php
│   │   ├── products.blade.php
│   │   ├── product-detail.blade.php
│   │   ├── cart.blade.php
│   │   └── admin/ (dashboard, products, orders, users)
│   ├── css/ (app.css)
│   └── js/ (app.js, bootstrap.js)
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   ├── app/public/products
│   ├── framework/
│   └── logs/
├── tests/
├── vendor/
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

Giải thích chi tiết từng phần

- `app/`
  - `Http/Controllers/`: chứa tất cả controller xử lý request cho web + API.  
    - `AuthController.php`: đăng nhập/đăng ký/logout, trả JSON cho front-end.  
    - `ProductController.php`, `CartController.php`, `OrderController.php`: logic nghiệp vụ chính.  
    - `Admin/`: controller chuyên cho admin (CRUD sản phẩm, thay đổi trạng thái đơn, quản lý người dùng).
  - `Models/`: chứa Eloquent models, định nghĩa quan hệ (belongsTo, hasMany) và accessor/cast.
  - `Middleware/`: middleware tùy chỉnh (ví dụ `AdminMiddleware`).

- `config/`: cấu hình Laravel (DB, mail, filesystems). Chỉnh `.env` để thay đổi môi trường.

- `database/`
  - `migrations/`: migration tương ứng với schema gốc (Users, Products, Orders, OrderItems, Cart, Reviews, Complaints, …).
  - `seeders/`: seed dữ liệu mẫu (users, categories, products, orders).

- `resources/views/`
  - `layouts/app.blade.php`: layout chính (navigation, footer, load Vite assets).
  - `layouts/admin.blade.php`: layout admin.
  - Các view tương ứng các trang frontend (`home`, `products`, `product-detail`, `cart`, `orders`, `profile`) và admin pages.

- `resources/css` & `resources/js`: source assets dùng Vite để compile. `app.css` + `app.js` giữ logic giao diện và helpers (formatPrice, ajax helpers).

- `routes/`
  - `web.php`: routes web (Blade views), middleware `auth`, route group `admin`.
  - `api.php`: routes RESTful API cho products, cart, orders, auth; có group `admin` dùng middleware `auth` + `admin`.

- `public/`
  - Entry point `index.php`, assets build output khi chạy `npm run build`, `public/storage` là symlink tới `storage/app/public`.

- `storage/`
  - `app/public/products` chứa ảnh upload; dùng `php artisan storage:link` để link ra `public/storage`.

- `composer.json` & `package.json`
  - Liệt kê dependency backend (Laravel, packages) và frontend (Vite, Tailwind/Lib nếu có).

- `vite.config.js` & `package.json` scripts
  - Dev: `npm run dev` — live reload; Prod: `npm run build`.

- `tests/`: file unit/feature test (nếu cần phát triển thêm).

Các file/feature quan trọng cần biết
- `app/Models/User.php` — custom primary key (`UserID`) và `getAuthPassword()` để dùng `password_hash`.
- `database/schema.sql` — schema gốc (tham khảo khi cần đối chiếu migration).
- `routes/api.php` — nơi API frontend tương tác; admin API group ở đây.
- `resources/views/layouts/app.blade.php` — chỗ đặt `window.Laravel` (csrf, user, api routes).
- `storage/app/public/products` — nơi lưu ảnh sản phẩm; đảm bảo đã chạy `php artisan storage:link`.


Cài đặt & chạy (local)
------------------------
Yêu cầu: PHP 8.2+, Composer, Node.js, npm, SQLite hoặc MySQL

1. Clone project:
```bash
git clone <repo> Website-Ban-Banh-Kem-PHP
cd Website-Ban-Banh-Kem-PHP
```

2. Cài phụ thuộc:
```bash
composer install
npm install
```

3. Cấu hình môi trường:
```bash
cp .env.example .env
php artisan key:generate
# chỉnh DB trong .env (sqlite hoặc mysql)
```

4. Migrate và seed dữ liệu mẫu:
```bash
php artisan migrate
php artisan db:seed
```

5. Tạo storage link:
```bash
php artisan storage:link
```

6. Biên dịch assets (dev / production):
```bash
npm run dev    # dev mode
npm run build  # production
```

7. Chạy server:
```bash
php artisan serve
# truy cập http://127.0.0.1:8000
```

Quick start (test tài khoản)
-----------------------------
- Admin: `admin` / `password`
- Staff: `staff01` / `password`
- Customer: `customer01` / `password`

API endpoints chính
---------------------
Authentication
- `POST /api/login` — body: { username, password }
- `POST /api/register` — body: { username, email, password, password_confirmation, full_name }
- `POST /api/logout`
- `GET /api/user`

Products
- `GET /api/products` — filter: search, category, featured, price_min, price_max
- `GET /api/products/{id}`
- `GET /api/products/featured`
- `GET /api/products/search?q=...`
- `GET /api/categories`

Cart (login required)
- `GET /api/cart`
- `POST /api/cart` — body: { product_id, quantity, note? }
- `PUT /api/cart/{id}` — update quantity
- `DELETE /api/cart/{id}`
- `DELETE /api/cart` — clear cart

Orders (login required)
- `GET /api/orders`
- `POST /api/orders` — checkout from cart, body includes shipping info, payment method
- `GET /api/orders/{id}`
- `POST /api/orders/{id}/cancel`

Admin (auth + admin middleware)
- `GET/POST/PUT/DELETE /api/admin/products` — CRUD sản phẩm
- `GET /api/admin/orders` — danh sách orders
- `PUT /api/admin/orders/{id}/status` — cập nhật trạng thái
- `GET /api/admin/users` — quản lý user
- `PUT /api/admin/users/{id}/status` — thay đổi trạng thái user

Admin Panel
------------
- Đường dẫn web: `/admin/dashboard` (cần login admin)
- Chức năng: thống kê, danh sách sản phẩm, CRUD sản phẩm (upload ảnh), danh sách đơn, quản lý user

Upload / Storage
-----------------
- Ảnh sản phẩm lưu trên disk `public` (storage/app/public/products)
- Dùng `php artisan storage:link` để tạo liên kết `public/storage`
- Khi upload, validate loại file (image), kích thước, và sử dụng `Storage::putFile()` để lưu

Testing & seed data
-------------------
- Seeder cơ bản đã tạo dữ liệu mẫu (users, categories, products, orders)
- Chạy `php artisan db:seed` để seed dữ liệu

Troubleshooting
---------------
- Nếu lỗi DB connection: kiểm tra `.env` (DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Nếu không thấy ảnh: chạy `php artisan storage:link`
- Assets không load: chạy `npm run dev` hoặc `npm run build`
- Clear cache config: `php artisan config:clear && php artisan cache:clear`

Ghi chú & phát triển thêm
-------------------------
- Tích hợp gateway thanh toán (VNPay) cho checkout\n+- Gửi email thông báo (order status)\n+- Viết unit / feature tests\n+- Dockerize production deployment\n+
--------------------------------------------------------------------------------
