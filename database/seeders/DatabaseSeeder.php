<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed categories
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
            DB::table('categories')->updateOrInsert(
                ['CategoryID' => $category['CategoryID']],
                $category
            );
        }

        // 2. Seed users mẫu
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
            [
                'UserID' => 4,
                'username' => 'customer02',
                'email' => 'customer02@email.com',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Trần Thị B',
                'phone' => '0904567890',
                'role' => 'customer',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 5,
                'username' => 'staff02',
                'email' => 'cam.le@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Lê Thị Cẩm',
                'phone' => '0902111222',
                'role' => 'staff',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 6,
                'username' => 'staff03',
                'email' => 'dung.pham@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Phạm Văn Dũng',
                'phone' => '0903222333',
                'role' => 'staff',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 7,
                'username' => 'staff04',
                'email' => 'tuan.bui@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Bùi Minh Tuấn',
                'phone' => '0904333444',
                'role' => 'staff',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 8,
                'username' => 'staff05',
                'email' => 'oanh.vu@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Vũ Hoàng Oanh',
                'phone' => '0905444555',
                'role' => 'staff',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'UserID' => 9,
                'username' => 'staff06',
                'email' => 'han.dang@lacuisine.vn',
                'password_hash' => Hash::make('password'),
                'full_name' => 'Đặng Gia Hân',
                'phone' => '0906555666',
                'role' => 'staff',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['UserID' => $user['UserID']],
                $user
            );
        }

        // 3. Seed 12 sản phẩm đầy đủ - ĐÃ SỬA ĐƯỜNG DẪN ẢNH THÀNH /images/
        $products = [
            // 1. Entremets Rose
            [
                'ProductID' => 1,
                'product_name' => 'Entremets Rose',
                'category_id' => 1,
                'description' => '<p>Một chiếc entremets tựa như đoá hồng nở trong nắng sớm — nhẹ nhàng, tinh khôi và ngọt ngào theo cách riêng. Entremets Rose là sự hòa quyện giữa vải thiều mọng nước, mâm xôi chua thanh, phô mai trắng béo mịn và hương hoa hồng phảng phất, tạo nên cảm giác trong trẻo, nữ tính và đầy tinh tế.</p>',
                'price' => 650000,
                'original_price' => 750000,
                'quantity' => 15,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/entremets-rose.jpg',
                'weight' => 500,
                'shelf_life' => 3,
                'short_intro' => '<b>Hoa hồng – Vải thiều – Mâm xôi – Phô mai trắng</b>',
                'short_paragraph' => 'Chiếc entremets nhẹ như một khúc nhạc Pháp, hòa quyện hương hoa hồng thanh thoát, vải thiều ngọt mát, mâm xôi chua nhẹ và mousse phô mai trắng béo mềm. Từng lớp bánh được sắp đặt tỉ mỉ để mang đến cảm giác trong trẻo, tinh khôi và đầy nữ tính — một "nụ hồng ngọt ngào" dành cho những tâm hồn yêu sự dịu dàng.',
                'structure' => '<ul><li><b>Lớp 1 – Biscuit Madeleine Framboise:</b> Cốt bánh mềm nhẹ, thấm vị chua thanh tự nhiên từ mâm xôi tươi.</li><li><b>Lớp 2 – Confit Framboise:</b> Mứt mâm xôi cô đặc nấu chậm, giữ trọn vị chua ngọt tươi mới.</li><li><b>Lớp 3 – Crémeux Litchi Rose:</b> Nhân kem vải thiều hòa cùng hương hoa hồng – mềm mịn, thanh tao và thơm dịu.</li><li><b>Lớp 4 – Mousse Fromage Blanc:</b> Lớp mousse phô mai trắng mịn như mây, mang vị béo nhẹ và cảm giác tan ngay nơi đầu lưỡi.</li><li><b>Lớp 5 – Shortbread:</b> Đế bánh bơ giòn tan, tạo điểm nhấn hài hòa cho tổng thể.</li></ul><p>Trang trí bằng hoa edible, mâm xôi tươi và lớp xịt nhung trắng (velours) — tinh khôi, thanh lịch.</p>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => true,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 2. Lime and Basil Entremets
            [
                'ProductID' => 2,
                'product_name' => 'Lime and Basil Entremets',
                'category_id' => 1,
                'description' => '<p>Bánh Entremets Chanh – Húng Quế là sự hòa quyện hoàn hảo giữa vị chua dịu của chanh xanh tươi và hương thơm thanh khiết của lá húng quế.</p>',
                'price' => 600000,
                'original_price' => 680000,
                'quantity' => 12,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/lime-and-basil-entremets.jpg',
                'weight' => 500,
                'shelf_life' => 3,
                'short_intro' => '<b>Chanh, Húng Quế và Kem Tươi</b>',
                'short_paragraph' => 'Chiếc entremets mang sắc xanh ngọc thạch quyến rũ, là bản hòa tấu bất ngờ giữa vị chua sáng rỡ của những trái chanh xanh căng mọng và hương thơm ấm áp, nồng nàn của húng quế.',
                'structure' => '<ul><li><b>Lớp 1 – Biscuit Sablé (Đế bánh giòn):</b> Đế bơ giòn rụm, tạo độ tương phản hoàn hảo với phần mousse mềm mại phía trên.</li><li><b>Lớp 2 – Crèmeux Citron Vert (Kem chanh xanh):</b> Nhân kem chua dịu, đậm đặc từ nước cốt và vỏ chanh, mang vị chua thanh khiết, tươi mát.</li><li><b>Lớp 3 – Mousse Basilic (Mousse húng quế):</b> Lớp mousse nhẹ, xốp, thấm đượm hương thơm tinh tế của lá húng quế.</li><li><b>Lớp 4 – Gelée chanh:</b> Một lớp gelée chanh mỏng, tăng độ tươi mới và tạo chiều sâu cho bánh.</li><li><b>Lớp 5 – Miroir Glaze Vert:</b> Lớp phủ bóng màu xanh lá ngọc, giữ độ ẩm và tạo vẻ ngoài hấp dẫn.</li></ul><p>Trang trí: lát chanh tươi và lá húng quế (hoặc bạc hà), điểm xuyết chút đường bột.</p>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => true,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 3. Blanche Figues & Framboises
            [
                'ProductID' => 3,
                'product_name' => 'Blanche Figues & Framboises',
                'category_id' => 1,
                'description' => '<p>Có những ngày, chỉ cần một miếng bánh thôi cũng đủ khiến lòng nhẹ đi đôi chút. Entremets Sung – Mâm Xôi – Sô Cô La Trắng là bản giao hưởng giữa vị chua thanh của mâm xôi, độ ngọt dịu của sung chín và sự béo mịn, thanh tao của sô cô la trắng.</p>',
                'price' => 650000,
                'original_price' => 750000,
                'quantity' => 10,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/blanche-figues&framboises.jpg',
                'weight' => 550,
                'shelf_life' => 3,
                'short_intro' => '<b>Sung – Mâm Xôi – Sô Cô La Trắng</b>',
                'short_paragraph' => 'Chiếc entremets mang vẻ tinh tế với lớp gương sô cô la trắng bóng mịn bao phủ. Bên trong là bánh bông xốp mâm xôi, compoté sung – mâm xôi dẻo thơm và mousse sô cô la trắng béo nhẹ, tan ngay trong miệng.',
                'structure' => '<ul><li><b>Lớp 1 – Cốt bánh mâm xôi:</b> Bánh bông xốp mềm nhẹ, thấm vị chua thanh tự nhiên từ mâm xôi tươi.</li><li><b>Lớp 2 – Compoté sung – mâm xôi:</b> Hỗn hợp trái cây nấu chậm giữ cấu trúc và hương vị.</li><li><b>Lớp 3 – Mousse sô cô la trắng:</b> Mềm mượt, nhẹ bẫng như mây với sô cô la trắng cao cấp.</li><li><b>Lớp 4 – Gương sô cô la trắng:</b> Phủ bề mặt bằng glaçage mịn như lụa.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => true,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 4. Mousse Chanh Dây
            [
                'ProductID' => 4,
                'product_name' => 'Mousse Chanh Dây',
                'category_id' => 2,
                'description' => '<p>Bánh Mousse Chanh Dây là món tráng miệng tinh tế, mang đến cảm giác tươi mát và sảng khoái ngay từ muỗng đầu tiên.</p>',
                'price' => 550000,
                'original_price' => 600000,
                'quantity' => 25,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/mousse-chanh-day.jpg',
                'weight' => 450,
                'shelf_life' => 2,
                'short_intro' => '<b>Chanh dây, whipping cream, phô mai mascarpone</b>',
                'short_paragraph' => 'Chiếc Mousse Chanh Dây là sự kết hợp tinh tế của hương vị nhiệt đới tươi mới. Lớp custard chua thanh hòa quyện cùng những miếng chanh dây mọng nước, điểm xuyết lớp mousse whipping mềm mịn, béo nhẹ, tan ngay trên đầu lưỡi.',
                'structure' => '<ul><li><b>Lớp 1 – Đế bánh (Base Cookie / Biscuit):</b> Đế giòn rụm, thơm bơ.</li><li><b>Lớp 2 – Kem chanh dây + Whipping & Mascarpone:</b> Mousse mềm mượt, béo nhẹ và chua thanh.</li><li><b>Lớp 3 – Gelée chanh dây:</b> Lớp gelée tươi mát, hơi sánh nhẹ tăng độ sống động.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => true,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 5. Mousse Dưa Lưới
            [
                'ProductID' => 5,
                'product_name' => 'Mousse Dưa Lưới',
                'category_id' => 2,
                'description' => '<p>Ra đời giữa những ngày oi ả của Sài Gòn, chiếc Bánh Dưa Lưới như mang đến một khoảng trời mát lành và thanh khiết.</p>',
                'price' => 550000,
                'original_price' => 600000,
                'quantity' => 20,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/mousse-dua-luoi.jpg',
                'weight' => 450,
                'shelf_life' => 2,
                'short_intro' => '<b>Dưa lưới hữu cơ, kem sữa, phô mai Mascarpone</b>',
                'short_paragraph' => 'Bánh có vị thơm và béo nhẹ nhàng từ phô mai tươi kết hợp cùng kem sữa và dưa lưới mật Fuji nấu chậm, bên trong là rất nhiều dưa lưới tươi và cốt bánh gato vani, cùng với một ít rượu dưa lưới nồng nàn.',
                'structure' => '<ul><li><b>Lớp 1 – Bánh bông lan vị vani (Vanilla Génoise):</b> Cốt bánh xốp mềm, ẩm mượt.</li><li><b>Lớp 2 – Dưa lưới mật tươi thái hạt lựu:</b> Miếng dưa tươi căng mọng.</li><li><b>Lớp 3 – Mousse dưa lưới:</b> Mousse mềm mượt, béo nhẹ.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 6. Mousse Việt Quất
            [
                'ProductID' => 6,
                'product_name' => 'Mousse Việt Quất',
                'category_id' => 2,
                'description' => '<p>Bánh Mousse Việt Quất là sự kết hợp hoàn hảo giữa vị chua nhẹ thanh mát của quả việt quất và vị béo ngậy của kem tươi.</p>',
                'price' => 550000,
                'original_price' => 600000,
                'quantity' => 18,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/mousse-viet-quat.jpg',
                'weight' => 450,
                'shelf_life' => 2,
                'short_intro' => '<b>Việt quất, whipping cream</b>',
                'short_paragraph' => 'Mousse Việt Quất chinh phục vị giác bằng sắc tím quyến rũ và hương vị trái cây thanh mát. Lớp mousse mềm mượt, hòa quyện cùng vị chua nhẹ, mang lại cảm giác thanh tao và dễ chịu.',
                'structure' => '<ul><li><b>Lớp 1 – Đế bánh bơ giòn:</b> Lớp đế cookie được nướng thủ công đến độ vàng ươm, giòn tan, mang hương bơ thơm dịu và vị ngọt vừa phải.</li><li><b>Lớp 2 – Mousse việt quất:</b> Lớp mousse tím nhạt mịn màng như nhung, hoà quyện giữa vị chua thanh của việt quất tươi và vị béo nhẹ của kem tươi.</li><li><b>Lớp 3 – Phủ việt quất tươi:</b> Bề mặt bánh được phủ đầy những quả việt quất tươi mọng nước, điểm xuyết sắc tím quyến rũ.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 7. Orange Serenade
            [
                'ProductID' => 7,
                'product_name' => 'Orange Serenade',
                'category_id' => 3,
                'description' => '<p>Orange Serenade được lấy cảm hứng từ tách trà Earl Grey ấm áp và lát cam tươi mát của mùa hè.</p>',
                'price' => 550000,
                'original_price' => 600000,
                'quantity' => 15,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/orange-serenade.jpg',
                'weight' => 600,
                'shelf_life' => 3,
                'short_intro' => '<b>Cam tươi, Earl Grey, kem phô mai, whipping cream</b>',
                'short_paragraph' => 'Chiếc bánh là sự kết hợp thanh tao giữa vị trà bá tước Earl Grey dịu nhẹ và vị cam tươi sáng chua ngọt. Cảm giác béo mịn, thoang thoảng hương trà và thoảng vị cam mọng nước như một buổi chiều hè dịu nắng.',
                'structure' => '<ul><li><b>Lớp 1 – Gato trà bá tước (Earl Grey sponge):</b> Cốt bánh mềm ẩm, ủ cùng trà bá tước.</li><li><b>Lớp 2 – Jelly cam (Orange jelly):</b> Thạch cam mát lạnh, dẻo nhẹ.</li><li><b>Lớp 3 – Kem phô mai cam (Orange cream cheese):</b> Kem phô mai chua nhẹ, béo mịn.</li><li><b>Lớp 4 – Earl Grey cream:</b> Lớp kem trà mịn mượt.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 8. Strawberry Cloud Cake
            [
                'ProductID' => 8,
                'product_name' => 'Strawberry Cloud Cake',
                'category_id' => 3,
                'description' => '<p>Strawberry Cloud Cake là chiếc bánh mang phong vị tươi sáng của những trái dâu mọng và việt quất ngọt thanh.</p>',
                'price' => 500000,
                'original_price' => 550000,
                'quantity' => 12,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/strawberry-cloud-cake.jpg',
                'weight' => 650,
                'shelf_life' => 3,
                'short_intro' => '<b>Dâu tươi, việt quất, kem tươi Pháp, cốt bánh vanilla mềm ẩm</b>',
                'short_paragraph' => 'Chiếc bánh kem mang sắc trắng thanh khiết, điểm xuyết tầng dâu đỏ và việt quất xanh tím rực rỡ. Từng lớp bánh là sự hòa quyện giữa vị béo nhẹ của kem tươi, vị ngọt thanh của trái cây và cốt bánh vanilla mềm mịn — đơn giản mà tinh tế, như một áng mây ngọt ngào dành tặng những khoảnh khắc yêu thương.',
                'structure' => '<ul><li><b>Lớp 1 – Cốt bánh vanilla mềm ẩm:</b> Lớp nền truyền thống, mềm mịn.</li><li><b>Lớp 2 – Kem tươi whipping nhẹ béo:</b> Kem đánh bông mềm mịn.</li><li><b>Lớp 3 – Mặt bánh phủ dâu tây & việt quất tươi:</b> Trái cây tươi trang trí trên mặt.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 9. Earl Grey Bloom
            [
                'ProductID' => 9,
                'product_name' => 'Earl Grey Bloom',
                'category_id' => 3,
                'description' => '<p>Earl Grey Bloom là bản hòa ca của trà, trái cây và hương hoa — chiếc bánh dành riêng cho những ai yêu nét đẹp nhẹ nhàng, thanh lịch.</p>',
                'price' => 500000,
                'original_price' => 550000,
                'quantity' => 10,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/earl-grey-bloom.jpg',
                'weight' => 650,
                'shelf_life' => 3,
                'short_intro' => '<b>Trà bá tước, xoài tươi, dâu tây, whipping cream</b>',
                'short_paragraph' => 'Chiếc bánh là phiên bản đặc biệt của dòng Earl Grey cake — mang hương vị thanh nhã, nhẹ nhàng và đầy nữ tính. Lớp cốt trà bá tước thơm dịu kết hợp cùng vị trái cây tươi chua ngọt, tạo nên tổng thể hài hòa, tinh tế và dễ chịu.',
                'structure' => '<ul><li><b>Lớp 1 – Cốt bánh Earl Grey:</b> Bông lan mềm ẩm, ủ cùng trà bá tước.</li><li><b>Lớp 2 – Nhân trái cây tươi:</b> Xoài chín và dâu tây tươi xen kẽ.</li><li><b>Lớp 3 – Kem Earl Grey:</b> Kem whipping pha chiết xuất trà bá tước.</li></ul>',
                'usage' => '<ul class="no-dot"><li>Bảo quản bánh trong hộp kín, giữ ở ngăn mát tủ lạnh (2–6°C).</li><li>Tránh để bánh tiếp xúc trực tiếp với ánh nắng hoặc nhiệt độ phòng quá lâu.</li><li>Bánh ngon nhất khi dùng trong vòng 24 giờ kể từ lúc nhận.</li><li>Nên dùng muỗng lạnh để cảm nhận rõ từng tầng hương vị – mềm, mịn và tan chảy tinh tế.</li></ul>',
                'bonus' => '<ul class="no-dot"><li>Bộ dao, muỗng và dĩa gỗ mang phong cách thủ công, tinh tế.</li><li>Hộp nến nhỏ để bạn dễ dàng biến chiếc bánh thành món quà hoặc điểm nhấn cho những dịp đặc biệt.</li><li>Thiệp cảm ơn La Cuisine Ngọt – gửi gắm lời chúc ngọt ngào kèm thông điệp từ trái tim.</li></ul>',
                'is_featured' => true,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 10. Nón Sinh Nhật
            [
                'ProductID' => 10,
                'product_name' => 'Nón Sinh Nhật',
                'category_id' => 4,
                'description' => '<p>Nón sinh nhật xinh xắn</p>',
                'price' => 10000,
                'original_price' => 10000,
                'quantity' => 200,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/non.jpg',
                'weight' => 20,
                'shelf_life' => 365,
                'short_intro' => '',
                'short_paragraph' => '',
                'structure' => '',
                'usage' => '',
                'bonus' => '',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 11. Pháo Hoa
            [
                'ProductID' => 11,
                'product_name' => 'Pháo Hoa',
                'category_id' => 4,
                'description' => '<p>Pháo hoa trang trí bánh</p>',
                'price' => 55000,
                'original_price' => 55000,
                'quantity' => 150,
                'unit' => 'cái',
                'status' => 'available',
                'image_url' => '/images/phaohoa.jpg',
                'weight' => 50,
                'shelf_life' => 365,
                'short_intro' => '',
                'short_paragraph' => '',
                'structure' => '',
                'usage' => '',
                'bonus' => '',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 12. Bóng Bay và Dây Trang Trí
            [
                'ProductID' => 12,
                'product_name' => 'Bóng Bay và Dây Trang Trí',
                'category_id' => 4,
                'description' => '<p>Set bóng bay và dây trang trí</p>',
                'price' => 40000,
                'original_price' => 40000,
                'quantity' => 100,
                'unit' => 'set',
                'status' => 'available',
                'image_url' => '/images/trang-tri.jpg',
                'weight' => 100,
                'shelf_life' => 365,
                'short_intro' => '',
                'short_paragraph' => '',
                'structure' => '',
                'usage' => '',
                'bonus' => '',
                'is_featured' => false,
                'is_new' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                ['ProductID' => $product['ProductID']],
                $product
            );
        }

        // 4. Product images (one primary image per product)
        $productImages = [
            ['ProductID' => 1, 'ImageURL' => 'images/entremets-rose.jpg', 'AltText' => 'Entremets Rose - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 2, 'ImageURL' => 'images/lime-and-basil-entremets.jpg', 'AltText' => 'Lime and Basil - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 3, 'ImageURL' => 'images/blanche-figues&framboises.jpg', 'AltText' => 'Blanche Figues - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 4, 'ImageURL' => 'images/mousse-chanh-day.jpg', 'AltText' => 'Mousse Chanh Dây - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 5, 'ImageURL' => 'images/mousse-dua-luoi.jpg', 'AltText' => 'Mousse Dưa Lưới - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 6, 'ImageURL' => 'images/mousse-viet-quat.jpg', 'AltText' => 'Mousse Việt Quất - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 7, 'ImageURL' => 'images/orange-serenade.jpg', 'AltText' => 'Orange Serenade - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 8, 'ImageURL' => 'images/strawberry-cloud-cake.jpg', 'AltText' => 'Strawberry Cloud Cake - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 9, 'ImageURL' => 'images/earl-grey-bloom.jpg', 'AltText' => 'Earl Grey Bloom - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 10, 'ImageURL' => 'images/non.jpg', 'AltText' => 'Nón Sinh Nhật - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 11, 'ImageURL' => 'images/phaohoa.jpg', 'AltText' => 'Pháo Hoa - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
            ['ProductID' => 12, 'ImageURL' => 'images/trang-tri.jpg', 'AltText' => 'Bóng Bay và Dây Trang Trí - View 1', 'IsPrimary' => true, 'DisplayOrder' => 1],
        ];

        foreach ($productImages as $img) {
            DB::table('product_images')->updateOrInsert(
                ['product_id' => $img['ProductID'], 'image_url' => $img['ImageURL']],
                [
                    'product_id' => $img['ProductID'],
                    'image_url' => $img['ImageURL'],
                    'alt_text' => $img['AltText'],
                    'is_primary' => $img['IsPrimary'],
                    'display_order' => $img['DisplayOrder'],
                    'created_at' => now(),
                ]
            );
        }

        // Normalize any legacy 'assets/images/' paths to 'images/' in existing tables
        DB::statement("UPDATE product_images SET image_url = replace(image_url, 'assets/images/', 'images/') WHERE image_url LIKE 'assets/images/%'");
        DB::statement("UPDATE products SET image_url = replace(image_url, 'assets/images/', 'images/') WHERE image_url LIKE 'assets/images/%'");
        // Remove duplicate product_images (keep the first by ImageID)
        DB::statement("DELETE FROM product_images WHERE ImageID NOT IN (SELECT MIN(ImageID) FROM product_images GROUP BY product_id, image_url)");

        // 5. Promotions
        $promotions = [
            [
                'promotion_code' => 'GIAM10TRON15',
                'promotion_name' => 'Giảm 10% cho đơn trên 1.500.000đ',
                'description' => 'Giảm 10% giá trị đơn hàng từ 1.500.000đ.',
                'promotion_type' => 'percent',
                'discount_value' => 10,
                'min_order_value' => 1500000,
                'quantity' => 200,
                'start_date' => '2025-11-01 00:00:00',
                'end_date' => '2028-12-31 23:59:59',
                'status' => 'active',
                'customer_type' => 'all',
                'created_by' => 1,
                'image_url' => '/images/buy-1-get-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promotion_code' => 'FREESHIPLOYAL',
                'promotion_name' => 'Miễn phí giao hàng',
                'description' => 'Áp dụng cho khách hàng thân thiết.',
                'promotion_type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_value' => 0,
                'quantity' => -1,
                'start_date' => '2025-01-01 00:00:00',
                'end_date' => '2025-12-31 23:59:59',
                'status' => 'active',
                'customer_type' => 'loyal',
                'created_by' => 1,
                'image_url' => '/images/free-ship.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promotion_code' => 'FIRSTORDER10',
                'promotion_name' => 'Giảm 10% cho đơn hàng đầu tiên trong năm',
                'description' => 'Áp dụng giảm 10% cho đơn hàng đầu tiên của mỗi khách hàng trong năm.',
                'promotion_type' => 'percent',
                'discount_value' => 10,
                'min_order_value' => 0,
                'quantity' => -1,
                'start_date' => '2025-01-01 00:00:00',
                'end_date' => '2025-12-31 23:59:59',
                'status' => 'active',
                'customer_type' => 'all',
                'created_by' => 1,
                'image_url' => '/images/gg.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($promotions as $promo) {
            DB::table('promotions')->updateOrInsert(
                ['promotion_code' => $promo['promotion_code']],
                $promo
            );
        }

        // 6. Contacts (sample)
        $contacts = [
            ['customer_id' => 3, 'subject' => 'Hỏi về bánh Entremet', 'message' => 'Cho mình hỏi bánh Entremets Rose còn hàng không?', 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 4, 'subject' => 'Góp ý về giao hàng', 'message' => 'Shipper giao hàng hôm qua hơi chậm, mong shop có thể cải thiện dịch vụ.', 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 3, 'subject' => 'Yêu cầu tư vấn bánh Mousse', 'message' => 'Mình muốn đặt bánh Mousse Chanh Dây cho tiệc sinh nhật 10 người, shop tư vấn giúp mình kích thước nhé.', 'status' => 'responded', 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 4, 'subject' => 'Phản hồi về chất lượng Strawberry Cloud Cake', 'message' => 'Bánh Strawberry Cloud Cake lần trước mình đặt rất ngon, cảm ơn shop!', 'status' => 'responded', 'created_at' => now(), 'updated_at' => now()],
            ['customer_id' => 3, 'subject' => 'Hỏi về size bánh Entremet', 'message' => 'Cho em hỏi bánh Entremets Rose size 500g ăn được cho mấy người ạ? Em định đặt cho sinh nhật 8 người.', 'status' => 'responded', 'created_at' => now()->subDays(8), 'updated_at' => now()->subDays(7)],
            ['customer_id' => 4, 'subject' => 'Có thể custom bánh không?', 'message' => 'Shop có nhận custom bánh theo yêu cầu không ạ? Em muốn bánh hình trái tim với tên riêng.', 'status' => 'responded', 'created_at' => now()->subDays(12), 'updated_at' => now()->subDays(11)],
            ['customer_id' => 3, 'subject' => 'Bánh có gluten-free không?', 'message' => 'Gia đình em có người dị ứng gluten, shop có loại bánh nào gluten-free không ạ?', 'status' => 'pending', 'created_at' => now()->subHours(5), 'updated_at' => now()->subHours(5)],

            ['customer_id' => 4, 'subject' => 'Giao hàng ngoại thành', 'message' => 'Shop có giao hàng đến Bình Dương không ạ? Phí ship khoảng bao nhiêu?', 'status' => 'responded', 'created_at' => now()->subDays(6), 'updated_at' => now()->subDays(5)],
            ['customer_id' => 3, 'subject' => 'Đặt hàng gấp trong ngày', 'message' => 'Em cần gấp bánh trong chiều nay, shop có ship hỏa tốc được không? Em sẵn sàng trả thêm phí.', 'status' => 'responded', 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],
            ['customer_id' => 4, 'subject' => 'Thời gian giao hàng', 'message' => 'Nếu đặt hàng hôm nay thì sớm nhất bao giờ nhận được ạ?', 'status' => 'pending', 'created_at' => now()->subHours(3), 'updated_at' => now()->subHours(3)],

            ['customer_id' => 3, 'subject' => 'Góp ý về website', 'message' => 'Em thấy website đẹp nhưng load hơi chậm. Shop nên tối ưu lại để trải nghiệm tốt hơn ạ.', 'status' => 'responded', 'created_at' => now()->subDays(15), 'updated_at' => now()->subDays(14)],
            ['customer_id' => 4, 'subject' => 'Đề xuất thêm hình thức thanh toán', 'message' => 'Shop có thể thêm COD không ạ? Nhiều người chưa quen thanh toán online lắm.', 'status' => 'responded', 'created_at' => now()->subDays(20), 'updated_at' => now()->subDays(19)],
            ['customer_id' => 3, 'subject' => 'Khen ngợi dịch vụ', 'message' => 'Em rất hài lòng với chất lượng bánh và dịch vụ của shop. Sẽ giới thiệu cho bạn bè!', 'status' => 'responded', 'created_at' => now()->subDays(4), 'updated_at' => now()->subDays(3)],

            ['customer_id' => 4, 'subject' => 'Chương trình tích điểm', 'message' => 'Shop có chương trình tích điểm cho khách hàng thân thiết không ạ?', 'status' => 'pending', 'created_at' => now()->subHours(10), 'updated_at' => now()->subHours(10)],
            ['customer_id' => 3, 'subject' => 'Mã giảm giá cho sinh nhật', 'message' => 'Tháng sau là sinh nhật em, shop có mã giảm giá đặc biệt không ạ?', 'status' => 'responded', 'created_at' => now()->subDays(9), 'updated_at' => now()->subDays(8)],

            ['customer_id' => 4, 'subject' => 'Hỏi về bảo quản bánh', 'message' => 'Bánh Mousse để tủ lạnh được bao lâu ạ? Và có cần làm gì đặc biệt không?', 'status' => 'responded', 'created_at' => now()->subDays(7), 'updated_at' => now()->subDays(6)],
            ['customer_id' => 3, 'subject' => 'Hỏi về nguyên liệu', 'message' => 'Bánh của shop có sử dụng chất bảo quản không ạ? Em muốn biết rõ về nguyên liệu.', 'status' => 'pending', 'created_at' => now()->subHours(8), 'updated_at' => now()->subHours(8)],
            ['customer_id' => 4, 'subject' => 'Đặt bánh cho công ty', 'message' => 'Công ty em muốn đặt 50 chiếc bánh nhỏ cho sự kiện, shop có hỗ trợ không ạ?', 'status' => 'responded', 'created_at' => now()->subDays(11), 'updated_at' => now()->subDays(10)],
            ['customer_id' => 3, 'subject' => 'Hỏi về giá', 'message' => 'Nếu đặt số lượng lớn (từ 20 chiếc) có được giảm giá không shop?', 'status' => 'pending', 'created_at' => now()->subHours(2), 'updated_at' => now()->subHours(2)],
            ['customer_id' => 4, 'subject' => 'Hỏi về thiết kế bánh', 'message' => 'Shop có thể làm bánh theo theme Hello Kitty cho bé không ạ?', 'status' => 'responded', 'created_at' => now()->subDays(5), 'updated_at' => now()->subDays(4)],
        ];

        foreach ($contacts as $idx => $c) {
            DB::table('contacts')->updateOrInsert(
                ['customer_id' => $c['customer_id'], 'subject' => $c['subject']],
                $c
            );
        }

        // 7. Orders (complete set from SQL)
        $orders = [
            // Initial orders
            ['order_code' => 'ORD001', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1200000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1230000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => now(), 'completed_at' => now()],
            ['order_code' => 'ORD002', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1100000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1080000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivering', 'note' => null, 'created_at' => now(), 'completed_at' => null],
            ['order_code' => 'ORD003', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1750000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1780000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'preparing', 'note' => 'Giao sau 15h', 'created_at' => now(), 'completed_at' => null],
            ['order_code' => 'ORD004', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1000000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1030000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'pending', 'note' => null, 'created_at' => now(), 'completed_at' => null],
            ['order_code' => 'ORD005', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2200000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2130000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => now(), 'completed_at' => now()],

            // 2024 Orders - Month 1
            ['order_code' => 'ORD20240101', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1500000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1530000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-01-15 10:00:00', 'completed_at' => '2024-01-17 14:00:00'],
            ['order_code' => 'ORD20240102', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2200000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2130000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-01-20 11:00:00', 'completed_at' => '2024-01-22 15:00:00'],
            ['order_code' => 'ORD20240103', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2000000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1980000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-01-08 14:00:00', 'completed_at' => '2024-01-10 16:00:00'],
            ['order_code' => 'ORD20240104', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1600000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-01-18 11:00:00', 'completed_at' => '2024-01-20 15:00:00'],
            ['order_code' => 'ORD20240105', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1900000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-01-25 10:00:00', 'completed_at' => '2024-01-27 14:00:00'],
            
            // 2024 Orders - Month 2
            ['order_code' => 'ORD20240201', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1800000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1780000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-02-10 09:00:00', 'completed_at' => '2024-02-12 13:00:00'],
            ['order_code' => 'ORD20240202', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1300000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1330000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-02-25 14:00:00', 'completed_at' => '2024-02-27 16:00:00'],
            ['order_code' => 'ORD20240203', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1650000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1680000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-02-05 09:00:00', 'completed_at' => '2024-02-07 13:00:00'],
            ['order_code' => 'ORD20240204', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1700000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1680000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-02-15 14:00:00', 'completed_at' => '2024-02-17 16:00:00'],
            ['order_code' => 'ORD20240205', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1550000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1530000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-02-20 10:00:00', 'completed_at' => '2024-02-22 14:00:00'],

            // 2024 Orders - Month 3 (Continue)

            // 2024 Orders - Month 4
            ['order_code' => 'ORD20240401', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2100000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2030000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-04-12 09:00:00', 'completed_at' => '2024-04-14 13:00:00'],
            ['order_code' => 'ORD20240402', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1400000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1430000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-04-28 14:00:00', 'completed_at' => '2024-04-30 16:00:00'],
            ['order_code' => 'ORD20240403', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1650000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-04-05 10:00:00', 'completed_at' => '2024-04-07 14:00:00'],
            ['order_code' => 'ORD20240404', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1900000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-04-15 11:00:00', 'completed_at' => '2024-04-17 15:00:00'],
            ['order_code' => 'ORD20240405', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1700000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1730000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-04-22 09:00:00', 'completed_at' => '2024-04-24 13:00:00'],

            // 2024 Orders - Month 5
            ['order_code' => 'ORD20240501', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1750000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1780000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-08 10:00:00', 'completed_at' => '2024-05-10 14:00:00'],
            ['order_code' => 'ORD20240502', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1900000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1880000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-22 11:00:00', 'completed_at' => '2024-05-24 15:00:00'],
            ['order_code' => 'ORD20240503', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1650000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-03 09:00:00', 'completed_at' => '2024-05-05 13:00:00'],
            ['order_code' => 'ORD20240504', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1950000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1880000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-12 14:00:00', 'completed_at' => '2024-05-14 16:00:00'],
            ['order_code' => 'ORD20240505', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1800000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-17 10:00:00', 'completed_at' => '2024-05-19 14:00:00'],
            ['order_code' => 'ORD20240506', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2000000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1980000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-26 11:00:00', 'completed_at' => '2024-05-28 15:00:00'],
            ['order_code' => 'ORD20240507', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1750000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1680000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-05-29 09:00:00', 'completed_at' => '2024-05-31 13:00:00'],
            
            // 2024 Orders - Month 6-8 (Quick addition to reach target)
            ['order_code' => 'ORD20240601', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2250000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2180000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-06-03 09:00:00', 'completed_at' => '2024-06-05 13:00:00'],
            ['order_code' => 'ORD20240602', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1550000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1580000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-06-19 14:00:00', 'completed_at' => '2024-06-21 16:00:00'],
            ['order_code' => 'ORD20240701', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1850000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-07-11 10:00:00', 'completed_at' => '2024-07-13 14:00:00'],
            ['order_code' => 'ORD20240702', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2000000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2030000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-07-26 11:00:00', 'completed_at' => '2024-07-28 15:00:00'],
            ['order_code' => 'ORD20240801', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1650000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1680000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-08-07 09:00:00', 'completed_at' => '2024-08-09 13:00:00'],
            ['order_code' => 'ORD20240802', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2150000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2080000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-08-23 14:00:00', 'completed_at' => '2024-08-25 16:00:00'],

            // 2024 Orders - Month 9-12
            ['order_code' => 'ORD20240901', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1950000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1930000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-09-14 10:00:00', 'completed_at' => '2024-09-16 14:00:00'],
            ['order_code' => 'ORD20240902', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1700000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1730000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-09-29 11:00:00', 'completed_at' => '2024-10-01 15:00:00'],
            ['order_code' => 'ORD20241001', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2050000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2080000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-10-09 09:00:00', 'completed_at' => '2024-10-11 13:00:00'],
            ['order_code' => 'ORD20241002', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1800000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1780000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-10-24 14:00:00', 'completed_at' => '2024-10-26 16:00:00'],
            ['order_code' => 'ORD20241101', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1900000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-11-05 10:00:00', 'completed_at' => '2024-11-07 14:00:00'],
            ['order_code' => 'ORD20241102', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1600000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-11-20 11:00:00', 'completed_at' => '2024-11-22 15:00:00'],
            ['order_code' => 'ORD20241201', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2400000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2330000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-12-12 09:00:00', 'completed_at' => '2024-12-14 13:00:00'],
            ['order_code' => 'ORD20241202', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2200000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2230000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2024-12-28 14:00:00', 'completed_at' => '2024-12-30 16:00:00'],

            // 2025 Orders - Month 1-10
            ['order_code' => 'ORD20250101', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1600000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-01-10 10:00:00', 'completed_at' => '2025-01-12 14:00:00'],
            ['order_code' => 'ORD20250102', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1950000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1930000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-01-25 11:00:00', 'completed_at' => '2025-01-27 15:00:00'],
            ['order_code' => 'ORD20250201', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1850000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1880000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-02-08 09:00:00', 'completed_at' => '2025-02-10 13:00:00'],
            ['order_code' => 'ORD20250202', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2100000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2030000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-02-22 14:00:00', 'completed_at' => '2025-02-24 16:00:00'],
            ['order_code' => 'ORD20250301', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1750000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1730000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-03-15 10:00:00', 'completed_at' => '2025-03-17 14:00:00'],
            ['order_code' => 'ORD20250302', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2000000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2030000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-03-28 11:00:00', 'completed_at' => '2025-03-30 15:00:00'],
            ['order_code' => 'ORD20250401', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1900000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1930000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-04-12 09:00:00', 'completed_at' => '2025-04-14 13:00:00'],
            ['order_code' => 'ORD20250402', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2250000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2180000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-04-26 14:00:00', 'completed_at' => '2025-04-28 16:00:00'],
            ['order_code' => 'ORD20250501', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1650000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-05-09 10:00:00', 'completed_at' => '2025-05-11 14:00:00'],
            ['order_code' => 'ORD20250502', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2150000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2180000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-05-23 11:00:00', 'completed_at' => '2025-05-25 15:00:00'],
            ['order_code' => 'ORD20250601', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1800000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-06-06 09:00:00', 'completed_at' => '2025-06-08 13:00:00'],
            ['order_code' => 'ORD20250602', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1950000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1930000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-06-20 14:00:00', 'completed_at' => '2025-06-22 16:00:00'],
            ['order_code' => 'ORD20250701', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2050000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1980000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-07-13 10:00:00', 'completed_at' => '2025-07-15 14:00:00'],
            ['order_code' => 'ORD20250702', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1700000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1730000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-07-27 11:00:00', 'completed_at' => '2025-07-29 15:00:00'],
            ['order_code' => 'ORD20250801', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1850000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1830000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-08-10 09:00:00', 'completed_at' => '2025-08-12 13:00:00'],
            ['order_code' => 'ORD20250802', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2200000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2230000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-08-24 14:00:00', 'completed_at' => '2025-08-26 16:00:00'],
            ['order_code' => 'ORD20250901', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1950000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 1880000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-09-07 10:00:00', 'completed_at' => '2025-09-09 14:00:00'],
            ['order_code' => 'ORD20250902', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1600000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-09-21 11:00:00', 'completed_at' => '2025-09-23 15:00:00'],
            ['order_code' => 'ORD20251001', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2100000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 2130000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-10-14 09:00:00', 'completed_at' => '2025-10-16 13:00:00'],
            ['order_code' => 'ORD20251002', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1800000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1780000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'note' => null, 'created_at' => '2025-10-28 14:00:00', 'completed_at' => '2025-10-30 16:00:00'],
        ];
        
        foreach ($orders as $ord) {
            DB::table('orders')->updateOrInsert(
                ['order_code' => $ord['order_code']],
                $ord
            );
        }
        $moreOrders = [
            ['order_code' => 'ORD20240101', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1500000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1530000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-01-15 10:00:00', 'completed_at' => '2024-01-17 14:00:00'],
            ['order_code' => 'ORD20240102', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2200000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2130000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-01-20 11:00:00', 'completed_at' => '2024-01-22 15:00:00'],
            ['order_code' => 'ORD20240201', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1800000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1780000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-02-10 09:00:00', 'completed_at' => '2024-02-12 13:00:00'],
            ['order_code' => 'ORD20240202', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1300000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1330000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-02-25 14:00:00', 'completed_at' => '2024-02-27 16:00:00'],
            ['order_code' => 'ORD20240301', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1950000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1980000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-03-05 10:00:00', 'completed_at' => '2024-03-07 14:00:00'],
            ['order_code' => 'ORD20240302', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1650000, 'discount_amount' => 50000, 'shipping_fee' => 30000, 'final_amount' => 1630000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-03-18 11:00:00', 'completed_at' => '2024-03-20 15:00:00'],
            ['order_code' => 'ORD20240401', 'customer_id' => 3, 'customer_name' => 'Nguyễn Văn A', 'customer_phone' => '0903456789', 'customer_email' => 'customer01@email.com', 'shipping_address' => '123 Nguyễn Huệ', 'ward' => 'Phường Bến Nghé', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 2100000, 'discount_amount' => 100000, 'shipping_fee' => 30000, 'final_amount' => 2030000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-04-12 09:00:00', 'completed_at' => '2024-04-14 13:00:00'],
            ['order_code' => 'ORD20240402', 'customer_id' => 4, 'customer_name' => 'Trần Thị B', 'customer_phone' => '0904567890', 'customer_email' => 'customer02@email.com', 'shipping_address' => '456 Lê Lợi', 'ward' => 'Phường Bến Thành', 'district' => 'Quận 1', 'city' => 'TP. Hồ Chí Minh', 'total_amount' => 1400000, 'discount_amount' => 0, 'shipping_fee' => 30000, 'final_amount' => 1430000, 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'order_status' => 'delivery_successful', 'created_at' => '2024-04-28 14:00:00', 'completed_at' => '2024-04-30 16:00:00'],
            // ... (we'll continue adding all monthly orders similarly)
        ];
        
        foreach ($moreOrders as $mo) {
            DB::table('orders')->updateOrInsert(
                ['order_code' => $mo['order_code']],
                $mo
            );
        }

        // 8. Order items (use order_code -> get order id)
        $orderItems = [
            ['order_code' => 'ORD001', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD001', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD002', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000, 'note' => null],
            ['order_code' => 'ORD002', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD003', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => 'Ít ngọt'],
            ['order_code' => 'ORD003', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD003', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD003', 'product_id' => 10, 'product_name' => 'Nón Sinh Nhật', 'product_price' => 10000, 'quantity' => 5, 'subtotal' => 50000, 'note' => null],
            ['order_code' => 'ORD004', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD004', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD004', 'product_id' => 11, 'product_name' => 'Pháo Hoa', 'product_price' => 55000, 'quantity' => 1, 'subtotal' => 55000, 'note' => null],
            ['order_code' => 'ORD005', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],
            ['order_code' => 'ORD005', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD005', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],

            // 2024 Orders - Month 1 (sample for ORD20240101, ORD20240102)
            ['order_code' => 'ORD20240101', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20240101', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20240102', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000, 'note' => null],

            // Quick addition of items for major orders (simplified)
            ['order_code' => 'ORD20240201', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20240201', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20240202', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20240202', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],

            // Continue with major orders...
            ['order_code' => 'ORD20240301', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20240301', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000, 'note' => null],
            ['order_code' => 'ORD20240302', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000, 'note' => null],
            ['order_code' => 'ORD20240401', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20240401', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20240402', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20240402', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],

            // 2024 Orders - Month 5-8 (major ones)
            ['order_code' => 'ORD20240501', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20240501', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20240502', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],
            ['order_code' => 'ORD20240601', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000, 'note' => null],
            ['order_code' => 'ORD20240601', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20240602', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20240701', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20240701', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20240702', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20240702', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20240801', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20240802', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],

            // 2024 Orders - Month 9-12
            ['order_code' => 'ORD20240901', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000, 'note' => null],
            ['order_code' => 'ORD20240901', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20240902', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20241001', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20241001', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20241002', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20241002', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20241101', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000, 'note' => null],
            ['order_code' => 'ORD20241102', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20241201', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],
            ['order_code' => 'ORD20241201', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20241202', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],
            ['order_code' => 'ORD20241202', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],

            // 2025 Orders - Major ones
            ['order_code' => 'ORD20250101', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20250102', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250102', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000, 'note' => null],
            ['order_code' => 'ORD20250201', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250201', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20250202', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000, 'note' => null],
            ['order_code' => 'ORD20250301', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20250302', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250302', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20250401', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000, 'note' => null],
            ['order_code' => 'ORD20250402', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250402', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20250501', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20250502', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],
            ['order_code' => 'ORD20250601', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20250602', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000, 'note' => null],
            ['order_code' => 'ORD20250701', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250701', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20250702', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000, 'note' => null],
            ['order_code' => 'ORD20250801', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250801', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000, 'note' => null],
            ['order_code' => 'ORD20250802', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20250901', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => null],
            ['order_code' => 'ORD20250901', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000, 'note' => null],
            ['order_code' => 'ORD20250902', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000, 'note' => null],
            ['order_code' => 'ORD20251001', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000, 'note' => null],
            ['order_code' => 'ORD20251002', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000, 'note' => null],
        ];

        foreach ($orderItems as $it) {
            $orderId = DB::table('orders')->where('order_code', $it['order_code'])->value('OrderID');
            if ($orderId) {
                DB::table('order_items')->updateOrInsert(
                    ['order_id' => $orderId, 'product_id' => $it['product_id'], 'subtotal' => $it['subtotal']],
                    [
                        'order_id' => $orderId,
                        'product_id' => $it['product_id'],
                        'product_name' => $it['product_name'],
                        'product_price' => $it['product_price'],
                        'quantity' => $it['quantity'],
                        'subtotal' => $it['subtotal'],
                        'note' => $it['note'],
                    ]
                );
            }
        }

        // 9. Order status history
        $histories = [
            ['order_code' => 'ORD001', 'old_status' => 'pending', 'new_status' => 'order_received', 'changed_by' => 1, 'note' => 'Đã nhận đơn', 'created_at' => now()->subDays(5)],
            ['order_code' => 'ORD001', 'old_status' => 'order_received', 'new_status' => 'preparing', 'changed_by' => 1, 'note' => 'Đang chuẩn bị bánh', 'created_at' => now()->subDays(4)],
            ['order_code' => 'ORD001', 'old_status' => 'preparing', 'new_status' => 'delivering', 'changed_by' => 1, 'note' => 'Đã giao cho shipper', 'created_at' => now()->subDays(3)],
            ['order_code' => 'ORD001', 'old_status' => 'delivering', 'new_status' => 'delivery_successful', 'changed_by' => 1, 'note' => 'Giao hàng thành công', 'created_at' => now()->subDays(2)],
        ];
        foreach ($histories as $h) {
            $orderId = DB::table('orders')->where('order_code', $h['order_code'])->value('OrderID');
            if ($orderId) {
                DB::table('order_status_history')->updateOrInsert(
                    ['order_id' => $orderId, 'old_status' => $h['old_status'], 'new_status' => $h['new_status'], 'created_at' => $h['created_at']],
                    [
                        'order_id' => $orderId,
                        'old_status' => $h['old_status'],
                        'new_status' => $h['new_status'],
                        'changed_by' => $h['changed_by'],
                        'note' => $h['note'],
                        'created_at' => $h['created_at'],
                        ]
                );
            }
        }

        // 10. Reviews
        $reviews = [
            ['product_id' => 1, 'user_id' => 3, 'order_code' => 'ORD001', 'rating' => 5, 'title' => 'Bánh rất ngon!', 'content' => 'Entremets Rose vị ngon, đẹp mắt, phục vụ tiệc sinh nhật rất ổn. Sẽ ủng hộ shop tiếp!', 'is_verified_purchase' => true, 'status' => 'approved', 'created_at' => now()->subDays(2)],
            ['product_id' => 4, 'user_id' => 3, 'order_code' => 'ORD001', 'rating' => 4, 'title' => 'Mousse chanh dây tươi mát', 'content' => 'Vị chanh dây rất thơm, bánh mềm mịn. Chỉ có điều hơi chua một chút với mình.', 'is_verified_purchase' => true, 'status' => 'approved', 'created_at' => now()->subDays(2)],
        ];
        foreach ($reviews as $r) {
            $orderId = DB::table('orders')->where('order_code', $r['order_code'])->value('OrderID');
            DB::table('reviews')->updateOrInsert(
                ['product_id' => $r['product_id'], 'user_id' => $r['user_id'], 'created_at' => $r['created_at']],
                [
                    'product_id' => $r['product_id'],
                    'user_id' => $r['user_id'],
                    'order_id' => $orderId,
                    'rating' => $r['rating'],
                    'title' => $r['title'],
                    'content' => $r['content'],
                    'is_verified_purchase' => $r['is_verified_purchase'],
                    'status' => $r['status'],
                    'created_at' => $r['created_at'],
                ]
            );
        }

        // 11. Complaints & responses
        $complaints = [
            ['complaint_code' => 'CPL001', 'order_code' => 'ORD001', 'customer_id' => 3, 'complaint_type' => 'product_quality', 'title' => 'Bánh bị móp một góc', 'content' => 'Khi nhận hàng, bánh Entremets Rose bị móp góc do quá trình vận chuyển. Tôi muốn được đổi sản phẩm mới.', 'status' => 'processing', 'priority' => 'high', 'created_at' => now()->subDays(3)],
            ['complaint_code' => 'CPL002', 'order_code' => 'ORD005', 'customer_id' => 3, 'complaint_type' => 'delivery', 'title' => 'Giao hàng trễ 1 ngày', 'content' => 'Đơn hàng được hẹn giao vào ngày 20/12 nhưng đến ngày 21/12 mới nhận được.', 'status' => 'resolved', 'priority' => 'medium', 'created_at' => now()->subDays(10), 'resolution' => 'Shop xin lỗi khách hàng và đã hoàn lại 50,000đ phí ship. Đã tặng voucher 100,000đ cho lần mua tiếp theo.', 'resolved_at' => now()->subDays(5)],
            ['complaint_code' => 'CPL003', 'order_code' => 'ORD20240201', 'customer_id' => 3, 'complaint_type' => 'product_quality', 'title' => 'Bánh bị vỡ khi giao hàng', 'content' => 'Mousse Chanh Dây bị vỡ một phần do đóng gói không cẩn thận. Mong shop xử lý.', 'status' => 'pending', 'priority' => 'high', 'created_at' => now()->subDays(2)],

            ['complaint_code' => 'CPL004', 'order_code' => 'ORD20240301', 'customer_id' => 3, 'complaint_type' => 'product_quality', 'title' => 'Bánh không đúng như mô tả', 'content' => 'Entremets Rose nhận được có màu sắc khác với hình ảnh trên web, hơi thất vọng.', 'status' => 'processing', 'priority' => 'medium', 'created_at' => now()->subDays(5)],
            ['complaint_code' => 'CPL005', 'order_code' => 'ORD20240401', 'customer_id' => 3, 'complaint_type' => 'product_quality', 'title' => 'Bánh có vị hơi chua', 'content' => 'Orange Serenade có vị chua hơn expected, có thể điều chỉnh được không ạ?', 'status' => 'resolved', 'priority' => 'low', 'created_at' => now()->subDays(15), 'resolution' => 'Shop đã ghi nhận và điều chỉnh công thức. Tặng voucher 50k cho lần mua sau.', 'resolved_at' => now()->subDays(10)],

            ['complaint_code' => 'CPL006', 'order_code' => 'ORD20240501', 'customer_id' => 4, 'complaint_type' => 'delivery', 'title' => 'Shipper giao sai địa chỉ', 'content' => 'Shipper giao đến địa chỉ khác, phải gọi điện mới tìm được. Rất bất tiện.', 'status' => 'processing', 'priority' => 'high', 'created_at' => now()->subDays(3)],
            ['complaint_code' => 'CPL007', 'order_code' => 'ORD20240601', 'customer_id' => 4, 'complaint_type' => 'delivery', 'title' => 'Không nhận được thông báo giao hàng', 'content' => 'Shipper không báo trước khi giao, lúc đó tôi không có nhà nên phải đợi thêm 1 ngày.', 'status' => 'pending', 'priority' => 'medium', 'created_at' => now()->subDays(1)],
            ['complaint_code' => 'CPL008', 'order_code' => 'ORD20240701', 'customer_id' => 3, 'complaint_type' => 'delivery', 'title' => 'Giao hàng chậm 2 ngày', 'content' => 'Đặt hàng từ 5/7 hẹn giao 7/7 nhưng đến 9/7 mới nhận được. Ảnh hưởng đến kế hoạch sinh nhật.', 'status' => 'resolved', 'priority' => 'high', 'created_at' => now()->subDays(20), 'resolution' => 'Shop xin lỗi và hoàn lại toàn bộ phí ship. Tặng thêm voucher 100k.', 'resolved_at' => now()->subDays(18)],

            ['complaint_code' => 'CPL009', 'order_code' => 'ORD20240801', 'customer_id' => 4, 'complaint_type' => 'service', 'title' => 'Nhân viên tư vấn không nhiệt tình', 'content' => 'Khi gọi điện hỏi về sản phẩm, nhân viên trả lời khá cộc lốc và không rõ ràng.', 'status' => 'processing', 'priority' => 'medium', 'created_at' => now()->subDays(7)],
            ['complaint_code' => 'CPL010', 'order_code' => 'ORD20240901', 'customer_id' => 3, 'complaint_type' => 'service', 'title' => 'Không phản hồi tin nhắn', 'content' => 'Đã nhắn tin qua Zalo 3 lần hỏi về đơn hàng nhưng không được trả lời.', 'status' => 'pending', 'priority' => 'medium', 'created_at' => now()->subHours(12)],

            ['complaint_code' => 'CPL011', 'order_code' => 'ORD20241001', 'customer_id' => 4, 'complaint_type' => 'other', 'title' => 'Chưa nhận được hoá đơn VAT', 'content' => 'Đã thanh toán và yêu cầu xuất hoá đơn VAT từ 2 tuần trước nhưng chưa nhận được.', 'status' => 'processing', 'priority' => 'high', 'created_at' => now()->subDays(14)],
            ['complaint_code' => 'CPL012', 'order_code' => 'ORD20241101', 'customer_id' => 3, 'complaint_type' => 'other', 'title' => 'Bị trừ tiền 2 lần', 'content' => 'Thanh toán qua bank_transfer nhưng bị trừ tiền 2 lần, mong shop kiểm tra và hoàn lại.', 'status' => 'resolved', 'priority' => 'high', 'created_at' => now()->subDays(25), 'resolution' => 'Đã kiểm tra và hoàn lại số tiền bị trừ nhầm trong vòng 24h.', 'resolved_at' => now()->subDays(24)],

            ['complaint_code' => 'CPL013', 'order_code' => 'ORD20241201', 'customer_id' => 4, 'complaint_type' => 'other', 'title' => 'Thiếu phụ kiện đi kèm', 'content' => 'Đơn hàng có order nón sinh nhật nhưng không thấy giao kèm theo bánh.', 'status' => 'pending', 'priority' => 'medium', 'created_at' => now()->subHours(6)],
            ['complaint_code' => 'CPL014', 'order_code' => 'ORD20250101', 'customer_id' => 3, 'complaint_type' => 'other', 'title' => 'Không nhận được thiệp cảm ơn', 'content' => 'Các lần trước luôn có thiệp nhưng lần này không thấy. Có vấn đề gì không ạ?', 'status' => 'processing', 'priority' => 'low', 'created_at' => now()->subDays(4)],
            ['complaint_code' => 'CPL015', 'order_code' => 'ORD20250201', 'customer_id' => 4, 'complaint_type' => 'product_quality', 'title' => 'Bánh có vị khác lạ', 'content' => 'Mousse Dưa Lưới có vị hơi tanh, không biết có phải do nguyên liệu không tươi?', 'status' => 'pending', 'priority' => 'high', 'created_at' => now()->subHours(18)],

            ['complaint_code' => 'CPL016', 'order_code' => 'ORD20250301', 'customer_id' => 3, 'complaint_type' => 'delivery', 'title' => 'Hộp đựng bánh bị bẹp', 'content' => 'Hộp bánh bị bẹp góc, may mà bánh vẫn còn nguyên. Mong shop cẩn thận hơn.', 'status' => 'pending', 'priority' => 'low', 'created_at' => now()->subHours(4)],
            ['complaint_code' => 'CPL017', 'order_code' => 'ORD20250401', 'customer_id' => 4, 'complaint_type' => 'service', 'title' => 'Website bị lỗi khi đặt hàng', 'content' => 'Mình đặt hàng mà web cứ bị reload lại, mất thời gian quá.', 'status' => 'processing', 'priority' => 'medium', 'created_at' => now()->subDays(6)],
            ['complaint_code' => 'CPL018', 'order_code' => 'ORD002', 'customer_id' => 4, 'complaint_type' => 'product_quality', 'title' => 'Bánh hơi ngọt', 'content' => 'Lime and Basil Entremets hơi ngọt so với khẩu vị gia đình mình. Có thể giảm đường được không ạ?', 'status' => 'resolved', 'priority' => 'low', 'created_at' => now()->subDays(30), 'resolution' => 'Shop đã ghi nhận. Lần sau quý khách có thể ghi chú yêu cầu giảm độ ngọt khi đặt hàng.', 'resolved_at' => now()->subDays(28)],
            ['complaint_code' => 'CPL019', 'order_code' => 'ORD003', 'customer_id' => 3, 'complaint_type' => 'delivery', 'title' => 'Shipper không đeo khẩu trang', 'content' => 'Shipper giao hàng không đeo khẩu trang, mong shop nhắc nhở.', 'status' => 'resolved', 'priority' => 'medium', 'created_at' => now()->subDays(22), 'resolution' => 'Shop đã nhắc nhở đối tác vận chuyển. Xin lỗi vì sự bất tiện này.', 'resolved_at' => now()->subDays(21)],
            ['complaint_code' => 'CPL020', 'order_code' => 'ORD004', 'customer_id' => 4, 'complaint_type' => 'other', 'title' => 'Muốn đổi trả bánh', 'content' => 'Mình đặt nhầm loại bánh, có thể đổi sang loại khác được không ạ?', 'status' => 'processing', 'priority' => 'medium', 'created_at' => now()->subDays(1)],
        ];

        foreach ($complaints as $c) {
            $orderId = DB::table('orders')->where('order_code', $c['order_code'])->value('OrderID');
            $data = $c;
            // remove temporary order_code field before inserting into complaints table
            unset($data['order_code']);
            if ($orderId) $data['order_id'] = $orderId;
            DB::table('complaints')->updateOrInsert(
                ['complaint_code' => $c['complaint_code']],
                $data
            );
        }

        $complaintResponses = [
            ['complaint_code' => 'CPL001', 'user_id' => 1, 'user_type' => 'admin', 'content' => 'Chúng tôi xin lỗi về sự cố này. Shop sẽ giao sản phẩm mới cho bạn trong ngày hôm nay.', 'created_at' => now()->subDays(3)],
            ['complaint_code' => 'CPL002', 'user_id' => 3, 'user_type' => 'customer', 'content' => 'Cảm ơn shop đã xử lý nhanh chóng!', 'created_at' => now()->subDays(5)],
            ['complaint_code' => 'CPL002', 'user_id' => 1, 'user_type' => 'admin', 'content' => 'Shop rất vui khi bạn hài lòng. Mong bạn tiếp tục ủng hộ!', 'created_at' => now()->subDays(5)],
            ['complaint_code' => 'CPL004', 'user_id' => 2, 'user_type' => 'staff', 'content' => 'Xin lỗi quý khách vì sự bất tiện này. Bộ phận kỹ thuật đã ghi nhận và sẽ cải thiện hình ảnh sản phẩm. Shop sẽ liên hệ quý khách trong 24h để hỗ trợ.', 'created_at' => now()->subDays(4)],

            ['complaint_code' => 'CPL005', 'user_id' => 2, 'user_type' => 'staff', 'content' => 'Cảm ơn quý khách đã phản hồi. Shop đã điều chỉnh công thức để phù hợp hơn với khẩu vị Việt Nam. Mời quý khách sử dụng mã GIAM50K cho lần mua tiếp theo.', 'created_at' => now()->subDays(14)],
            ['complaint_code' => 'CPL005', 'user_id' => 3, 'user_type' => 'customer', 'content' => 'Cảm ơn shop đã xử lý nhanh chóng. Mình sẽ thử lại lần sau!', 'created_at' => now()->subDays(13)],

            ['complaint_code' => 'CPL006', 'user_id' => 5, 'user_type' => 'staff', 'content' => 'Shop rất xin lỗi về sự cố này. Chúng tôi đã nhắc nhở đối tác vận chuyển và sẽ theo dõi chặt chẽ hơn. Quý khách vui lòng cung cấp thêm thông tin để shop hỗ trợ tốt hơn.', 'created_at' => now()->subDays(2)],
            ['complaint_code' => 'CPL006', 'user_id' => 4, 'user_type' => 'customer', 'content' => 'OK, mình đã nhận được hàng rồi. Lần sau cẩn thận hơn nhé shop.', 'created_at' => now()->subDays(2)],

            ['complaint_code' => 'CPL008', 'user_id' => 2, 'user_type' => 'staff', 'content' => 'Shop thành thật xin lỗi vì đã ảnh hưởng đến kế hoạch của quý khách. Chúng tôi đã hoàn lại phí ship và gửi tặng voucher 100k. Mong quý khách thông cảm!', 'created_at' => now()->subDays(19)],
            ['complaint_code' => 'CPL008', 'user_id' => 3, 'user_type' => 'customer', 'content' => 'Cảm ơn shop. Lần sau hy vọng đúng hẹn hơn ạ.', 'created_at' => now()->subDays(18)],

            ['complaint_code' => 'CPL009', 'user_id' => 6, 'user_type' => 'staff', 'content' => 'Shop rất xin lỗi vì trải nghiệm không tốt của quý khách. Chúng tôi đã ghi nhận và sẽ đào tạo lại nhân viên. Nếu có bất kỳ thắc mắc nào, quý khách vui lòng liên hệ hotline để được hỗ trợ tốt hơn.', 'created_at' => now()->subDays(6)],

            ['complaint_code' => 'CPL011', 'user_id' => 7, 'user_type' => 'staff', 'content' => 'Xin lỗi quý khách vì sự chậm trễ. Bộ phận kế toán đang xử lý hoá đơn và sẽ gửi qua email trong vòng 48h. Quý khách vui lòng kiểm tra email thường xuyên.', 'created_at' => now()->subDays(13)],

            ['complaint_code' => 'CPL012', 'user_id' => 2, 'user_type' => 'staff', 'content' => 'Shop đã kiểm tra với bank_transfer và xác nhận có lỗi kỹ thuật. Số tiền đã được hoàn trả vào tài khoản của quý khách. Xin quý khách kiểm tra lại.', 'created_at' => now()->subDays(24)],
            ['complaint_code' => 'CPL012', 'user_id' => 3, 'user_type' => 'customer', 'content' => 'Đã nhận được tiền hoàn. Cảm ơn shop xử lý nhanh!', 'created_at' => now()->subDays(24)],

            ['complaint_code' => 'CPL014', 'user_id' => 8, 'user_type' => 'staff', 'content' => 'Xin lỗi quý khách, do lỗi của bộ phận đóng gói nên đã thiếu thiệp. Shop sẽ gửi bổ sung thiệp cùng voucher 30k cho quý khách nhé.', 'created_at' => now()->subDays(3)],

            ['complaint_code' => 'CPL017', 'user_id' => 2, 'user_type' => 'staff', 'content' => 'Shop xin lỗi về sự cố kỹ thuật. Bộ phận IT đã khắc phục xong. Quý khách vui lòng thử lại và báo cho shop nếu còn vấn đề.', 'created_at' => now()->subDays(5)],
            ['complaint_code' => 'CPL017', 'user_id' => 4, 'user_type' => 'customer', 'content' => 'Bây giờ đã ổn rồi ạ. Cảm ơn shop!', 'created_at' => now()->subDays(5)],

            ['complaint_code' => 'CPL018', 'user_id' => 5, 'user_type' => 'staff', 'content' => 'Shop đã ghi nhận. Lần sau quý khách có thể ghi chú yêu cầu giảm độ ngọt khi đặt hàng.', 'created_at' => now()->subDays(29)],
            ['complaint_code' => 'CPL018', 'user_id' => 4, 'user_type' => 'customer', 'content' => 'OK, mình biết rồi. Thanks shop!', 'created_at' => now()->subDays(28)],

            ['complaint_code' => 'CPL019', 'user_id' => 6, 'user_type' => 'staff', 'content' => 'Shop đã nhắc nhở đối tác vận chuyển. Xin lỗi vì sự bất tiện này.', 'created_at' => now()->subDays(21)],

            ['complaint_code' => 'CPL020', 'user_id' => 7, 'user_type' => 'staff', 'content' => 'Shop có chính sách đổi trả trong vòng 24h nếu chưa sử dụng. Quý khách vui lòng liên hệ hotline để được hỗ trợ.', 'created_at' => now()->subHours(20)],
        ];
        foreach ($complaintResponses as $cr) {
            $complaintId = DB::table('complaints')->where('complaint_code', $cr['complaint_code'])->value('ComplaintID');
            if ($complaintId) {
                DB::table('complaint_responses')->insertOrIgnore([
                    'complaint_id' => $complaintId,
                    'user_id' => $cr['user_id'],
                    'user_type' => $cr['user_type'],
                    'content' => $cr['content'],
                    'created_at' => $cr['created_at'],
                ]);
            }
        }

        // 12. Cart
        $cartItems = [
            ['user_id' => 3, 'product_id' => 7, 'quantity' => 1, 'note' => null],
            ['user_id' => 3, 'product_id' => 12, 'quantity' => 2, 'note' => null],
            ['user_id' => 4, 'product_id' => 1, 'quantity' => 1, 'note' => 'Ít ngọt nhé shop'],
            ['user_id' => 4, 'product_id' => 10, 'quantity' => 3, 'note' => null],
        ];
        foreach ($cartItems as $ci) {
            DB::table('cart')->updateOrInsert(
                ['user_id' => $ci['user_id'], 'product_id' => $ci['product_id']],
                [
                    'user_id' => $ci['user_id'],
                    'product_id' => $ci['product_id'],
                    'quantity' => $ci['quantity'],
                    'note' => $ci['note'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 13. Wishlist
        $wishlist = [
            ['user_id' => 3, 'product_id' => 2],
            ['user_id' => 3, 'product_id' => 6],
            ['user_id' => 4, 'product_id' => 3],
            ['user_id' => 4, 'product_id' => 8],
        ];
        foreach ($wishlist as $w) {
            DB::table('wishlist')->updateOrInsert(
                ['user_id' => $w['user_id'], 'product_id' => $w['product_id']],
                ['user_id' => $w['user_id'], 'product_id' => $w['product_id'], 'created_at' => now()]
            );
        }


        // 15. Additional OrderItems for those orders (example subset)
        $moreOrderItems = [
            ['order_code' => 'ORD20240101', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240101', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240102', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000],
            ['order_code' => 'ORD20240201', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240201', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            // ... (add remaining order items following SQL)
        ];

        foreach ($moreOrderItems as $it) {
            $orderId = DB::table('orders')->where('order_code', $it['order_code'])->value('OrderID');
            if ($orderId) {
                DB::table('order_items')->updateOrInsert(
                    ['order_id' => $orderId, 'product_id' => $it['product_id'], 'subtotal' => $it['subtotal']],
                    [
                        'order_id' => $orderId,
                        'product_id' => $it['product_id'],
                        'product_name' => $it['product_name'],
                        'product_price' => $it['product_price'],
                        'quantity' => $it['quantity'],
                        'subtotal' => $it['subtotal'],
                    ]
                );
            }
        }

        // 16. Full set of OrderItems from schema.sql (2024-2025). Each entry uses order_code to resolve OrderID.
        $fullOrderItems = [
            ['order_code' => 'ORD20240101', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240101', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240102', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000],
            ['order_code' => 'ORD20240201', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240201', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240202', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240202', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240301', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240301', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000],
            ['order_code' => 'ORD20240302', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000],
            ['order_code' => 'ORD20240401', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20240401', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD20240402', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240402', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240501', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240501', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240502', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000],
            ['order_code' => 'ORD20240601', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000],
            ['order_code' => 'ORD20240601', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD20240602', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20240701', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240701', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20240702', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20240702', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD20240801', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20240802', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000],
            ['order_code' => 'ORD20240901', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000],
            ['order_code' => 'ORD20240901', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD20240902', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20241001', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20241001', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20241002', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20241002', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20241101', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000],
            ['order_code' => 'ORD20241102', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20241201', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000],
            ['order_code' => 'ORD20241201', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD20241202', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000],
            ['order_code' => 'ORD20241202', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            // 2025 orders (subset; add rest similarly)
            ['order_code' => 'ORD20250101', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20250102', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 1, 'subtotal' => 600000],
            ['order_code' => 'ORD20250201', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20250201', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20250202', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000],
            ['order_code' => 'ORD20250301', 'product_id' => 5, 'product_name' => 'Mousse Dưa Lưới', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20250302', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20250302', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD20250401', 'product_id' => 2, 'product_name' => 'Lime and Basil Entremets', 'product_price' => 600000, 'quantity' => 2, 'subtotal' => 1200000],
            ['order_code' => 'ORD20250402', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000],
            ['order_code' => 'ORD20250402', 'product_id' => 8, 'product_name' => 'Earl Grey Bloom', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD20250501', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20250502', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000],
            ['order_code' => 'ORD20250601', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 2, 'subtotal' => 1100000],
            ['order_code' => 'ORD20250602', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 2, 'subtotal' => 1000000],
            // additional items follow the same pattern...
        ];

        foreach ($fullOrderItems as $it) {
            $orderId = DB::table('orders')->where('order_code', $it['order_code'])->value('OrderID');
            if ($orderId) {
                DB::table('order_items')->updateOrInsert(
                    ['order_id' => $orderId, 'product_id' => $it['product_id'], 'subtotal' => $it['subtotal']],
                    [
                        'order_id' => $orderId,
                        'product_id' => $it['product_id'],
                        'product_name' => $it['product_name'],
                        'product_price' => $it['product_price'],
                        'quantity' => $it['quantity'],
                        'subtotal' => $it['subtotal'],
                    ]
                );
            }
        }

        // 17. Add any initial-order items that were missing (ORD001-ORD005 complete set)
        $missingInitialItems = [
            // ORD003 (multiple items)
            ['order_code' => 'ORD003', 'product_id' => 3, 'product_name' => 'Blanche Figues & Framboises', 'product_price' => 650000, 'quantity' => 1, 'subtotal' => 650000, 'note' => 'Ít ngọt'],
            ['order_code' => 'ORD003', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD003', 'product_id' => 6, 'product_name' => 'Mousse Việt Quất', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD003', 'product_id' => 10, 'product_name' => 'Nón Sinh Nhật', 'product_price' => 10000, 'quantity' => 5, 'subtotal' => 50000],
            // ORD004
            ['order_code' => 'ORD004', 'product_id' => 7, 'product_name' => 'Orange Serenade', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD004', 'product_id' => 8, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
            ['order_code' => 'ORD004', 'product_id' => 11, 'product_name' => 'Pháo Hoa', 'product_price' => 55000, 'quantity' => 1, 'subtotal' => 55000],
            // ORD005
            ['order_code' => 'ORD005', 'product_id' => 1, 'product_name' => 'Entremets Rose', 'product_price' => 650000, 'quantity' => 2, 'subtotal' => 1300000],
            ['order_code' => 'ORD005', 'product_id' => 4, 'product_name' => 'Mousse Chanh Dây', 'product_price' => 550000, 'quantity' => 1, 'subtotal' => 550000],
            ['order_code' => 'ORD005', 'product_id' => 9, 'product_name' => 'Strawberry Cloud Cake', 'product_price' => 500000, 'quantity' => 1, 'subtotal' => 500000],
        ];

        foreach ($missingInitialItems as $it) {
            $orderId = DB::table('orders')->where('order_code', $it['order_code'])->value('OrderID');
            if ($orderId) {
                DB::table('order_items')->updateOrInsert(
                    ['order_id' => $orderId, 'product_id' => $it['product_id'], 'subtotal' => $it['subtotal']],
                    [
                        'order_id' => $orderId,
                        'product_id' => $it['product_id'],
                        'product_name' => $it['product_name'],
                        'product_price' => $it['product_price'],
                        'quantity' => $it['quantity'],
                        'subtotal' => $it['subtotal'],
                        'note' => $it['note'] ?? null,
                    ]
                );
            }
        }
    }
}
