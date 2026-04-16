-- CREATE DATABASE IF NOT EXISTS challenge_hub;
USE challenge_hub;

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    avatar VARCHAR(255),
    role ENUM('user','admin','useradmin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO users (name, email, password)
VALUES ('Admin', 'admin@gmail.com', '123456');


-- CATEGORIES
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE categories;
SET FOREIGN_KEY_CHECKS = 1;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO categories (name, description, image) VALUES
('Học tập', 'Phát triển kiến thức và kỹ năng học tập', 'study.jpg'),
('Sức khỏe', 'Rèn luyện thể chất và sức khỏe', 'health.jpg'),
('Phát triển bản thân', 'Cải thiện bản thân mỗi ngày', 'self.jpg'),
('Kỹ năng', 'Nâng cao kỹ năng mềm và chuyên môn', 'skill.jpg'),
('Thể thao', 'Hoạt động thể chất và thể thao', 'sport.jpg'),
('Thói quen tốt', 'Xây dựng thói quen tích cực', 'habit.jpg');
-- Cập nhật danh mục Học tập
UPDATE categories
SET description = 'Không chỉ là sách vở, đây là quá trình tiếp thu kiến thức chủ động (active learning), học cách tư duy logic, nghiên cứu, đọc sách và áp dụng kiến thức vào thực tế để mở rộng tư duy.'
WHERE name = 'Học tập';

-- Cập nhật danh mục Sức khỏe
UPDATE categories
SET description = 'Tập trung vào việc duy trì lối sống lành mạnh, bao gồm chế độ dinh dưỡng cân bằng, giấc ngủ đủ, quản lý căng thẳng (stress) và kiểm tra sức khỏe định kỳ để có nền tảng vững chắc cho mọi hoạt động.'
WHERE name = 'Sức khỏe';

-- Cập nhật danh mục Phát triển bản thân
UPDATE categories
SET description = 'Quá trình tự nhận thức, thiết lập mục tiêu (SMART), nâng cao trí tuệ cảm xúc (EQ), quản lý thời gian và rèn luyện tư duy tích cực (growth mindset) để trở thành phiên bản tốt nhất của chính mình.'
WHERE name = 'Phát triển bản thân';

-- Cập nhật danh mục Kỹ năng
UPDATE categories
SET description = 'Tập trung rèn luyện các kỹ năng thiết yếu như giao tiếp, đàm phán, làm việc nhóm, kỹ năng tin học/ngoại ngữ và kỹ năng chuyên môn sâu để tăng năng lực cạnh tranh trong công việc.'
WHERE name = 'Kỹ năng';

-- Cập nhật danh mục Thể thao
UPDATE categories
SET description = 'Tham gia các bộ môn như chạy bộ, bơi lội, yoga hay gym để tăng cường sức bền, sự dẻo dai và rèn luyện tinh thần kiên cường, kỷ luật, đồng thời xả stress hiệu quả.'
WHERE name = 'Thể thao';

-- Cập nhật danh mục Thói quen tốt
UPDATE categories
SET description = 'Thiết lập các quy tắc nhỏ mỗi ngày (như dậy sớm, thiền, viết nhật ký, sắp xếp gọn gàng) nhằm tạo ra sự thay đổi bền vững, kỷ luật tự giác và lối sống có tổ chức hơn.'
WHERE name = 'Thói quen tốt';

-- CHALLENGES
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE challenges;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    difficulty ENUM('easy','medium','hard'),
    title VARCHAR(255),
    description TEXT,
    daily_time INT, -- số phút mỗi ngày
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
ALTER TABLE categories
ADD COLUMN image VARCHAR(255),
ADD COLUMN description TEXT;

-- 📚 HỌC TẬP (1)
UPDATE challenges SET
title='Khởi động trí tuệ',
description='Đọc 10 trang sách hoặc ôn tập lại kiến thức đã học trong ngày để củng cố trí nhớ.',
daily_time=30 WHERE category_id=1 AND difficulty='easy';

UPDATE challenges SET
title='Tư duy chủ động',
description='Học một chủ đề mới và tóm tắt lại bằng sơ đồ tư duy (Mindmap) để hiểu sâu vấn đề.',
daily_time=60 WHERE category_id=1 AND difficulty='medium';

UPDATE challenges SET
title='Bậc thầy nghiên cứu',
description='Nghiên cứu chuyên sâu, giải quyết các bài tập phức tạp và áp dụng kiến thức vào một dự án thực tế.',
daily_time=180 WHERE category_id=1 AND difficulty='hard';


-- 💪 SỨC KHỎE (2)
UPDATE challenges SET
title='Lắng nghe cơ thể',
description='Đi bộ nhẹ nhàng kết hợp hít thở sâu và thực hiện các bài giãn cơ toàn thân cơ bản.',
daily_time=20 WHERE category_id=2 AND difficulty='easy';

UPDATE challenges SET
title='Năng lượng bùng nổ',
description='Thực hiện bài tập Cardio hoặc Bodyweight (Squat, Push-up) để tăng cường nhịp tim và sức bền.',
daily_time=45 WHERE category_id=2 AND difficulty='medium';

UPDATE challenges SET
title='Chiến binh kỷ luật',
description='Tập luyện cường độ cao theo lịch trình chuyên sâu, chú trọng vào kỹ thuật và sức mạnh cơ bắp.',
daily_time=90 WHERE category_id=2 AND difficulty='hard';


-- 🧠 PHÁT TRIỂN BẢN THÂN (3)
UPDATE challenges SET
title='Gieo mầm tư duy',
description='Đọc sách phát triển bản thân và viết lại 1 bài học tâm đắc nhất bạn có thể áp dụng ngay.',
daily_time=20 WHERE category_id=3 AND difficulty='easy';

UPDATE challenges SET
title='Quản trị cuộc đời',
description='Luyện tập kỹ năng quản lý thời gian và thiết lập mục tiêu SMART cho tuần mới.',
daily_time=45 WHERE category_id=3 AND difficulty='medium';

UPDATE challenges SET
title='Bản lĩnh đột phá',
description='Thực hành phản tư (Self-reflection), rèn luyện EQ thông qua các tình huống giả định và đối mặt với nỗi sợ.',
daily_time=90 WHERE category_id=3 AND difficulty='hard';


-- 🛠 KỸ NĂNG (4)
UPDATE challenges SET
title='Làm quen công cụ',
description='Tìm hiểu và thực hành các thao tác cơ bản trên một phần mềm hoặc công cụ chuyên môn mới.',
daily_time=30 WHERE category_id=4 AND difficulty='easy';

UPDATE challenges SET
title='Thực hành dự án nhỏ',
description='Xây dựng một sản phẩm nhỏ (code một tính năng, thiết kế một logo) để vận dụng kỹ năng.',
daily_time=60 WHERE category_id=4 AND difficulty='medium';

UPDATE challenges SET
title='Chuyên gia thực thụ',
description='Tối ưu hóa quy trình làm việc, xử lý các lỗi phức tạp và hoàn thiện sản phẩm đạt tiêu chuẩn chất lượng.',
daily_time=120 WHERE category_id=4 AND difficulty='hard';


-- 🏃 THỂ THAO (5)
UPDATE challenges SET
title='Vận động nhẹ nhàng',
description='Chạy bộ chậm hoặc tập các bài thể dục nhịp điệu để khởi động năng lượng ngày mới.',
daily_time=20 WHERE category_id=5 AND difficulty='easy';

UPDATE challenges SET
title='Sức bền bền bỉ',
description='Chạy bộ cự ly trung bình kết hợp các bài tập bổ trợ để cải thiện tốc độ và sự dẻo dai.',
daily_time=45 WHERE category_id=5 AND difficulty='medium';

UPDATE challenges SET
title='Vượt ngưỡng giới hạn',
description='Tập luyện HIIT cường độ cao hoặc thử thách bản thân với những bài tập đòi hỏi ý chí thép.',
daily_time=90 WHERE category_id=5 AND difficulty='hard';


-- 🌱 THÓI QUEN TỐT (6)
UPDATE challenges SET
title='Thay đổi nhỏ, kết quả lớn',
description='Thực hiện 3 việc nhỏ: Dậy sớm, uống 500ml nước ngay khi ngủ dậy và lập danh sách To-do list.',
daily_time=15 WHERE category_id=6 AND difficulty='easy';

UPDATE challenges SET
title='Kỷ luật thép',
description='Duy trì chuỗi thói quen tích cực (Thiền, viết nhật ký biết ơn) không ngắt quãng trong ngày.',
daily_time=30 WHERE category_id=6 AND difficulty='medium';

UPDATE challenges SET
title='Lối sống tỉnh thức',
description='Thiết lập và tuân thủ tuyệt đối lịch trình sinh hoạt khoa học, loại bỏ hoàn toàn các thói quen xấu.',
daily_time=60 WHERE category_id=6 AND difficulty='hard';

-- TASKS
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    challenge_id INT,
    title VARCHAR(255),
    description TEXT,
    day_number INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

-- USER_CHALLENGES
CREATE TABLE user_challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    challenge_id INT,
    start_date DATE,
    progress INT DEFAULT 0,
    completed_days INT DEFAULT 0,
    streak INT DEFAULT 0,
    status ENUM('active','completed','dropped') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE (user_id, challenge_id),

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

-- CHECKINS
CREATE TABLE checkins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    challenge_id INT,
    task_id INT NULL,
    date DATE,
    note TEXT,
    status ENUM('done','missed') DEFAULT 'done',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (user_id, challenge_id, date),

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL
);

-- AI_LOGS
CREATE TABLE ai_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    challenge_id INT,
    recommendation TEXT,
    evaluation TEXT,
    score INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

-- GROUPS
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_by INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- GROUP_USER
CREATE TABLE group_user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE (group_id, user_id),

    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- GROUP_CHALLENGE
CREATE TABLE group_challenge (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    challenge_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE (group_id, challenge_id),

    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

-- NOTIFICATIONS
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT,
    created_by INT,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
);

-- STREAK_LOGS
CREATE TABLE streak_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    challenge_id INT,
    streak INT,
    date DATE,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);
 
