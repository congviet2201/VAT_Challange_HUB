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
            // 📚 HỌC TẬP (1)
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Khởi động trí tuệ', 'description' => 'Đọc 10 trang sách hoặc ôn tập lại kiến thức đã học trong ngày để củng cố trí nhớ.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Tư duy chủ động', 'description' => 'Học một chủ đề mới và tóm tắt lại bằng sơ đồ tư duy (Mindmap) để hiểu sâu vấn đề.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Bậc thầy nghiên cứu', 'description' => 'Nghiên cứu chuyên sâu, giải quyết các bài tập phức tạp và áp dụng kiến thức vào một dự án thực tế.', 'daily_time' => 180, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Vốn từ mỗi ngày', 'description' => 'Học 5-10 từ vựng mới và sử dụng chúng trong các câu để mở rộng kho ngôn ngữ.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Thuyết trình ý tưởng', 'description' => 'Chuẩn bị và thuyết trình về một chủ đề mình vừa học trước tập thể để rèn kỹ năng trình bày.', 'daily_time' => 45, 'image' => null],

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
    }
}
