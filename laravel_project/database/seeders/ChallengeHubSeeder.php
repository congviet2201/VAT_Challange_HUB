<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChallengeHubSeeder extends Seeder
{
    public function run(): void
    {
        // Insert Admin User - Duy nhất tài khoản admin cho hệ thống
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'role' => 'admin',
                'is_active' => 1,
            ],
            // UserAdmin Accounts - Để quản lý nhóm
            [
                'name' => 'Trưởng Nhóm A',
                'email' => 'useradmin1@gmail.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'role' => 'useradmin',
                'is_active' => 1,
            ],
            [
                'name' => 'Trưởng Nhóm B',
                'email' => 'useradmin2@gmail.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'role' => 'useradmin',
                'is_active' => 1,
            ],
            // Regular Users
            [
                'name' => 'Người Dùng 1',
                'email' => 'user1@gmail.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'role' => 'user',
                'is_active' => 1,
            ],
            [
                'name' => 'Người Dùng 2',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'role' => 'user',
                'is_active' => 1,
            ],
            [
                'name' => 'Người Dùng 3',
                'email' => 'user3@gmail.com',
                'password' => Hash::make('password'),
                'avatar' => null,
                'role' => 'user',
                'is_active' => 1,
            ],
        ]);

        // Insert Categories
        $categories = [
            [
                'name' => 'Học tập',
                'description' => 'Không chỉ là sách vở, đây là quá trình tiếp thu kiến thức chủ động (active learning), học cách tư duy logic, nghiên cứu, đọc sách và áp dụng kiến thức vào thực tế để mở rộng tư duy.',
                'image' => 'study.jpg',
            ],
            [
                'name' => 'Sức khỏe',
                'description' => 'Tập trung vào việc duy trì lối sống lành mạnh, bao gồm chế độ dinh dưỡng cân bằng, giấc ngủ đủ, quản lý căng thẳng (stress) và kiểm tra sức khỏe định kỳ để có nền tảng vững chắc cho mọi hoạt động.',
                'image' => 'health.jpg',
            ],
            [
                'name' => 'Phát triển bản thân',
                'description' => 'Quá trình tự nhận thức, thiết lập mục tiêu (SMART), nâng cao trí tuệ cảm xúc (EQ), quản lý thời gian và rèn luyện tư duy tích cực (growth mindset) để trở thành phiên bản tốt nhất của chính mình.',
                'image' => 'self.jpg',
            ],
            [
                'name' => 'Kỹ năng',
                'description' => 'Tập trung rèn luyện các kỹ năng thiết yếu như giao tiếp, đàm phán, làm việc nhóm, kỹ năng tin học/ngoại ngữ và kỹ năng chuyên môn sâu để tăng năng lực cạnh tranh trong công việc.',
                'image' => 'skill.jpg',
            ],
            [
                'name' => 'Thể thao',
                'description' => 'Tham gia các bộ môn như chạy bộ, bơi lội, yoga hay gym để tăng cường sức bền, sự dẻo dai và rèn luyện tinh thần kiên cường, kỷ luật, đồng thời xả stress hiệu quả.',
                'image' => 'sport.jpg',
            ],
            [
                'name' => 'Thói quen tốt',
                'description' => 'Thiết lập các quy tắc nhỏ mỗi ngày (như dậy sớm, thiền, viết nhật ký, sắp xếp gọn gàng) nhằm tạo ra sự thay đổi bền vững, kỷ luật tự giác và lối sống có tổ chức hơn.',
                'image' => 'habit.jpg',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }

        // Insert Challenges for each category
        $challenges = [
            // 📚 HỌC TẬP (1) - 3 challenges: Easy, Medium, Hard
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Learn English TOEIC - Dễ', 'description' => 'Goal: 200đ - Luyện tiếng Anh cơ bản: từ vựng, ngữ pháp đơn giản, phát âm chuẩn xác và giao tiếp hàng ngày.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Learn English TOEIC - Trung bình', 'description' => 'Goal: 400đ - Nâng cao kỹ năng tiếng Anh: ngữ pháp nâng cao, từ vựng chuyên sâu, nghe hiểu văn bản dài và viết bài luận.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Learn English TOEIC - Khó', 'description' => 'Goal: 600đ - Tinh thông tiếng Anh: đọc tài liệu phức tạp, thảo luận chuyên môn, viết tiếng Anh chuẩn mực và phát triển vốn từ vựng rộng.', 'daily_time' => 60, 'image' => null],

            // 💪 SỨC KHỎE (2)
            ['category_id' => 2, 'difficulty' => 'easy', 'title' => 'Lắng nghe cơ thể', 'description' => 'Đi bộ nhẹ nhàng kết hợp hít thở sâu và thực hiện các bài giãn cơ toàn thân cơ bản.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 2, 'difficulty' => 'medium', 'title' => 'Năng lượng bùng nổ', 'description' => 'Thực hiện bài tập Cardio hoặc Bodyweight (Squat, Push-up) để tăng cường nhịp tim và sức bền.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 2, 'difficulty' => 'hard', 'title' => 'Chiến binh kỷ luật', 'description' => 'Tập luyện cường độ cao theo lịch trình chuyên sâu, chú trọng vào kỹ thuật và sức mạnh cơ bắp.', 'daily_time' => 90, 'image' => null],
            ['category_id' => 2, 'difficulty' => 'easy', 'title' => 'Cân bằng dinh dưỡng', 'description' => 'Lên kế hoạch và ăn một bữa cân bằng chất dinh dưỡng (carb, protein, fat) đúng khẩu phần.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 2, 'difficulty' => 'medium', 'title' => 'Giấc ngủ vàng', 'description' => 'Tuân thủ giờ giấc đi ngủ/dậy đều đặn, ngủ 7-8 giờ và tạo môi trường ngủ tối ưu.', 'daily_time' => 0, 'image' => null],

            // 🧠 PHÁT TRIỂN BẢN THÂN (3)
            ['category_id' => 3, 'difficulty' => 'easy', 'title' => 'Gieo mầm tư duy', 'description' => 'Đọc sách phát triển bản thân và viết lại 1 bài học tâm đắc nhất bạn có thể áp dụng ngay.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 3, 'difficulty' => 'medium', 'title' => 'Quản trị cuộc đời', 'description' => 'Luyện tập kỹ năng quản lý thời gian và thiết lập mục tiêu SMART cho tuần mới.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 3, 'difficulty' => 'hard', 'title' => 'Bản lĩnh đột phá', 'description' => 'Thực hành phản tư (Self-reflection), rèn luyện EQ thông qua các tình huống giả định và đối mặt với nỗi sợ.', 'daily_time' => 90, 'image' => null],
            ['category_id' => 3, 'difficulty' => 'easy', 'title' => 'Tâm an định mỗi sáng', 'description' => 'Thiền/nói chuyện tâm sự với bản thân 10 phút để bắt đầu ngày với tâm trạng tích cực.', 'daily_time' => 10, 'image' => null],
            ['category_id' => 3, 'difficulty' => 'medium', 'title' => 'Vượt qua thử thách', 'description' => 'Đối diện với một nỗi sợ hay khó khăn nhỏ và tìm cách vượt qua nó để xây dựng lòng tự tin.', 'daily_time' => 30, 'image' => null],

            // 🛠 KỸ NĂNG (4)
            ['category_id' => 4, 'difficulty' => 'easy', 'title' => 'Làm quen công cụ', 'description' => 'Tìm hiểu và thực hành các thao tác cơ bản trên một phần mềm hoặc công cụ chuyên môn mới.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 4, 'difficulty' => 'medium', 'title' => 'Thực hành dự án nhỏ', 'description' => 'Xây dựng một sản phẩm nhỏ (code một tính năng, thiết kế một logo) để vận dụng kỹ năng.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 4, 'difficulty' => 'hard', 'title' => 'Chuyên gia thực thụ', 'description' => 'Tối ưu hóa quy trình làm việc, xử lý các lỗi phức tạp và hoàn thiện sản phẩm đạt tiêu chuẩn chất lượng.', 'daily_time' => 120, 'image' => null],
            ['category_id' => 4, 'difficulty' => 'easy', 'title' => 'Giao tiếp hiệu quả', 'description' => 'Luyện tập nói trước tập thể hoặc ghi lại voice để cải thiện kỹ năng thuyết trình và giao tiếp.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 4, 'difficulty' => 'medium', 'title' => 'Làm việc nhóm', 'description' => 'Tham gia hoặc tổ chức hoạt động nhóm, chia sẻ ý tưởng và lắng nghe ý kiến người khác.', 'daily_time' => 60, 'image' => null],

            // 🏃 THỂ THAO (5)
            ['category_id' => 5, 'difficulty' => 'easy', 'title' => 'Vận động nhẹ nhàng', 'description' => 'Chạy bộ chậm hoặc tập các bài thể dục nhịp điệu để khởi động năng lượng ngày mới.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 5, 'difficulty' => 'medium', 'title' => 'Sức bền bền bỉ', 'description' => 'Chạy bộ cự ly trung bình kết hợp các bài tập bổ trợ để cải thiện tốc độ và sự dẻo dai.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 5, 'difficulty' => 'hard', 'title' => 'Vượt ngưỡng giới hạn', 'description' => 'Tập luyện HIIT cường độ cao hoặc thử thách bản thân với những bài tập đòi hỏi ý chí thép.', 'daily_time' => 90, 'image' => null],
            ['category_id' => 5, 'difficulty' => 'easy', 'title' => 'Yoga thở thả', 'description' => 'Tập các bài yoga cơ bản kết hợp thiền để tăng sự linh hoạt, cân bằng và thư giãn.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 5, 'difficulty' => 'medium', 'title' => 'Bơi lội sức khỏe', 'description' => 'Bơi lội 30-45 phút để tập toàn bộ cơ thể, tăng sức bền tim mạch và làm thư giãn cơ.', 'daily_time' => 45, 'image' => null],

            // 🌱 THÓI QUEN TỐT (6)
            ['category_id' => 6, 'difficulty' => 'easy', 'title' => 'Thay đổi nhỏ, kết quả lớn', 'description' => 'Thực hiện 3 việc nhỏ: Dậy sớm, uống 500ml nước ngay khi ngủ dậy và lập danh sách To-do list.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 6, 'difficulty' => 'medium', 'title' => 'Kỷ luật thép', 'description' => 'Duy trì chuỗi thói quen tích cực (Thiền, viết nhật ký biết ơn) không ngắt quãng trong ngày.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 6, 'difficulty' => 'hard', 'title' => 'Lối sống tỉnh thức', 'description' => 'Thiết lập và tuân thủ tuyệt đối lịch trình sinh hoạt khoa học, loại bỏ hoàn toàn các thói quen xấu.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 6, 'difficulty' => 'easy', 'title' => 'Sạch sẽ gọn gàng', 'description' => 'Dành 15 phút để sắp xếp gọn gàng nơi làm việc hoặc phòng để tạo không gian tích cực.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 6, 'difficulty' => 'medium', 'title' => 'Nhật ký tấm lòng', 'description' => 'Viết nhật ký, ghi lại những điều biết ơn hoặc phản tư lại ngày qua để tăng tự nhận thức.', 'daily_time' => 15, 'image' => null],
        ];

        foreach ($challenges as $challenge) {
            DB::table('challenges')->insert($challenge);
        }
    }
}
