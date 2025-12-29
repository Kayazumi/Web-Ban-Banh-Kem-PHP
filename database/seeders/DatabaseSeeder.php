<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed categories
        $categories = [
            [
                'CategoryID' => 1,
                'category_name' => 'Entremet',
                'description' => 'Bánh entremet cao cấp với nhiều lớp hương vị tinh tế',
                'slug' => 'entremet',
                'is_active' => true,
                'display_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'CategoryID' => 2,
                'category_name' => 'Mousse',
                'description' => 'Bánh mousse mềm mịn, nhẹ nhàng',
                'slug' => 'mousse',
                'is_active' => true,
                'display_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'CategoryID' => 3,
                'category_name' => 'Truyền thống',
                'description' => 'Bánh truyền thống Việt Nam',
                'slug' => 'truyen-thong',
                'is_active' => true,
                'display_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'CategoryID' => 4,
                'category_name' => 'Phụ kiện',
                'description' => 'Các phụ kiện trang trí bánh',
                'slug' => 'phu-kien',
                'is_active' => true,
                'display_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }

        // Seed users
        $users = [
            [
                'UserID' => 1,
                'username' => 'admin',
                'email' => 'admin@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Quản trị viên',
                'phone' => '0901234567',
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 2,
                'username' => 'staff01',
                'email' => 'staff01@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Nhân viên 1',
                'phone' => '0902345678',
                'role' => 'staff',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 3,
                'username' => 'customer01',
                'email' => 'customer01@email.com',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Nguyễn Văn A',
                'phone' => '0903456789',
                'role' => 'customer',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        // Seed sample products
        $products = [
            [
                'ProductID' => 1,
                'product_name' => 'Entremets Rose',
                'category_id' => 1,
                'description' => '<p>Một chiếc entremets tựa như đoá hồng nở trong nắng sớm — nhẹ nhàng, tinh khôi và ngọt ngào theo cách riêng.</p>',
                'price' => 650000,
                'original_price' => 750000,
                'quantity' => 15,
                'status' => 'available',
                'image_url' => 'assets/images/entremets-rose.jpg',
                'weight' => 500,
                'shelf_life' => 3,
                'is_featured' => true,
                'short_intro' => '<b>Hoa hồng – Vải thiều – Mâm xôi – Phô mai trắng</b>',
                'short_paragraph' => 'Chiếc entremets nhẹ như một khúc nhạc Pháp, hòa quyện hương hoa hồng thanh thoát, vải thiều ngọt mát, mâm xôi chua nhẹ và mousse phô mai trắng béo mềm.',
                'structure' => '<ul><li><b>Lớp 1 – Biscuit Madeleine Framboise:</b> Cốt bánh mềm nhẹ, thấm vị chua thanh tự nhiên từ mâm xôi tươi.</li><li><b>Lớp 2 – Confit Framboise:</b> Mứt mâm xôi cô đặc nấu chậm, giữ trọn vị chua ngọt tươi mới.</li><li><b>Lớp 3 – Crémeux Litchi Rose:</b> Nhân kem vải thiều hòa cùng hương hoa hồng – mềm mịn, thanh tao và thơm dịu.</li><li><b>Lớp 4 – Mousse Fromage Blanc:</b> Lớp mousse phô mai trắng mịn như mây, mang vị béo nhẹ và cảm giác tan ngay nơi đầu lưỡi.</li><li><b>Lớp 5 – Shortbread:</b> Đế bánh bơ giòn tan, tạo điểm nhấn hài hòa cho tổng thể.</li></ul><p>Trang trí bằng hoa edible, mâm xôi tươi và lớp xịt nhung trắng (velours) — tinh khôi, thanh lịch.</p>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ProductID' => 2,
                'product_name' => 'Lime and Basil Entremets',
                'category_id' => 1,
                'description' => '<p>Bánh Entremets Chanh – Húng Quế là sự hòa quyện hoàn hảo giữa vị chua dịu của chanh xanh tươi và hương thơm thanh khiết của lá húng quế.</p>',
                'price' => 600000,
                'original_price' => 680000,
                'quantity' => 12,
                'status' => 'available',
                'image_url' => 'assets/images/lime-and-basil-entremets.jpg',
                'weight' => 500,
                'shelf_life' => 3,
                'is_featured' => true,
                'short_intro' => '<b>Chanh, Húng Quế và Kem Tươi</b>',
                'short_paragraph' => 'Chiếc entremets mang sắc xanh ngọc thạch quyến rũ, là bản hòa tấu bất ngờ giữa vị chua sáng rỡ của những trái chanh xanh căng mọng và hương thơm ấm áp, nồng nàn của húng quế.',
                'structure' => '<ul><li><b>Lớp 1 – Biscuit Sablé (Đế bánh giòn):</b> Đế bơ giòn rụm, tạo độ tương phản hoàn hảo với phần mousse mềm mại phía trên.</li><li><b>Lớp 2 – Crèmeux Citron Vert (Kem chanh xanh):</b> Nhân kem chua dịu, đậm đặc từ nước cốt và vỏ chanh, mang vị chua thanh khiết, tươi mát.</li><li><b>Lớp 3 – Mousse Basilic (Mousse húng quế):</b> Lớp mousse nhẹ, xốp, thấm đượm hương thơm tinh tế của lá húng quế.</li><li><b>Lớp 4 – Gelée chanh:</b> Một lớp gelée chanh mỏng, tăng độ tươi mới và tạo chiều sâu cho bánh.</li><li><b>Lớp 5 – Miroir Glaze Vert:</b> Lớp phủ bóng màu xanh lá ngọc, giữ độ ẩm và tạo vẻ ngoài hấp dẫn.</li></ul><p>Trang trí: lát chanh tươi và lá húng quế (hoặc bạc hà), điểm xuyết chút đường bột.</p>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
