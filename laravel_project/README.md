# Challenge Hub 🎯

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=white" alt="JavaScript">
</p>

## 📖 Giới thiệu dự án

**Challenge Hub** là một nền tảng web ứng dụng quản lý và theo dõi thử thách cá nhân, được xây dựng bằng framework Laravel. Dự án nhằm tạo ra một cộng đồng nơi mọi người có thể tham gia các thử thách đa dạng để phát triển bản thân, cải thiện sức khỏe, và đạt được các mục tiêu trong cuộc sống.

## 👥 Thành viên nhóm

- **Đoàn Công Việt** - *Leader* 👑
- **Nguyễn Thị Ngọc Anh** 👩‍💻
- **Nguyễn Tiến Nhựt** 👨‍💻

## 🛠️ Công nghệ sử dụng

### Backend
- **Laravel 11** - Framework PHP chính
- **PHP 8.2+** - Ngôn ngữ lập trình backend
- **MySQL** - Hệ quản trị cơ sở dữ liệu

### Frontend
- **Bootstrap 5** - Framework CSS responsive
- **Blade Templates** - Template engine của Laravel
- **JavaScript** - Tương tác phía client

### Công cụ phát triển
- **Composer** - Quản lý dependencies PHP
- **NPM** - Quản lý dependencies JavaScript
- **Git** - Hệ thống quản lý phiên bản

## 🎯 Mô tả dự án

Challenge Hub là một ứng dụng web toàn diện giúp người dùng:

### Đối với Người dùng thông thường:
- Khám phá và tham gia các thử thách đa dạng trong 12 danh mục khác nhau
- Theo dõi tiến độ hoàn thành thử thách hàng ngày
- Tham gia vào các nhóm cộng đồng
- Tìm kiếm thử thách theo tên hoặc danh mục

### Đối với UserAdmin:
- Tạo và quản lý nhóm cộng đồng
- Thêm thành viên vào nhóm
- Gán thử thách cho nhóm
- Gửi thông báo đến thành viên

### Đối với Admin:
- Quản lý toàn bộ hệ thống
- CRUD danh mục và thử thách
- Quản lý tài khoản người dùng

## 📋 Các danh mục thử thách

1. **Học tập** 📚 - Phát triển kiến thức và kỹ năng học tập
2. **Sức khỏe** 💪 - Cải thiện thể chất và tinh thần
3. **Phát triển bản thân** 🧠 - Nâng cao khả năng cá nhân
4. **Kỹ năng** 🛠️ - Học các kỹ năng thực tế
5. **Thể thao** 🏃 - Hoạt động thể chất và rèn luyện
6. **Thói quen tốt** 🌱 - Xây dựng thói quen tích cực
7. **Tài chính** 💰 - Quản lý tài chính cá nhân
8. **Mối quan hệ** 👥 - Xây dựng mối quan hệ lành mạnh
9. **Nghệ thuật** 🎨 - Phát triển khả năng sáng tạo
10. **Công nghệ** 💻 - Theo kịp xu hướng công nghệ
11. **Môi trường** 🌍 - Bảo vệ môi trường sống
12. **Du lịch** ✈️ - Khám phá và trải nghiệm

## 🚀 Tính năng chính

### 🔐 Hệ thống xác thực
- Đăng ký/Đăng nhập tài khoản
- Phân quyền người dùng (User, UserAdmin, Admin)
- Bảo mật thông tin cá nhân

### 🎯 Quản lý thử thách
- Hiển thị thử thách theo danh mục
- Chi tiết thử thách với mô tả và độ khó
- Bắt đầu và theo dõi tiến độ thử thách
- Đánh giá độ khó (Dễ, Trung bình, Khó)

### 👥 Quản lý nhóm
- Tạo nhóm cộng đồng
- Mời và quản lý thành viên
- Gán thử thách cho nhóm
- Hệ thống thông báo

### 🔍 Tìm kiếm thông minh
- Tìm kiếm theo tên thử thách
- Lọc theo danh mục
- Không phân biệt dấu và hoa thường

### 📱 Giao diện responsive
- Thiết kế responsive cho mọi thiết bị
- Giao diện thân thiện và dễ sử dụng
- Phân trang đẹp mắt

## 🗄️ Cấu trúc cơ sở dữ liệu

### Các bảng chính:
- `users` - Thông tin người dùng
- `categories` - Danh mục thử thách
- `challenges` - Thông tin thử thách
- `challenge_progress` - Tiến độ hoàn thành
- `groups` - Nhóm cộng đồng
- `notifications` - Hệ thống thông báo

## 📦 Cài đặt và chạy dự án

### Yêu cầu hệ thống:
- PHP 8.2 hoặc cao hơn
- Composer
- MySQL 8.0+
- Node.js & NPM

### Các bước cài đặt:

1. **Clone repository:**
```bash
git clone <repository-url>
cd challenge-hub
```

2. **Cài đặt dependencies PHP:**
```bash
composer install
```

3. **Cài đặt dependencies JavaScript:**
```bash
npm install
```

4. **Cấu hình môi trường:**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Cấu hình database trong file .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=challenge_hub
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Chạy migration và seed:**
```bash
php artisan migrate:fresh --seed
```

7. **Chạy ứng dụng:**
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 🎨 Demo

### Trang chủ
![Home Page](screenshots/home.png)

### Danh sách thử thách
![Challenges](screenshots/challenges.png)

### Chi tiết thử thách
![Challenge Detail](screenshots/challenge-detail.png)

## 📈 Tình trạng dự án

- ✅ Hoàn thành: Hệ thống xác thực, quản lý thử thách, quản lý nhóm
- ✅ Hoàn thành: Giao diện responsive, tìm kiếm thông minh
- ✅ Hoàn thành: Phân trang, hệ thống thông báo
- 🔄 Đang phát triển: Tính năng nâng cao (API, mobile app)

## 🤝 Đóng góp

Chúng tôi hoan nghênh mọi đóng góp! Vui lòng:

1. Fork dự án
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📄 Giấy phép

Dự án này được phân phối dưới giấy phép MIT. Xem file `LICENSE` để biết thêm chi tiết.

## 📞 Liên hệ

- **Email:** challengehub@example.com
- **GitHub:** [https://github.com/your-username/challenge-hub](https://github.com/your-username/challenge-hub)

---

<p align="center">Được phát triển với ❤️ bởi Team Challenge Hub</p>

---

## 🆕 Cập nhật mới của dự án

Phần dưới đây bổ sung các tính năng và kiến trúc mới nhất đang chạy trong hệ thống hiện tại.

### 1) Hệ thống Goal cá nhân + AI Sub-goals

- Người dùng có thể tạo **Mục tiêu chính (Goal)** và hệ thống sẽ sinh **Mục tiêu phụ (Sub-goals)** bằng AI.
- AI generate theo **đúng từng Goal user nhập**, không dùng kế hoạch chung:
  - `title`
  - `description`
  - `duration_days` (thời hạn theo ngày)
- Sub-goals được ràng buộc theo `goal_id` cụ thể để tránh xung đột giữa các Goal khác nhau.

### 2) Submit Proof và hoàn thành Sub-goal

- User nộp minh chứng (`text` / `image`) cho từng sub-goal.
- Chỉ được hoàn thành sub-goal khi đã có proof.
- Luồng hoàn thành được xử lý transaction để đồng bộ:
  - trạng thái sub-goal
  - trạng thái goal cha
  - `last_completed_date`

### 3) Ràng buộc hoàn thành theo ngày (Daily Constraint)

- Đã áp dụng logic giới hạn theo ngày để tránh hoàn thành hàng loạt trong một lần.
- Với luồng AI task trong challenge progress:
  - Mỗi ngày chỉ hoàn thành tối đa **1 task**
  - Backend chặn cứng (không thể bypass chỉ bằng UI)
  - UI hiển thị trạng thái khóa task còn lại đến ngày hôm sau

### 4) Challenge AI Roadmap cá nhân hóa

- Người dùng nhập trình độ hiện tại (`current_level`) để AI sinh roadmap phù hợp.
- Hệ thống hỗ trợ:
  - OpenAI roadmap
  - Fallback nội bộ khi AI bên ngoài lỗi
- Task AI hỗ trợ upload ảnh minh chứng và cập nhật tiến độ tự động.

### 5) Tích hợp LM Studio (Local/Remote)

Hệ thống có thể dùng LM Studio làm AI backend qua endpoint tương thích OpenAI:

- `LMSTUDIO_BASE_URL`
- `LMSTUDIO_CHAT_ENDPOINT`
- `LMSTUDIO_MODEL`
- `LMSTUDIO_CONNECT_TIMEOUT`
- `LMSTUDIO_TIMEOUT`

Ví dụ cấu hình:

```env
LMSTUDIO_BASE_URL=http://127.0.0.1:1234
LMSTUDIO_CHAT_ENDPOINT=/v1/chat/completions
LMSTUDIO_MODEL=google/gemma-3-4b
LMSTUDIO_CONNECT_TIMEOUT=10
LMSTUDIO_TIMEOUT=180
```

> Lưu ý: LM Studio phải **đang load model** thì API generate mới hoạt động. Tránh dùng cổng `8000` nếu bạn đang chạy Laravel bằng `php artisan serve`, vì cổng đó thường là web app chứ không phải LM Studio.

---

## 🧩 Kiến trúc module hiện tại

### Backend Core

- `app/Http/Controllers`
  - Xử lý request/response cho auth, challenge, goals, group, admin.
- `app/Models`
  - Định nghĩa entity và quan hệ dữ liệu (User, Goal, SubGoal, Challenge, Group, Notification...).
- `app/Services`
  - Chứa nghiệp vụ AI/fallback, tách khỏi controller để dễ mở rộng.
- `app/Http/Middleware`
  - Phân quyền truy cập theo role (`admin`, `useradmin`).

### Routing

- `routes/web.php` gom toàn bộ route theo nhóm:
  - public pages
  - auth
  - challenge + ai roadmap
  - admin
  - useradmin
  - goals/sub-goals APIs

### Presentation

- `resources/views`
  - `shop/*`: giao diện người dùng
  - `admin/*`: màn hình quản trị admin
  - `useradmin/*`: màn hình quản trị nhóm
  - `goals/*`: màn hình quản lý mục tiêu cá nhân

### Data layer

- `database/migrations`
  - Quản lý schema evolution, bao gồm các bảng AI roadmap, goals/sub-goals/proofs, groups, progress...

---

## 🔄 Luồng nghiệp vụ chính

### A. Goal AI Flow

1. User tạo Goal trong `goals/create`
2. Backend lưu Goal với `duration_days`
3. Gọi `GoalAIService` để generate sub-goals theo đúng goal đó
4. Lưu sub-goals theo `goal_id`

### B. Proof & Complete Sub-goal Flow

1. User submit proof
2. Backend validate ownership + dữ liệu proof
3. User complete sub-goal
4. Backend kiểm tra rule + đồng bộ goal cha

### C. Challenge AI Task Flow

1. User bắt đầu challenge
2. Tạo AI roadmap cá nhân hóa
3. Hoàn thành task bằng proof image
4. Cập nhật progress và enforce giới hạn theo ngày

---

## 🧪 Hướng dẫn kiểm tra nhanh sau khi pull code mới

1. Chạy migrate:
```bash
php artisan migrate
```

2. Clear config/cache:
```bash
php artisan config:clear
php artisan cache:clear
```

3. Kiểm tra AI endpoint:
```bash
curl http://<lmstudio-host>:<port>/v1/models
```

4. Test các luồng:
- Tạo Goal mới -> AI sinh sub-goals
- Submit proof -> complete sub-goal
- Challenge progress -> mỗi ngày chỉ complete tối đa 1 AI task

---

## 📚 Tài liệu giải thích codebase

Để người mới vào dự án có thể nắm nhanh toàn bộ cấu trúc và chức năng từng module, xem thêm:

- `docs/CODEBASE_EXPLAINER_VI.md`

Tài liệu này mô tả:
- ý nghĩa từng thư mục chính
- vai trò từng nhóm file
- các luồng nghiệp vụ cốt lõi
- gợi ý cách trình bày khi review/bảo vệ dự án
