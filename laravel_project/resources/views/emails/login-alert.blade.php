<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo đăng nhập</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #222;">
    <h2>Xin chào {{ $user->name }},</h2>
    <p>Tài khoản của bạn vừa đăng nhập thành công vào Challenge Hub.</p>
    <p><strong>Thời gian:</strong> {{ $loginTime }}</p>
    <p><strong>Địa chỉ IP:</strong> {{ $ipAddress }}</p>
    <p>Nếu đây không phải là bạn, hãy đổi mật khẩu ngay.</p>
</body>
</html>
