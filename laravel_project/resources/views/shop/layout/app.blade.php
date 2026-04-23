<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --light-bg: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            padding: 20px 0;
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #007bff !important;
        }

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> ef2b80f8fcd16c42d6253d490255ae91022037ae
        .search-input-group .input-group-text {
            border-radius: 999px 0 0 999px;
        }

        .search-input-group .form-control {
            border-radius: 0;
            box-shadow: none;
        }

        .search-input-group .btn {
            border-radius: 0 999px 999px 0;
        }

        .search-input-group:focus-within .input-group-text,
        .search-input-group:focus-within .form-control,
        .search-input-group:focus-within .btn {
            border-color: #86b7fe;
        }

<<<<<<< HEAD
=======
        {{-- Cấu hình card --}}
>>>>>>> 2c48e9224a7a7d84e661d9aeac8ef80d86517bd7
=======
>>>>>>> ef2b80f8fcd16c42d6253d490255ae91022037ae
        .card {
            border: none;
            border-radius: 8px;
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

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

        .badge {
            border-radius: 20px;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
        /* TASK CARDS */
=======
>>>>>>> ef2b80f8fcd16c42d6253d490255ae91022037ae
        .bg-light-success {
            background-color: #f0fdf4 !important;
        }

        .complete-task-btn {
            transition: all 0.3s ease;
        }

        .complete-task-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

<<<<<<< HEAD
        /* FOOTER */
=======
>>>>>>> NgocAnh/Goals
=======
        {{-- Cấu hình footer --}}
>>>>>>> 2c48e9224a7a7d84e661d9aeac8ef80d86517bd7
=======
>>>>>>> ef2b80f8fcd16c42d6253d490255ae91022037ae
        footer {
            background-color: #0b1120;
            color: white;
            padding: 40px 0;
            margin-top: auto;
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

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> ef2b80f8fcd16c42d6253d490255ae91022037ae
        @media (max-width: 991px) {
            .search-form {
                margin: 1rem 0;
                padding: 0;
<<<<<<< HEAD
=======
        {{-- Responsive cho mobile --}}
        @media (max-width: 768px) {
            .navbar form {
                display: none; {{-- Ẩn form search trên mobile --}}
>>>>>>> 2c48e9224a7a7d84e661d9aeac8ef80d86517bd7
=======
>>>>>>> ef2b80f8fcd16c42d6253d490255ae91022037ae
            }
        }

        @media (max-width: 768px) {
            .card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    @include('shop.header')

    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Lỗi!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Thông tin!</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <main class="container">
        @yield('content')
    </main>

    @include('shop.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
