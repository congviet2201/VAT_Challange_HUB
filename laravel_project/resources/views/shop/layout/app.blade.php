{{-- Layout chính của ứng dụng shop --}}
{{-- File này chứa cấu trúc HTML chung cho tất cả các trang shop --}}

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Hub</title>

    {{-- Bootstrap CSS từ CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS tùy chỉnh cho giao diện --}}
    <style>
        {{-- Biến CSS để dễ quản lý màu sắc --}}
        :root {
            --primary: #007bff;    {{-- Màu chính (xanh dương) --}}
            --success: #28a745;    {{-- Màu thành công (xanh lá) --}}
            --warning: #ffc107;    {{-- Màu cảnh báo (vàng) --}}
            --danger: #dc3545;     {{-- Màu lỗi (đỏ) --}}
            --light-bg: #f8f9fa;   {{-- Màu nền sáng --}}
        }

        {{-- Reset margin và padding mặc định --}}
        * {
            margin: 0;
            padding: 0;
        }

        {{-- Cấu hình body --}}
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh; {{-- Chiều cao tối thiểu 100% viewport --}}
        }

        {{-- Cấu hình main content --}}
        main {
            flex: 1;           {{-- Chiếm hết không gian còn lại --}}
            padding: 20px 0;   {{-- Khoảng cách trên dưới --}}
        }

        {{-- Cấu hình navbar --}}
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); {{-- Đổ bóng nhẹ --}}
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #007bff !important;
        }

        {{-- Cấu hình card --}}
        .card {
            border: none;
            border-radius: 8px;
            transition: 0.3s ease; {{-- Hiệu ứng chuyển đổi mượt mà --}}
        }

        .card:hover {
            transform: translateY(-5px); {{-- Nâng card lên khi hover --}}
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover; {{-- Cắt ảnh để vừa khung --}}
            border-radius: 8px 8px 0 0;
        }

        {{-- Cấu hình button --}}
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        {{-- Cấu hình badge --}}
        .badge {
            border-radius: 20px;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }

        {{-- Cấu hình footer --}}
        footer {
            background-color: #0b1120; {{-- Màu nền tối --}}
            color: white;
            padding: 40px 0;
            margin-top: auto; {{-- Đẩy footer xuống cuối --}}
            text-align: center;
        }

        footer h5, footer h6 {
            color: #ffffff;
            font-weight: 700;
        }

        footer p, footer ul {
            color: #c0c0c0;
        }

        footer .text-muted {
            color: #c0c0c0 !important;
        }

        footer a {
            color: #a8a8a8;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
            transition: 0.3s ease;
        }

        footer small {
            color: #b0b0b0;
        }

        {{-- Responsive cho mobile --}}
        @media (max-width: 768px) {
            .navbar form {
                display: none; {{-- Ẩn form search trên mobile --}}
            }

            .card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    {{-- Include header --}}
    @include('shop.header')

    {{-- Container cho thông báo --}}
    <div class="container mt-3">
        {{-- Thông báo thành công --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Thông báo lỗi --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Lỗi!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Thông báo thông tin --}}
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Thông tin!</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Nội dung chính của trang --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- Include footer --}}
    @include('shop.footer')

    {{-- Bootstrap JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
