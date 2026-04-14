# 👥 UserAdmin Features - Challenge Hub

## 🎯 Tính Năng Mới cho UserAdmin (Trưởng Nhóm)

### 1. **Quản Lý Nhóm** (Group Management)
UserAdmin có thể tạo và quản lý các nhóm để gom nhóm những người dùng lại với nhau.

**Các chức năng:**
- ✅ **Tạo nhóm mới** - Nhập tên và mô tả nhóm
- ✅ **Chỉnh sửa nhóm** - Cập nhật tên và mô tả
- ✅ **Xem chi tiết nhóm** - Xem danh sách thành viên hiện tại
- ✅ **Kích hoạt/Vô hiệu hóa nhóm** - Quản lý trạng thái hoạt động

**URL:** `http://localhost:8000/useradmin/groups`

---

### 2. **Thêm/Xóa User Vào Nhóm** (User Management)
UserAdmin có thể thêm người dùng vào nhóm và xóa họ ra khỏi nhóm.

**Các chức năng:**
- ✅ **Thêm thành viên** - Chọn người dùng từ danh sách khả dụng
- ✅ **Xóa thành viên** - Loại bỏ người dùng khỏi nhóm
- ✅ **Xem danh sách** - Chỉ UserAdmin là người tạo mới có thể xem

**URL:** `http://localhost:8000/useradmin/groups/{id}/add-users`

---

### 3. **Gửi Thông Báo Nhóm** (Send Notifications)
UserAdmin có thể gửi thông báo tới tất cả thành viên của nhóm để nhắc nhở và chia sẻ thông tin.

**Các chức năng:**
- ✅ **Tạo thông báo** - Nhập tiêu đề và nội dung thông báo
- ✅ **Gửi theo nhóm** - Thông báo sẽ đến tất cả thành viên nhóm
- ✅ **Xem lịch sử** - Xem danh sách thành viên nhận thông báo
- ✅ **Xóa thông báo** - Xóa các thông báo cũ

**URL:** `http://localhost:8000/useradmin/notifications`

---

### 4. **Gán Thử Thách Cho Nhóm** (Assign Challenges to Group)
UserAdmin có thể gán các thử thách từ hệ thống cho nhóm của mình để chia sẻ với các thành viên.

**Các chức năng:**
- ✅ **Xem danh sách thử thách** - Xem tất cả thử thách đã gán cho nhóm, được sắp xếp theo danh mục
- ✅ **Thêm thử thách** - Chọn nhiều thử thách từ các danh mục khác nhau để thêm vào nhóm
- ✅ **Xóa thử thách** - Loại bỏ thử thách khỏi nhóm
- ✅ **Xem chi tiết** - Hiển thị độ khó, mô tả và thời gian hàng ngày

**URL:** `http://localhost:8000/useradmin/groups/{id}/challenges`

**Cách sử dụng:**
1. Vào chi tiết nhóm
2. Click nút "📚 Quản Lý Thử Thách"
3. Click "Thêm Thử Thách" để chọn challenges
4. Các thử thách sẽ được hiển thị theo danh mục (Học tập, Sức khỏe, v.v)
5. Chọn checkbox của các thử thách cần thêm
6. Click "Thêm Thử Thách" để hoàn thành

---

## 🔐 Tài Khoản Test

Để test các tính năng UserAdmin, bạn có thể sử dụng các tài khoản sau:

```
📧 Email: useradmin1@gmail.com
🔐 Password: password
Vai trò: Trưởng Nhóm A

📧 Email: useradmin2@gmail.com
🔐 Password: password
Vai trò: Trưởng Nhóm B
```

Hoặc các tài khoản user để test thêm vào nhóm:
```
user1@gmail.com / password
user2@gmail.com / password
user3@gmail.com / password
```

---

## 🎨 Giao Diện

### Sidebar Navigation
UserAdmin sẽ thấy sidebar gồm:
- 🏠 Trang Chủ (Home)
- 👥 Quản Lý Nhóm (Group Management)
- 🔔 Thông Báo (Notifications)
- 🚪 Đăng Xuất (Logout)

### Header Button
Khi đăng nhập với tài khoản UserAdmin, sẽ có nút "👥 Nhóm" ở header để nhanh chóng truy cập.

---

## 📋 Quy Trình Sử Dụng

### 1️⃣ Tạo Nhóm
1. Đăng nhập với tài khoản UserAdmin
2. Click "Quản Lý Nhóm" trên Sidebar
3. Click "Tạo Nhóm Mới"
4. Nhập tên và mô tả nhóm
5. Click "Tạo Nhóm"

### 2️⃣ Thêm User Vào Nhóm
1. Vào danh sách nhóm
2. Chọn nhóm và click "Thêm User"
3. Chọn người dùng muốn thêm (checkbox)
4. Click "Thêm Thành Viên"

### 3️⃣ Gửi Thông Báo
1. Click "Thông Báo" trên Sidebar
2. Click "Gửi Thông Báo"
3. Chọn nhóm đích
4. Nhập tiêu đề và nội dung
5. Click "Gửi Thông Báo"

---

## 🛡️ Quyền Hạn & Bảo Mật

- ✅ UserAdmin chỉ có thể quản lý **các nhóm mà chính họ tạo**
- ✅ Không thể chỉnh sửa nhóm của người khác
- ✅ Thông báo chỉ được gửi tới nhóm của chính mình
- ✅ Middleware `useradmin` bảo vệ tất cả các route
- ✅ Kiểm tra quyền sở hữu trên mỗi hành động

---

## 🗄️ Cơ Sở Dữ Liệu

### Bảng `groups`
```sql
- id (PRIMARY KEY)
- name (string) - Tên nhóm
- description (text, nullable) - Mô tả nhóm
- created_by (FOREIGN KEY → users.id) - UserAdmin người tạo
- is_active (boolean) - Trạng thái hoạt động
- timestamps - created_at, updated_at
```

### Bảng `notifications`
```sql
- id (PRIMARY KEY)
- group_id (FOREIGN KEY → groups.id) - Nhóm nhận thông báo
- created_by (FOREIGN KEY → users.id) - UserAdmin gửi thông báo
- title (string) - Tiêu đề thông báo
- message (text) - Nội dung thông báo
- timestamps - created_at, updated_at
```

### Bảng `group_user`
```sql
- id (PRIMARY KEY)
- user_id (FOREIGN KEY → users.id) - Người dùng
- group_id (FOREIGN KEY → groups.id) - Nhóm
- unique(user_id, group_id) - Mỗi user chỉ tham gia nhóm 1 lần
- timestamps - created_at, updated_at
```

### Bảng `group_challenge`
```sql
- id (PRIMARY KEY)
- group_id (FOREIGN KEY → groups.id) - Nhóm
- challenge_id (FOREIGN KEY → challenges.id) - Thử thách
- unique(group_id, challenge_id) - Mỗi thử thách chỉ gán nhóm 1 lần
- timestamps - created_at, updated_at
```

---

## 🚀 API Routes

### Groups
- `GET /useradmin/groups` - Danh sách nhóm của UserAdmin
- `GET /useradmin/groups/create` - Form tạo nhóm
- `POST /useradmin/groups` - Lưu nhóm mới
- `GET /useradmin/groups/{id}` - Xem chi tiết nhóm
- `GET /useradmin/groups/{id}/edit` - Form chỉnh sửa
- `PUT /useradmin/groups/{id}` - Cập nhật nhóm
- `POST /useradmin/groups/{id}/toggle` - Kích hoạt/Vô hiệu nhóm
- `GET /useradmin/groups/{id}/add-users` - Form thêm users
- `POST /useradmin/groups/{id}/add-users` - Lưu users vào nhóm
- `POST /useradmin/groups/{id}/remove-user/{user_id}` - Xóa user khỏi nhóm
- `GET /useradmin/groups/{id}/challenges` - Danh sách challenges trong nhóm
- `GET /useradmin/groups/{id}/add-challenges` - Form chọn challenges
- `POST /useradmin/groups/{id}/add-challenges` - Thêm challenges vào nhóm
- `POST /useradmin/groups/{id}/remove-challenge/{challenge_id}` - Xóa challenge khỏi nhóm

### Notifications
- `GET /useradmin/notifications` - Danh sách thông báo đã gửi
- `GET /useradmin/notifications/create` - Form gửi thông báo
- `POST /useradmin/notifications` - Lưu thông báo
- `GET /useradmin/notifications/{id}` - Xem chi tiết thông báo
- `DELETE /useradmin/notifications/{id}` - Xóa thông báo

---

## 💾 Models & Relationships

### Group Model
```php
- belongsTo(User, 'created_by') // UserAdmin người tạo
- belongsToMany(User, 'group_user') // Thành viên nhóm
- belongsToMany(Challenge, 'group_challenge') // Thử thách trong nhóm
- hasMany(Notification) // Thông báo gửi tới nhóm
```

### Notification Model
```php
- belongsTo(Group) // Nhóm nhận thông báo
- belongsTo(User, 'created_by') // UserAdmin gửi
```

### Challenge Model
```php
- belongsTo(Category) // Danh mục
- hasMany(ChallengeProgress) // Tiến độ người dùng
- belongsToMany(Group, 'group_challenge') // Các nhóm được gán
```

### User Model
```php
- belongsToMany(Group, 'group_user') // Các nhóm user tham gia
- hasMany(Group, 'created_by') // Các nhóm do user tạo (UserAdmin)
- hasMany(Notification, 'created_by') // Thông báo do user gửi
```

---

## ✨ Các Tính Năng Tương Lai

- 📱 Thông báo push trên mobile
- 📊 Thống kê nhóm và hoạt động
- 🔔 Lịch sử đọc thông báo
- 👥 Phân quyền trong nhóm (role: member, moderator, admin)
- 📅 Lên lịch gửi thông báo
- 🎯 Phân loại và tag cho nhóm

---

**Phiên bản:** 1.0  
**Ngày cập nhật:** 2026-04-14  
**Tác giả:** Challenge Hub Team
