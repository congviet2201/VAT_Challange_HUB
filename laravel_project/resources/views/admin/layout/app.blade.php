<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Challenge Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .sidebar {
            background-color: #1a1a2e;
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
            color: #00d4ff !important;
            font-weight: bold;
            margin-bottom: 30px;
            display: block;
            text-align: center;
        }

        .sidebar .nav-link {
            color: #b0b0b0;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #16213e;
            color: #00d4ff;
            border-left-color: #00d4ff;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        main {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 8px 8px 0 0 !important;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }

            main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <nav class="sidebar">
        <div class="navbar-brand">🎯 Admin Panel</div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.challenges.index') }}">
                    <i class="bi bi-chat-square-text"></i> Quản Lý Thử Thách
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> Quản Lý User
                </a>
            </li>
            <li class="nav-item border-top pt-3 mt-3">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="bi bi-house"></i> Quay Về Trang Chủ
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start border-0">
                        <i class="bi bi-box-arrow-right"></i> Đăng Xuất
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✅ Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>❌ Lỗi!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
