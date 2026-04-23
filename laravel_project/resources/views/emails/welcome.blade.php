{{-- File purpose: resources/views/emails/welcome.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chào mừng đến với Challenge Hub</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h1>Chào {{ $user->name }}!</h1>
    <p>Chúc mừng bạn đã đăng ký thành công tại Challenge Hub.</p>
    <p>Giờ bạn có thể bắt đầu thử thách và theo dõi tiến độ mỗi ngày.</p>
    <p>Chúc bạn hoàn thành mục tiêu và có trải nghiệm thú vị!</p>
</body>
</html>
