# Tài liệu giải thích toàn bộ dự án (Laravel Challenge Hub)

## 1) Cấu trúc thư mục chính

- `app/`: Business logic cốt lõi (Controller, Model, Service, Middleware, Mail).
- `routes/`: Định tuyến endpoint web/cli.
- `resources/views/`: Giao diện Blade cho user/admin/useradmin.
- `public/js/`: JavaScript chạy phía client cho một số màn hình.
- `database/migrations/`: Định nghĩa schema DB theo từng thay đổi.
- `database/seeders/`: Dữ liệu mẫu ban đầu.
- `config/`: Cấu hình ứng dụng và tích hợp dịch vụ ngoài.
- `storage/`: log/cache/view compile runtime (không phải source chính để thuyết trình).

---

## 2) App layer (source chính)

### 2.1 Controllers (`app/Http/Controllers`)

- `AuthController.php`: Đăng ký, đăng nhập, đăng xuất; gửi `WelcomeMail` khi đăng ký.
- `HomeController.php`: Trang chủ, trang danh mục, chi tiết challenge, tìm kiếm challenge.
- `PageController.php`: Trang tĩnh (about/contact) và gửi form liên hệ.
- `ChallengeController.php`: Bắt đầu challenge, check-in, trang tiến độ, tạo AI roadmap, hoàn thành AI task.
- `GoalController.php`: CRUD theo luồng tạo goal + gọi AI sinh sub-goals theo mục tiêu user.
- `SubGoalController.php`: Nộp proof, hoàn thành sub-goal, đồng bộ trạng thái goal cha.
- `UserController.php`: Quản lý hồ sơ user và màn hình quản trị user.
- `Controller.php`: Base controller của Laravel.
- `Admin/ChallengeController.php`: CRUD challenge cho admin.
- `User/GroupController.php`: User thường xem/join/leave group.
- `UserAdmin/GroupController.php`: UserAdmin quản lý group và thành viên.
- `UserAdmin/NotificationController.php`: UserAdmin tạo/xem/xóa thông báo nhóm.

### 2.2 Models (`app/Models`)

- `User.php`: Người dùng hệ thống, role (`user`, `useradmin`, `admin`) và quan hệ nhóm/challenge.
- `Category.php`: Danh mục challenge.
- `Challenge.php`: Challenge chính (title, difficulty, daily_time...), quan hệ task/progress/AI plan.
- `Task.php`: Task chuẩn theo challenge.
- `ChallengeProgress.php`: Tiến độ người dùng theo challenge.
- `Checkin.php`: Log check-in theo ngày.
- `UserChallenge.php`: Bản ghi challenge user tham gia (luồng cũ/phụ trợ).
- `Goal.php`: Mục tiêu chính user tự đặt (`duration_days`, `status`, `last_completed_date`).
- `SubGoal.php`: Mục tiêu phụ thuộc `Goal`, có logic hoàn thành + sync goal.
- `SubGoalProof.php`: Minh chứng cho sub-goal (`type`, `content`).
- `TaskCompletion.php`: Lưu completion của task chuẩn.
- `Group.php`: Nhóm người dùng.
- `Notification.php`: Thông báo trong group.
- `ChallengeAiPlan.php`: Kế hoạch AI cá nhân hóa theo user/challenge.
- `ChallengeAiTask.php`: Task do AI tạo ra.
- `ChallengeFeedbackHistory.php`: Lưu lịch sử phản hồi AI theo tiến độ.

### 2.3 Services (`app/Services`)

- `GoalAIService.php`: Gọi LM Studio để sinh sub-goals theo từng goal cụ thể (title/description/duration).
- `ChallengeAiPlannerService.php`: Sinh AI roadmap challenge (ưu tiên OpenAI, fallback local).
- `ChallengeFeedbackService.php`: Tạo feedback theo tiến độ challenge.
- `OpenAiFeedbackService.php`: Adapter gọi OpenAI cho feedback.

### 2.4 Middleware (`app/Http/Middleware`)

- `IsAdmin.php`: Chặn route không thuộc quyền admin.
- `IsUserAdmin.php`: Chặn route không thuộc quyền useradmin.

### 2.5 Mail (`app/Mail`)

- `WelcomeMail.php`: Mail chào mừng khi user đăng ký.

### 2.6 Providers (`app/Providers`)

- `AppServiceProvider.php`: Bootstrap service/provider chung của ứng dụng.

---

## 3) Routing layer

- `routes/web.php`:
  - Public: home/challenges/category/challenge-detail/search/about/contact.
  - Auth: login/register/logout.
  - Challenge flow: start/checkin/progress/AI roadmap/AI task complete.
  - Goal flow: tạo goal, API generate sub-goals, submit proof, complete sub-goal.
  - Admin routes: quản lý user + challenge.
  - UserAdmin routes: quản lý group + notification.
- `routes/console.php`: route lệnh artisan tùy biến (nếu có).

---

## 4) Views layer (`resources/views`)

### 4.1 Layouts / Shared

- `shop/layout/app.blade.php`: Layout chung phần user.
- `shop/header.blade.php`: Navbar, search, menu role-based.
- `shop/footer.blade.php`: Footer.
- `admin/layout/app.blade.php`: Layout admin.
- `useradmin/layout/app.blade.php`: Layout useradmin.

### 4.2 Shop/User pages

- `shop/home.blade.php`: Trang chủ danh mục.
- `shop/category.blade.php`: Danh sách challenge theo category.
- `shop/challenge-detail.blade.php`: Chi tiết challenge + nút bắt đầu.
- `shop/challenge-progress.blade.php`: Tiến độ challenge + AI coach + AI tasks.
- `shop/start.blade.php`: Trang/bước bắt đầu challenge.
- `shop/profile.blade.php`: Hồ sơ user.
- `shop/groups/index.blade.php`, `shop/groups/show.blade.php`: màn hình nhóm cho user.
- `shop/pages/about.blade.php`, `shop/pages/contact.blade.php`, `shop/pages/challenges.blade.php`: trang nội dung/tìm kiếm.
- `shop/auth/login.blade.php`, `shop/auth/register.blade.php`: màn hình auth cho khu shop.

### 4.3 Goals pages

- `goals/create.blade.php`: Form tạo nhiều goal (kèm `duration_days`).
- `goals/index.blade.php`: Danh sách goal.
- `goals/show.blade.php`: Chi tiết goal, submit proof, complete sub-goal.

### 4.4 Admin pages

- `admin/users.blade.php`, `admin/users/create.blade.php`, `admin/users/edit.blade.php`: quản trị user.
- `admin/challenges/index.blade.php`, `create.blade.php`, `show.blade.php`, `edit.blade.php`: quản trị challenge.

### 4.5 UserAdmin pages

- `useradmin/groups/index.blade.php`, `create.blade.php`, `show.blade.php`, `edit.blade.php`: quản lý group.
- `useradmin/groups/add-users.blade.php`, `add-challenges.blade.php`, `challenges.blade.php`: gán user/challenge cho group.
- `useradmin/notifications/index.blade.php`, `create.blade.php`, `show.blade.php`: quản lý thông báo.

### 4.6 Other views

- `dashboard.blade.php`: dashboard user.
- `emails/welcome.blade.php`: template mail chào mừng.
- `auth/register.blade.php`: màn đăng ký legacy.

---

## 5) JavaScript client-side

- `public/js/goals/script.js`: thêm động nhiều goal trong form tạo goal.
- `resources/js/app.js`: entry JS của Laravel/Vite.
- `resources/js/bootstrap.js`: bootstrap axios/cấu hình JS mặc định.

---

## 6) Database migrations (ý nghĩa theo nhóm)

### 6.1 Core framework

- `0001_01_01_000000_create_users_table.php`: users/sessions/password reset.
- `0001_01_01_000001_create_cache_table.php`: cache.
- `0001_01_01_000002_create_jobs_table.php`: queue jobs.

### 6.2 Domain categories/challenges/tasks

- `0001_01_01_000003_create_categories_table.php`: categories.
- `0001_01_01_000004_create_challenges_table.php`: challenges.
- `2026_04_16_000000_create_tasks_table.php`: tasks chuẩn cho challenge.
- `2026_04_14_031032_add_index_to_challenges_title.php`: index tìm kiếm theo title.

### 6.3 Progress & completion

- `2025_04_07_000005_create_challenge_progress_table.php`: bảng progress challenge.
- `2025_04_14_000009_add_completed_days_and_streak_to_challenge_progress.php`: completed_days/streak.
- `2026_04_16_000001_create_task_completions_table.php`: completion task chuẩn.
- `2026_04_16_000002_create_user_challenges_table.php`: challenge theo user.
- `2025_04_07_000007_drop_challenge_checkins_table.php`: dọn bảng checkin cũ.

### 6.4 Goals / Sub-goals

- `2026_04_18_172044_create_goals_table.php`: goals.
- `2026_04_21_013605_create_sub_goals_table.php`: sub_goals.
- `2026_04_21_015404_add_status_to_sub_goals_table.php`: status sub_goals.
- `2026_04_21_015445_create_sub_goal_proofs_table.php`: proofs cho sub_goals.
- `2026_04_21_220000_add_status_to_goals_table.php`: status cho goals.
- `2026_04_21_224500_fix_add_status_to_goals_for_sqlite.php`: fix migration status goals cho sqlite.
- `2026_04_22_134056_add_last_completed_date_to_goals_table.php`: ngày hoàn thành gần nhất của goal.
- `2026_04_23_190000_add_duration_days_to_goals_table.php`: thời hạn goal để AI generate theo từng mục tiêu.

### 6.5 Group / notification

- `2026_04_14_014848_create_groups_table.php`: groups.
- `2026_04_14_014853_create_group_user_table.php`: pivot user-group.
- `2026_04_14_015756_create_group_challenge_table.php`: pivot group-challenge.
- `2026_04_14_014852_create_notifications_table.php`: notifications.
- `2026_04_15_150000_add_is_active_to_users_table.php`: cờ active user.

### 6.6 AI challenge plan

- `2026_04_21_104000_create_challenge_ai_plans_table.php`: bảng AI plans.
- `2026_04_21_104100_create_challenge_ai_tasks_table.php`: bảng AI tasks.
- `2026_04_21_111500_add_completion_fields_to_challenge_ai_tasks_table.php`: completed_at/proof fields.
- `2026_04_16_000001_create_challenge_feedback_histories_table.php`: lịch sử feedback.

---

## 7) Luồng nghiệp vụ quan trọng để thuyết trình

### 7.1 Goal AI generation theo mục tiêu riêng

1. User nhập goal tại `goals/create`.
2. `GoalController@store` lưu goal + `duration_days`.
3. Controller gọi `GoalAIService::generateSubGoalsFromAI()` với context cụ thể.
4. Service gọi LM Studio, parse JSON hợp lệ, validate `day` theo `duration_days`.
5. Sub-goals được lưu theo `goal_id` tương ứng.

### 7.2 Submit proof & complete sub-goal

1. `SubGoalController@submitProof` lưu proof theo sub-goal của chính chủ.
2. `SubGoalController@complete` kiểm tra proof + daily rule trong cùng goal.
3. `SubGoal::completeAndSyncGoal()` transaction:
   - update status sub-goal,
   - kiểm tra pending sub-goal cùng goal,
   - sync status/last_completed_date của goal cha.

### 7.3 Challenge AI roadmap

1. User bắt đầu challenge (`ChallengeController@start`).
2. User nhập current level -> `generateAiRoadmap`.
3. `ChallengeAiPlannerService` tạo plan (OpenAI/fallback) và lưu DB.
4. User upload proof để complete AI task (`completeAiTask`).
5. Controller sync lại progress theo số task hoàn thành.

---

## 8) File không cần giải thích sâu khi bảo vệ

- `vendor/`, `node_modules/`: dependency ngoài.
- `storage/framework/views/*.php`: file blade compile tự sinh.
- `bootstrap/cache/*`: cache runtime.

---
