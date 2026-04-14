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
            // 📚 HỌC TẬP (1) - TOEIC Challenges
            // EASY (10 challenges)
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Thử thách 100 từ/tuần', 'description' => 'Học và ghi nhớ 100 từ vựng TOEIC cơ bản đầu tiên trong 7 ngày. Dùng flashcard để kiểm tra lại vào cuối tuần.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Chủ điểm thì (Tenses)', 'description' => 'Viết 5 câu tiếng Anh sử dụng 5 thì cơ bản: Hiện tại đơn, Hiện tại tiếp diễn, Quá khứ đơn, Tương lai đơn, Hiện tại hoàn thành.', 'daily_time' => 25, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Thử thách 600 từ', 'description' => 'Hoàn thành toàn bộ 600 từ vựng TOEIC cốt lõi trong vòng 6 tuần, mỗi ngày học 15-20 từ.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Phân loại từ', 'description' => 'Đặt câu với cùng một từ ở các dạng từ loại khác nhau (danh từ, động từ, tính từ, trạng từ) để hiểu ngữ pháp.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Chinh phục 8 chủ điểm', 'description' => 'Hoàn thành 10 bài tập cho mỗi chủ điểm ngữ pháp trọng tâm (Thì, Từ loại, Bị động, Câu điều kiện, v.v.).', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Chép chính tả Part 1', 'description' => 'Nghe 10 bức tranh mô tả (Part 1) và chép lại chính xác từng câu. Sai chỗ nào, sửa bằng bút đỏ.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Phản xạ Part 2', 'description' => 'Nghe 10 câu hỏi/đáp (Part 2) và chọn câu trả lời đúng ngay trong 3 giây.', 'daily_time' => 15, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Thử thách Nghe không nhìn', 'description' => 'Nghe 1 đoạn Part 2 dài 3 phút (tương đương 5 câu) mà không xem sub/script, sau đó tự tóm tắt lại nội dung bằng tiếng Việt.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Làm 50 câu Part 1+2', 'description' => 'Làm trọn vẹn 50 câu nghe Part 1 & 2 trong 15 phút, mục tiêu đúng trên 25 câu.', 'daily_time' => 25, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'easy', 'title' => 'Thi thử 200 điểm', 'description' => 'Thực hiện một đề thi thử TOEIC rút gọn (chỉ Part 1, 2 và phần Ngữ pháp) để đánh giá khả năng đạt 200 điểm.', 'daily_time' => 35, 'image' => null],

            // MEDIUM (10 challenges)
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Nghe chép chính tả Dictation 3 câu', 'description' => 'Chọn 3 đoạn hội thoại ngắn Part 3, nghe và chép lại từng từ để quen tốc độ.', 'daily_time' => 25, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Đọc trước câu hỏi Preview', 'description' => 'Trước khi nghe Part 3/4, tập đọc trước 3 câu hỏi và 4 đáp án để tìm từ khóa.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Keyword Mapping', 'description' => 'Khi luyện nghe, hãy gạch chân từ khóa (danh từ, động từ chính) và đối chiếu xem nó chuyển thành từ đồng nghĩa nào trong đáp án.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Nghe tăng tốc độ 1.25x', 'description' => 'Luyện nghe Part 3&4 ở tốc độ 1.25 để khi đi thi thật (tốc độ thường) thấy dễ dàng hơn.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Không nghe lại No rewind', 'description' => 'Luyện nghe 1 lần duy nhất và chọn đáp án, rèn khả năng tập trung cao độ.', 'daily_time' => 25, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Phân tích 5 câu Part 5 trong 2 phút', 'description' => 'Chỉ nhìn vào thành phần trước/sau chỗ trống để chọn từ loại (danh, động, tính, trạng) mà không cần dịch cả câu.', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Kỹ thuật dịch nhẩm', 'description' => 'Luyện đọc nhanh Part 6 và dịch nhẩm cấu trúc câu để nắm ý chính, tập trung vào liên từ và đại từ thay thế.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Thử thách Chữa đề gấp đôi', 'description' => 'Với mỗi câu sai, hãy viết lại tại sao sai và cấu trúc đúng ra một cuốn sổ tay.', 'daily_time' => 35, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Học 10 từ vựng kinh doanh mỗi ngày', 'description' => 'Tập trung vào các chủ đề: Hợp đồng (Contract), Nhân sự (Personnel), Văn phòng (Office), Hội thảo (Conference).', 'daily_time' => 20, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'medium', 'title' => 'Làm đề Part 5+6 dưới 20 phút', 'description' => 'Đặt thời gian làm trọn vẹn 46 câu Part 5 & 6 (câu 101-146) trong đúng 20 phút.', 'daily_time' => 25, 'image' => null],

            // HARD (10 challenges)
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách 7 ngày 7 đề full', 'description' => 'Thi nghiêm túc 7 đề TOEIC hoàn chỉnh (2 giờ/đề), không nghỉ giữa giờ để rèn áp lực thời gian.', 'daily_time' => 120, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách No Pause Reading', 'description' => 'Part 7, đọc không dừng lại tra từ mới. Sử dụng kỹ thuật skimming và scanning để tìm câu trả lời trong tối đa 10 phút/bài.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách 50-100 từ vựng/ngày', 'description' => 'Chữa đề và ghi chép lại toàn bộ từ vựng mới/bẫy thường gặp vào sổ tay, ôn tập lại vào cuối tuần.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách Part 7 1-shot', 'description' => 'Đọc câu hỏi trước, sau đó tìm đúng vị trí thông tin để trả lời mà không đọc toàn bộ đoạn văn.', 'daily_time' => 40, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách 10 câu bẫy', 'description' => 'Tự tay viết lại 10 câu bẫy (distractors) thường gặp nhất trong đề thi và cách tránh.', 'daily_time' => 30, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách Timer Master', 'description' => 'Làm phần Reading với đồng hồ đếm ngược, phân bổ thời gian: 15 phút Part 5&6, 60 phút Part 7.', 'daily_time' => 75, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách Chữa đề sâu', 'description' => 'Giải thích chi tiết tại sao sai, tại sao đúng đối với ít nhất 10 câu sai của mỗi đề.', 'daily_time' => 50, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách đọc nhanh Speed Reading', 'description' => 'Tăng tốc độ đọc hiểu văn bản tiếng Anh trong Part 7, rèn kỹ năng nắm bắt ý chính.', 'daily_time' => 45, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách Zero Vocabulary', 'description' => 'Làm lại 1 đề cũ chỉ dựa vào ngữ cảnh và kỹ năng scanning mà không tra cứu từ vựng trước đó.', 'daily_time' => 60, 'image' => null],
            ['category_id' => 1, 'difficulty' => 'hard', 'title' => 'Thử thách Mục tiêu 950+', 'description' => 'Tăng số điểm của đề sau cao hơn đề trước ít nhất 20-30 điểm nhờ việc chữa kỹ và rút kinh nghiệm.', 'daily_time' => 90, 'image' => null],

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

        // Thêm Tasks cho tất cả các challenges gốc (không phải English)
        // Các challenge từ categories 1-6 sẽ được thêm tasks
        $tasksForAllChallenges = [
            ['title' => 'Bước 1: Chuẩn bị', 'order' => 1],
            ['title' => 'Bước 2: Thực hiện hoạt động chính', 'order' => 2],
            ['title' => 'Bước 3: Ghi chép kết quả', 'order' => 3],
            ['title' => 'Bước 4: Phản tư và hiểu bài học', 'order' => 4],
            ['title' => 'Bước 5: Chia sẻ kinh nghiệm', 'order' => 5],
        ];

        $challengesForTasks = DB::table('challenges')->whereIn('category_id', [1, 2, 3, 4, 5, 6])->get();

        foreach ($challengesForTasks as $challenge) {
            foreach ($tasksForAllChallenges as $task) {
                DB::table('tasks')->insert([
                    'challenge_id' => $challenge->id,
                    'title' => $task['title'],
                    'order' => $task['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 🇬🇧 ENGLISH (7) - Thêm category cho English challenges
        DB::table('categories')->insert([
            'name' => 'English',
            'description' => 'Phát triển kỹ năng tiếng Anh toàn diện: từ vựng, ngữ pháp, nghe, nói, đọc, viết để tự tin giao tiếp và học tập.',
            'image' => 'english.jpg',
        ]);

        $englishCategoryId = DB::table('categories')->where('name', 'English')->value('id');

        // English Challenges - Easy (5)
        $englishChallenges = [
            // EASY (10 challenges)
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => '', 'description' => 'Học 10 từ vựng mới hằng ngày: phát âm, nghĩa, và ví dụ sử dụng.', 'daily_time' => 15, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => '', 'description' => 'Đọc các truyện ngắn dành cho người mới bắt đầu, tăng từ vựng đơn giản.', 'daily_time' => 20, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Luyện nghe qua Podcast', 'description' => 'Nghe các tập Podcast tiếng Anh cơ bản, tập trung vào phát âm và intonation.', 'daily_time' => 15, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Viết Diary bằng Tiếng Anh', 'description' => 'Viết nhật ký hằng ngày bằng tiếng Anh đơn giản: sự kiện, cảm xúc, kế hoạch.', 'daily_time' => 20, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Nói chuyện với AI Assistant', 'description' => 'Sử dụng ứng dụng AI để luyện nói tiếng Anh, được tự động chữa lỗi.', 'daily_time' => 15, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Luyện từ vựng qua trò chơi', 'description' => 'Tham gia các trò chơi từ vựng để học và ghi nhớ từ mới một cách thú vị.', 'daily_time' => 15, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Xem video hướng dẫn', 'description' => 'Xem các video hướng dẫn ngắn về các chủ đề tiếng Anh cơ bản.', 'daily_time' => 20, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Luyện phát âm', 'description' => 'Luyện phát âm các âm tiết và cụm từ trong tiếng Anh.', 'daily_time' => 15, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Tạo flashcards', 'description' => 'Tạo flashcards để ôn tập từ vựng và ngữ pháp.', 'daily_time' => 10, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'easy', 'title' => 'Nghe và lặp lại', 'description' => 'Nghe các đoạn hội thoại và lặp lại để cải thiện phát âm.', 'daily_time' => 20, 'image' => null],


            // MEDIUM (10 challenges)
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Xem Film với subtitle Anh', 'description' => 'Xem phim/series với subtitle tiếng Anh, dừng lại để hiểu các cụm từ.', 'daily_time' => 30, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Học ngữ pháp Advanced', 'description' => 'Học các cấu trúc ngữ pháp phức tạp: conditional, relative clauses, passive voice.', 'daily_time' => 30, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Viết Essay ngắn', 'description' => 'Viết bài luận dài 300-500 từ về một chủ đề, cải thiện kỹ năng viết.', 'daily_time' => 40, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Thảo luận về các chủ đề', 'description' => 'Tham gia thảo luận nhóm về các chủ đề khác nhau bằng tiếng Anh.', 'daily_time' => 30, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Luyện thi TOEIC/IELTS', 'description' => 'Làm các bài tập thi chuẩn: listening, reading, writing để chuẩn bị cho kỳ thi.', 'daily_time' => 45, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Thuyết trình tiếng Anh', 'description' => 'Chuẩn bị và thực hành thuyết trình 5-10 phút về một chủ đề bất kỳ.', 'daily_time' => 35, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'medium', 'title' => 'Đọc bài báo tiếng Anh', 'description' => 'Đọc các bài báo trên BBC, CNN, The Guardian để cập nhật từ vựng học thuật.', 'daily_time' => 30, 'image' => null],

            // HARD (10 challenges)
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Xem TED Talk & Viết Summary', 'description' => 'Xem TED talk chuyên sâu và viết tóm tắt cho phép nhập vào diễn đàn.', 'daily_time' => 45, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Dịch từ Anh → Việt (Chuyên ngành)', 'description' => 'Dịch các tài liệu chuyên ngành (IT, kinh tế, khoa học) từ tiếng Anh sang Việt.', 'daily_time' => 50, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Viết báo cáo kỹ thuật', 'description' => 'Viết báo cáo chuyên sâu (800-1000 từ) về các vấn đề kỹ thuật bằng tiếng Anh.', 'daily_time' => 60, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Học với Native Speaker (1:1)', 'description' => 'Gặp gỡ online với người bản ngữ để luyện nói, điều chỉnh accent và phát âm.', 'daily_time' => 45, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Luyện Debate tiếng Anh', 'description' => 'Tham gia cuộc tranh luận chính thức bằng tiếng Anh, bảo vệ quan điểm của mình.', 'daily_time' => 60, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Đọc Academic Paper', 'description' => 'Đọc và phân tích các bài báo khoa học tiếng Anh, hiểu các khái niệm phức tạp.', 'daily_time' => 60, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Tạo content Anh (Blog/Video)', 'description' => 'Tạo bài viết blog hoặc video hướng dẫn bằng tiếng Anh chất lượng cao.', 'daily_time' => 90, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Chuẩn bị phỏng vấn tiếng Anh', 'description' => 'Luyện phỏng vấn công việc bằng tiếng Anh: trả lời câu hỏi khó, pitch công ty.', 'daily_time' => 45, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Viết sáng tác (Essay/Story)', 'description' => 'Viết những tác phẩm sáng tạo hoặc reflective essay dài và chi tiết bằng tiếng Anh.', 'daily_time' => 75, 'image' => null],
            ['category_id' => $englishCategoryId, 'difficulty' => 'hard', 'title' => 'Học tiếng Anh vệ sinh (Medical English)', 'description' => 'Học tiếng Anh chuyên ngành: y học, công nghệ, kinh doanh tùy theo nhu cầu.', 'daily_time' => 60, 'image' => null],
        ];

        foreach ($englishChallenges as $challenge) {
            DB::table('challenges')->insert($challenge);
        }

        // Thêm Tasks cho English Challenges
        // Easy Challenges: Mỗi challenge có 4-5 tasks
        $easyTasks = [
            // Challenge 1: Học 10 từ vựng mỗi ngày
            [
                ['challenge_title' => 'Học 10 từ vựng mỗi ngày', 'title' => 'Xem cách phát âm từ vựng', 'order' => 1],
                ['challenge_title' => 'Học 10 từ vựng mỗi ngày', 'title' => 'Viết 10 từ vào sổ tay', 'order' => 2],
                ['challenge_title' => 'Học 10 từ vựng mỗi ngày', 'title' => 'Làm bài tập điền từ', 'order' => 3],
                ['challenge_title' => 'Học 10 từ vựng mỗi ngày', 'title' => 'Tạo câu với các từ vừa học', 'order' => 4],
                ['challenge_title' => 'Học 10 từ vựng mỗi ngày', 'title' => 'Ôn lại những từ cũ', 'order' => 5],
            ],
            // Challenge 2: Đọc truyện ngắn Easy
            [
                ['challenge_title' => 'Đọc truyện ngắn Easy', 'title' => 'Chọn truyện thích hợp', 'order' => 1],
                ['challenge_title' => 'Đọc truyện ngắn Easy', 'title' => 'Đọc 1-2 đoạn đầu', 'order' => 2],
                ['challenge_title' => 'Đọc truyện ngắn Easy', 'title' => 'Tìm từ mới và ghi chú', 'order' => 3],
                ['challenge_title' => 'Đọc truyện ngắn Easy', 'title' => 'Đọc hết toàn bộ truyện', 'order' => 4],
                ['challenge_title' => 'Đọc truyện ngắn Easy', 'title' => 'Tóm tắt nội dung bằng tiếng Anh', 'order' => 5],
            ],
            // Challenge 3: Luyện nghe qua Podcast
            [
                ['challenge_title' => 'Luyện nghe qua Podcast', 'title' => 'Chọn podcast phù hợp', 'order' => 1],
                ['challenge_title' => 'Luyện nghe qua Podcast', 'title' => 'Nghe 1 tập (không subtitle)', 'order' => 2],
                ['challenge_title' => 'Luyện nghe qua Podcast', 'title' => 'Nghe lại với subtitle Anh', 'order' => 3],
                ['challenge_title' => 'Luyện nghe qua Podcast', 'title' => 'Ghi chú các cụm từ mới', 'order' => 4],
            ],
            // Challenge 4: Viết Diary bằng Tiếng Anh
            [
                ['challenge_title' => 'Viết Diary bằng Tiếng Anh', 'title' => 'Suy nghĩ về sự kiện hôm nay', 'order' => 1],
                ['challenge_title' => 'Viết Diary bằng Tiếng Anh', 'title' => 'Viết 5-10 câu về ngày của bạn', 'order' => 2],
                ['challenge_title' => 'Viết Diary bằng Tiếng Anh', 'title' => 'Kiểm tra ngữ pháp và từ vựng', 'order' => 3],
                ['challenge_title' => 'Viết Diary bằng Tiếng Anh', 'title' => 'Đọc lại và sửa lỗi', 'order' => 4],
            ],
            // Challenge 5: Nói chuyện với AI Assistant
            [
                ['challenge_title' => 'Nói chuyện với AI Assistant', 'title' => 'Mở ứng dụng học tiếng Anh', 'order' => 1],
                ['challenge_title' => 'Nói chuyện với AI Assistant', 'title' => 'Luyện nói 5-10 phút', 'order' => 2],
                ['challenge_title' => 'Nói chuyện với AI Assistant', 'title' => 'Nhận phản hồi từ AI', 'order' => 3],
                ['challenge_title' => 'Nói chuyện với AI Assistant', 'title' => 'Sửa lỗi phát âm', 'order' => 4],
            ],
        ];

        // Insert Easy Tasks
        foreach ($easyTasks as $challengeTasks) {
            foreach ($challengeTasks as $task) {
                $challengeTitle = $task['challenge_title'];
                unset($task['challenge_title']);
                $challengeId = DB::table('challenges')
                    ->where('title', $challengeTitle)
                    ->where('difficulty', 'easy')
                    ->where('category_id', $englishCategoryId)
                    ->value('id');
                if ($challengeId) {
                    DB::table('tasks')->insert(array_merge($task, [
                        'challenge_id' => $challengeId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                }
            }
        }

        // Tương tự cho Medium và Hard challenges (mỗi challenge 5-6 tasks)
        // Để rút ngắn, tôi sẽ tạo tasks cho vài challenges Medium
        $mediumTasksData = [
            [
                'title' => 'Xem Film với subtitle Anh',
                'tasks' => [
                    ['title' => 'Chọn bộ phim/series phù hợp', 'order' => 1],
                    ['title' => 'Xem 30 phút với subtitle Anh', 'order' => 2],
                    ['title' => 'Ghi chú các cụm từ hay', 'order' => 3],
                    ['title' => 'Xem lại các cảnh khó', 'order' => 4],
                    ['title' => 'Viết tóm tắt 1 cảnh', 'order' => 5],
                ]
            ],
            [
                'title' => 'Học ngữ pháp Advanced',
                'tasks' => [
                    ['title' => 'Đọc giải thích ngữ pháp', 'order' => 1],
                    ['title' => 'Làm bài tập 1', 'order' => 2],
                    ['title' => 'Làm bài tập 2', 'order' => 3],
                    ['title' => 'Làm bài tập 3', 'order' => 4],
                    ['title' => 'Kiểm tra đáp án và sửa lỗi', 'order' => 5],
                ]
            ],
        ];

        foreach ($mediumTasksData as $data) {
            $challengeId = DB::table('challenges')
                ->where('title', $data['title'])
                ->where('difficulty', 'medium')
                ->where('category_id', $englishCategoryId)
                ->value('id');

            if ($challengeId) {
                foreach ($data['tasks'] as $task) {
                    DB::table('tasks')->insert([
                        'challenge_id' => $challengeId,
                        'title' => $task['title'],
                        'order' => $task['order'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
