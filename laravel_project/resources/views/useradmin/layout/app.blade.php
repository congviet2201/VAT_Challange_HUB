<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserAdmin - Challenge Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .sidebar {
            background-color: #2c3e50;
            color: white;
            min-height: 100vh;
            padding: 20px 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .sidebar .navbar-brand {
            color: #3498db !important;
            font-weight: bold;
            margin-bottom: 30px;
            display: block;
            text-align: center;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #34495e;
            color: #3498db;
            border-left-color: #3498db;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        main {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar-top {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 250px;
            border-bottom: 2px solid #3498db;
        }

        .navbar-top .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-logout {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #c0392b;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
                padding: 10px 0;
            }

            main {
                margin-left: 0;
            }

            .navbar-top {
                margin-left: 0;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a class="navbar-brand d-block text-center mb-4" href="/">
            <i class="bi bi-diagram-3"></i> Challenge Hub
        </a>
        <nav class="nav flex-column">
            <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="/">
                <i class="bi bi-house"></i> Trang Chủ
            </a>
            <a class="nav-link {{ Request::routeIs('useradmin.groups.*') ? 'active' : '' }}" href="{{ route('useradmin.groups.index') }}">
                <i class="bi bi-people"></i> Quản Lý Nhóm
            </a>
            <a class="nav-link {{ Request::routeIs('useradmin.notifications.*') ? 'active' : '' }}" href="{{ route('useradmin.notifications.index') }}">
                <i class="bi bi-bell"></i> Thông Báo
            </a>
            <hr style="border-color: #555; margin: 20px 0;">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-start w-100" style="border: none; background: none; color: #bdc3c7; padding: 12px 20px; text-decoration: none;">
                    <i class="bi bi-box-arrow-right"></i> Đăng Xuất
                </button>
            </form>
        </nav>
    </div>

    <!-- Navbar Top (nếu cần) -->
    <div class="navbar-top d-none d-md-flex">
        <h5 class="mb-0">Challenge Hub - UserAdmin</h5>
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            <i class="bi bi-person-circle"></i>
        </div>
    </div>

    <!-- Main Content -->
    <main class="mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
