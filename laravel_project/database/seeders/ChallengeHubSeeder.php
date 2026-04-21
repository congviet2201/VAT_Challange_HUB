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
            [
                'name' => 'Tài chính',
                'description' => 'Quản lý tài chính cá nhân thông minh, lập kế hoạch tiết kiệm, đầu tư và xây dựng tài sản lâu dài để đạt được sự ổn định và tự do tài chính.',
                'image' => 'taichinh.jpg',
            ],
            [
                'name' => 'Mối quan hệ',
                'description' => 'Xây dựng và duy trì các mối quan hệ lành mạnh với gia đình, bạn bè và đồng nghiệp thông qua giao tiếp hiệu quả, lắng nghe và thể hiện sự quan tâm.',
                'image' => 'moiquanhe.jpg',
            ],
            [
                'name' => 'Nghệ thuật',
                'description' => 'Khám phá và phát triển khả năng sáng tạo thông qua hội họa, âm nhạc, viết lách hoặc các hình thức nghệ thuật khác để nuôi dưỡng tâm hồn và thể hiện bản thân.',
                'image' => 'nghethuat.jpg',
            ],
            [
                'name' => 'Công nghệ',
                'description' => 'Theo kịp xu hướng công nghệ, học lập trình, sử dụng công cụ số và ứng dụng công nghệ vào cuộc sống để nâng cao hiệu quả và mở rộng cơ hội.',
                'image' => 'congnghe.jpg',
            ],
            [
                'name' => 'Môi trường',
                'description' => 'Tham gia bảo vệ môi trường thông qua các hành động như tái chế, tiết kiệm năng lượng, trồng cây và nâng cao nhận thức về bảo vệ hành tinh.',
                'image' => 'moitruong.jpg',
            ],
            [
                'name' => 'Du lịch',
                'description' => 'Khám phá thế giới, trải nghiệm văn hóa mới và mở mang tầm nhìn thông qua các chuyến đi, học hỏi từ những nền văn hóa khác nhau.',
                'image' => 'dulich.jpg',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }

        // Insert Challenges for each category
        $challenges = [
            // 📚 HỌC TẬP (1) - 3 challenges: Easy, Medium, Hard
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Learn English TOEIC - Dễ', 'description' => 'Goal: 200đ - Luyện tiếng Anh cơ bản: từ vựng, ngữ pháp đơn giản, phát âm chuẩn xác và giao tiếp hàng ngày.', 'daily_time' => 10, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Learn English TOEIC - Trung bình', 'description' => 'Goal: 400đ - Nâng cao kỹ năng tiếng Anh: ngữ pháp nâng cao, từ vựng chuyên sâu, nghe hiểu văn bản dài và viết bài luận.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Learn English TOEIC - Khó', 'description' => 'Goal: 600đ - Tinh thông tiếng Anh: đọc tài liệu phức tạp, thảo luận chuyên môn, viết tiếng Anh chuẩn mực và phát triển vốn từ vựng rộng.', 'daily_time' => 20, 'image' => null],

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

            // 💰 TÀI CHÍNH (7)
            ['category_id' => 7, 'difficulty' => 'easy', 'title' => 'Theo dõi chi tiêu', 'description' => 'Ghi lại tất cả chi tiêu trong ngày và phân loại để hiểu rõ nguồn tiền ra.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 7, 'difficulty' => 'medium', 'title' => 'Lập kế hoạch tiết kiệm', 'description' => 'Thiết lập mục tiêu tiết kiệm hàng tháng và lập kế hoạch chi tiêu hợp lý.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 7, 'difficulty' => 'hard', 'title' => 'Đầu tư thông minh', 'description' => 'Nghiên cứu và thực hiện các khoản đầu tư nhỏ như chứng khoán hoặc quỹ tương hỗ.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 7, 'difficulty' => 'easy', 'title' => 'Tăng thu nhập', 'description' => 'Tìm kiếm cơ hội tăng thu nhập phụ như freelance hoặc bán hàng online.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 7, 'difficulty' => 'medium', 'title' => 'Quản lý nợ', 'description' => 'Lập kế hoạch trả nợ hiệu quả và tránh nợ xấu để cải thiện điểm tín dụng.', 'daily_time' => 30, 'image' => null],

            // 👥 MỐI QUAN HỆ (8)
            ['category_id' => 8, 'difficulty' => 'easy', 'title' => 'Liên lạc thường xuyên', 'description' => 'Gọi điện hoặc nhắn tin hỏi thăm người thân, bạn bè ít nhất một lần mỗi tuần.', 'daily_time' => 10, 'image' => null],
            ['category_id' => 8, 'difficulty' => 'medium', 'title' => 'Xây dựng mạng lưới', 'description' => 'Tham gia các sự kiện networking hoặc kết nối với ít nhất 2 người mới trong lĩnh vực của bạn.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 8, 'difficulty' => 'hard', 'title' => 'Giải quyết xung đột', 'description' => 'Đối diện và giải quyết một xung đột trong mối quan hệ một cách xây dựng.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 8, 'difficulty' => 'easy', 'title' => 'Lắng nghe tích cực', 'description' => 'Dành thời gian lắng nghe người khác chia sẻ mà không phán xét hoặc ngắt lời.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 8, 'difficulty' => 'medium', 'title' => 'Thể hiện sự quan tâm', 'description' => 'Làm một việc nhỏ để thể hiện sự quan tâm đến người thân hoặc bạn bè.', 'daily_time' => 15, 'image' => null],

            // 🎨 NGHỆ THUẬT (9)
            ['category_id' => 9, 'difficulty' => 'easy', 'title' => 'Vẽ tranh đơn giản', 'description' => 'Dành 15 phút vẽ một bức tranh đơn giản hoặc phác thảo ý tưởng sáng tạo.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 9, 'difficulty' => 'medium', 'title' => 'Học nhạc cụ', 'description' => 'Luyện tập chơi một nhạc cụ hoặc hát một bài hát mới mỗi ngày.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 9, 'difficulty' => 'hard', 'title' => 'Tạo tác phẩm nghệ thuật', 'description' => 'Hoàn thành một tác phẩm nghệ thuật như tranh vẽ, âm nhạc hoặc thơ ca.', 'daily_time' => 90, 'image' => null],
            ['category_id' => 9, 'difficulty' => 'easy', 'title' => 'Thưởng thức nghệ thuật', 'description' => 'Xem một bộ phim, nghe một bản nhạc hoặc đọc một cuốn sách về nghệ thuật.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 9, 'difficulty' => 'medium', 'title' => 'Viết sáng tạo', 'description' => 'Viết một câu chuyện ngắn, bài thơ hoặc nhật ký sáng tạo mỗi ngày.', 'daily_time' => 20, 'image' => null],

            // 💻 CÔNG NGHỆ (10)
            ['category_id' => 10, 'difficulty' => 'easy', 'title' => 'Học lập trình cơ bản', 'description' => 'Dành 30 phút học một khái niệm lập trình mới hoặc thực hành code đơn giản.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 10, 'difficulty' => 'medium', 'title' => 'Xây dựng dự án nhỏ', 'description' => 'Tạo một ứng dụng web đơn giản hoặc script tự động hóa công việc.', 'daily_time' => 90, 'image' => null],
            ['category_id' => 10, 'difficulty' => 'hard', 'title' => 'Đóng góp mã nguồn mở', 'description' => 'Tham gia dự án mã nguồn mở trên GitHub và đóng góp code hoặc báo cáo lỗi.', 'daily_time' => 120, 'image' => null],
            ['category_id' => 10, 'difficulty' => 'easy', 'title' => 'Tìm hiểu công nghệ mới', 'description' => 'Đọc tin tức công nghệ hoặc xem video về xu hướng công nghệ mới.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 10, 'difficulty' => 'medium', 'title' => 'Tự động hóa công việc', 'description' => 'Tạo script hoặc ứng dụng để tự động hóa một công việc lặp lại.', 'daily_time' => 60, 'image' => null],

            // 🌍 MÔI TRƯỜNG (11)
            ['category_id' => 11, 'difficulty' => 'easy', 'title' => 'Tái chế rác thải', 'description' => 'Phân loại rác thải và tái chế đúng cách trong ngày hôm nay.', 'daily_time' => 10, 'image' => null],
            ['category_id' => 11, 'difficulty' => 'medium', 'title' => 'Tiết kiệm năng lượng', 'description' => 'Thực hiện các hành động tiết kiệm điện, nước và giảm phát thải carbon.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 11, 'difficulty' => 'hard', 'title' => 'Bảo vệ môi trường', 'description' => 'Tham gia chiến dịch bảo vệ môi trường hoặc trồng cây xanh trong cộng đồng.', 'daily_time' => 120, 'image' => null],
            ['category_id' => 11, 'difficulty' => 'easy', 'title' => 'Sử dụng phương tiện xanh', 'description' => 'Đi bộ, đạp xe hoặc sử dụng phương tiện công cộng thay vì ô tô cá nhân.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 11, 'difficulty' => 'medium', 'title' => 'Giảm rác thải nhựa', 'description' => 'Tránh sử dụng đồ nhựa dùng một lần và tìm giải pháp thay thế bền vững.', 'daily_time' => 20, 'image' => null],

            // ✈️ DU LỊCH (12)
            ['category_id' => 12, 'difficulty' => 'easy', 'title' => 'Khám phá địa phương', 'description' => 'Tham quan một địa điểm mới trong thành phố hoặc khu vực của bạn.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 12, 'difficulty' => 'medium', 'title' => 'Du lịch ngắn ngày', 'description' => 'Lập kế hoạch và thực hiện một chuyến du lịch ngắn ngày đến nơi gần đó.', 'daily_time' => 480, 'image' => null],
            ['category_id' => 12, 'difficulty' => 'hard', 'title' => 'Du lịch dài ngày', 'description' => 'Tham gia một chuyến du lịch dài ngày để trải nghiệm văn hóa mới.', 'daily_time' => 0, 'image' => null],
            ['category_id' => 12, 'difficulty' => 'easy', 'title' => 'Học ngoại ngữ du lịch', 'description' => 'Học 5-10 từ vựng hoặc câu cơ bản trong ngôn ngữ của điểm đến.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 12, 'difficulty' => 'medium', 'title' => 'Lập kế hoạch du lịch', 'description' => 'Nghiên cứu và lập kế hoạch chi tiết cho một chuyến du lịch trong tương lai.', 'daily_time' => 45, 'image' => null],
        ];

        foreach ($challenges as $challenge) {
            DB::table('challenges')->insert($challenge);
        }

        // Insert Tasks for each challenge
        $tasks = [
            // --- EASY (Challenge ID: 1) ---
            ['challenge_id' => 1, 'order' => 1, 'title' => 'Thử thách 100 từ/tuần', 'description' => 'Học và ghi nhớ 100 từ vựng TOEIC cơ bản đầu tiên trong 7 ngày. Dùng flashcard để kiểm tra lại vào cuối tuần.'],
            ['challenge_id' => 1, 'order' => 2, 'title' => 'Chủ điểm thì (Tenses)', 'description' => 'Viết 5 câu tiếng Anh sử dụng 5 thì cơ bản: Hiện tại đơn, Hiện tại tiếp diễn, Quá khứ đơn, Tương lai đơn, Hiện tại hoàn thành.'],
            ['challenge_id' => 1, 'order' => 3, 'title' => 'Thử thách 600 từ', 'description' => 'Hoàn thành toàn bộ 600 từ vựng TOEIC cốt lõi trong vòng 6 tuần, mỗi ngày học 15-20 từ.'],
            ['challenge_id' => 1, 'order' => 4, 'title' => 'Phân loại từ', 'description' => 'Đặt câu với cùng một từ ở các dạng từ loại khác nhau (danh từ, động từ, tính từ, trạng từ) để hiểu ngữ pháp.'],
            ['challenge_id' => 1, 'order' => 5, 'title' => 'Chinh phục 8 chủ điểm', 'description' => 'Hoàn thành 10 bài tập cho mỗi chủ điểm ngữ pháp trọng tâm (Thì, Từ loại, Bị động, Câu điều kiện, v.v.).'],
            ['challenge_id' => 1, 'order' => 6, 'title' => 'Chép chính tả Part 1', 'description' => 'Nghe 10 bức tranh mô tả (Part 1) và chép lại chính xác từng câu. Sai chỗ nào, sửa bằng bút đỏ.'],
            ['challenge_id' => 1, 'order' => 7, 'title' => 'Phản xạ Part 2', 'description' => 'Nghe 10 câu hỏi/đáp (Part 2) và chọn câu trả lời đúng ngay trong 3 giây.'],
            ['challenge_id' => 1, 'order' => 8, 'title' => 'Thử thách Nghe không nhìn', 'description' => 'Nghe 1 đoạn Part 2 dài 3 phút mà không xem sub/script, sau đó tự tóm tắt lại nội dung bằng tiếng Việt.'],
            ['challenge_id' => 1, 'order' => 9, 'title' => 'Làm 50 câu Part 1+2', 'description' => 'Làm trọn vẹn 50 câu nghe Part 1 & 2 trong 15 phút, mục tiêu đúng trên 25 câu.'],
            ['challenge_id' => 1, 'order' => 10, 'title' => 'Thi thử 200 điểm', 'description' => 'Thực hiện một đề thi thử TOEIC rút gọn (chỉ Part 1, 2 và phần Ngữ pháp) để đánh giá khả năng đạt 200 điểm.'],

            // --- MEDIUM (Challenge ID: 2) ---
            ['challenge_id' => 2, 'order' => 1, 'title' => 'Nghe chép chính tả Dictation', 'description' => 'Chọn 3 đoạn hội thoại ngắn Part 3, nghe và chép lại từng từ để quen tốc độ.'],
            ['challenge_id' => 2, 'order' => 2, 'title' => 'Đọc trước câu hỏi Preview', 'description' => 'Trước khi nghe Part 3/4, tập đọc trước 3 câu hỏi và 4 đáp án để tìm từ khóa.'],
            ['challenge_id' => 2, 'order' => 3, 'title' => 'Keyword Mapping', 'description' => 'Gạch chân từ khóa và đối chiếu xem nó chuyển thành từ đồng nghĩa nào trong đáp án.'],
            ['challenge_id' => 2, 'order' => 4, 'title' => 'Nghe tăng tốc độ 1.25x', 'description' => 'Luyện nghe Part 3&4 ở tốc độ 1.25 để khi đi thi thật thấy dễ dàng hơn.'],
            ['challenge_id' => 2, 'order' => 5, 'title' => 'Không nghe lại No rewind', 'description' => 'Luyện nghe 1 lần duy nhất và chọn đáp án, rèn khả năng tập trung cao độ.'],
            ['challenge_id' => 2, 'order' => 6, 'title' => 'Phân tích 5 câu Part 5', 'description' => 'Chỉ nhìn vào thành phần trước/sau chỗ trống để chọn từ loại mà không cần dịch cả câu.'],
            ['challenge_id' => 2, 'order' => 7, 'title' => 'Kỹ thuật dịch nhẩm', 'description' => 'Luyện đọc nhanh Part 6 và dịch nhẩm cấu trúc câu để nắm ý chính.'],
            ['challenge_id' => 2, 'order' => 8, 'title' => 'Thử thách Chữa đề gấp đôi', 'description' => 'Với mỗi câu sai, hãy viết lại tại sao sai và cấu trúc đúng ra một cuốn sổ tay.'],
            ['challenge_id' => 2, 'order' => 9, 'title' => 'Học từ vựng kinh doanh', 'description' => 'Tập trung vào các chủ đề: Hợp đồng, Nhân sự, Văn phòng, Hội thảo.'],
            ['challenge_id' => 2, 'order' => 10, 'title' => 'Làm đề Part 5+6', 'description' => 'Đặt thời gian làm trọn vẹn 46 câu Part 5 & 6 trong đúng 20 phút.'],
            ['challenge_id' => 2, 'order' => 11, 'title' => 'Paraphrasing Master', 'description' => 'Tìm và viết lại 10 cặp từ đồng nghĩa xuất hiện giữa câu hỏi và đoạn văn trong Part 7.'],
            ['challenge_id' => 2, 'order' => 12, 'title' => 'Luyện nghe chéo', 'description' => 'Nghe một đoạn Part 4 và tự đặt ra 3 câu hỏi vấn đáp dựa trên nội dung vừa nghe.'],
            ['challenge_id' => 2, 'order' => 13, 'title' => 'Thử thách 15 câu Part 5', 'description' => 'Hoàn thành 15 câu điền từ Part 5 trong tối đa 6 phút với độ chính xác trên 80%.'],
            ['challenge_id' => 2, 'order' => 14, 'title' => 'Phân tích liên từ', 'description' => 'Chọn 5 bài Part 6, gạch chân toàn bộ liên từ (however, therefore, although...) và giải thích logic nối câu.'],
            ['challenge_id' => 2, 'order' => 15, 'title' => 'Nghe hiểu sơ đồ', 'description' => 'Luyện tập 5 bài nghe có kèm hình ảnh/biểu đồ (Part 3/4 mới) và ghi lại thông tin liên kết giữa lời nói và hình ảnh.'],

            // --- HARD (Challenge ID: 3) ---
            ['challenge_id' => 3, 'order' => 1, 'title' => '7 ngày 7 đề full', 'description' => 'Thi nghiêm túc 7 đề TOEIC hoàn chỉnh (2 giờ/đề), không nghỉ giữa giờ.'],
            ['challenge_id' => 3, 'order' => 2, 'title' => 'Thử thách No Pause Reading', 'description' => 'Part 7, đọc không dừng lại tra từ mới. Sử dụng kỹ thuật skimming và scanning.'],
            ['challenge_id' => 3, 'order' => 3, 'title' => 'Thử thách 50-100 từ/ngày', 'description' => 'Chữa đề và ghi chép lại toàn bộ từ vựng mới/bẫy thường gặp vào sổ tay.'],
            ['challenge_id' => 3, 'order' => 4, 'title' => 'Thử thách Part 7 1-shot', 'description' => 'Đọc câu hỏi trước, sau đó tìm đúng vị trí thông tin mà không đọc toàn bộ đoạn văn.'],
            ['challenge_id' => 3, 'order' => 5, 'title' => 'Thử thách 10 câu bẫy', 'description' => 'Tự tay viết lại 10 câu bẫy (distractors) thường gặp nhất và cách tránh.'],
            ['challenge_id' => 3, 'order' => 6, 'title' => 'Thử thách Timer Master', 'description' => 'Làm phần Reading: 15 phút Part 5&6, 60 phút Part 7.'],
            ['challenge_id' => 3, 'order' => 7, 'title' => 'Thử thách Chữa đề sâu', 'description' => 'Giải thích chi tiết tại sao sai, tại sao đúng đối với ít nhất 10 câu sai của mỗi đề.'],
            ['challenge_id' => 3, 'order' => 8, 'title' => 'Đọc nhanh Speed Reading', 'description' => 'Tăng tốc độ đọc hiểu văn bản tiếng Anh trong Part 7, nắm bắt ý chính nhanh.'],
            ['challenge_id' => 3, 'order' => 9, 'title' => 'Thử thách Zero Vocabulary', 'description' => 'Làm lại 1 đề cũ chỉ dựa vào ngữ cảnh mà không tra cứu từ vựng.'],
            ['challenge_id' => 3, 'order' => 10, 'title' => 'Thử thách Mục tiêu 950+', 'description' => 'Tăng số điểm của đề sau cao hơn đề trước ít nhất 20-30 điểm nhờ chữa kỹ.'],
            ['challenge_id' => 3, 'order' => 11, 'title' => 'Thử thách 180 phút tập trung', 'description' => 'Nghe và đọc liên tục trong 3 tiếng không nghỉ để rèn luyện sức bền trí não trước ngày thi.'],
            ['challenge_id' => 3, 'order' => 12, 'title' => 'Dịch ngược Part 7', 'description' => 'Chọn một đoạn văn khó nhất trong Part 7, dịch sang tiếng Việt rồi từ bản tiếng Việt dịch ngược lại tiếng Anh.'],
            ['challenge_id' => 3, 'order' => 13, 'title' => 'Shadowing Part 4', 'description' => 'Nghe 1 đoạn Talk (Part 4) và lặp lại đuổi theo lời người nói với tốc độ và ngữ điệu giống 95%.'],
            ['challenge_id' => 3, 'order' => 14, 'title' => 'Bẫy suy luận (Inference)', 'description' => 'Chỉ làm các câu hỏi dạng "What is implied about..." hoặc "What can be inferred..." trong 5 đề thi.'],
            ['challenge_id' => 3, 'order' => 15, 'title' => 'Triple Passage Marathon', 'description' => 'Làm liên tục 5 bài đọc đoạn thẳng (đoạn ba - 3 đoạn văn) trong tối đa 25 phút.'],
            ['challenge_id' => 3, 'order' => 16, 'title' => 'Tư duy loại trừ tuyệt đối', 'description' => 'Với mỗi câu Part 2, phải giải thích được tại sao 2 đáp án còn lại là sai (bẫy lặp âm, bẫy sai ngữ cảnh...).'],
            ['challenge_id' => 3, 'order' => 17, 'title' => 'Thử thách Reading không từ điển', 'description' => 'Làm trọn bộ Part 5, 6, 7 của một đề ETS mới nhất mà tuyệt đối không được dùng công cụ hỗ trợ.'],
            ['challenge_id' => 3, 'order' => 18, 'title' => 'Master of Tone', 'description' => 'Xác định thái độ của người viết (hài lòng, phàn nàn, đề nghị) trong 10 bài đọc Email/Letter.'],
            ['challenge_id' => 3, 'order' => 19, 'title' => 'Thử thách 1000 từ nâng cao', 'description' => 'Ôn tập và kiểm tra lại toàn bộ các từ vựng ít gặp nhưng chuyên sâu về tài chính/pháp lý trong TOEIC.'],
            ['challenge_id' => 3, 'order' => 20, 'title' => 'Mock Test áp lực cao', 'description' => 'Làm đề full nhưng tự cắt giảm 10 phút thời gian quy định (Nghe 45p, Đọc 65p).'],
        ];

        foreach ($tasks as $task) {
            DB::table('tasks')->insert($task);
        }
    }
}
