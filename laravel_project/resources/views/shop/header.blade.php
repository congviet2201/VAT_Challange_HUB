<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="{{ route('home') }}">
            Challenge Hub
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <form class="mx-auto flex-grow-1" action="{{ route('search') }}" method="GET">
                <input class="form-control form-control-lg" type="search" name="query" value="{{ request('query') }}" placeholder="Tìm kiếm thử thách...">
            </form>

            <div class="ms-auto d-flex gap-3 align-items-center">
                <a href="{{ route('about') }}" class="text-dark text-decoration-none">Giới thiệu</a>
                <a href="{{ route('contact') }}" class="text-dark text-decoration-none">Liên hệ</a>

                @if (Auth::check())
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.challenges.index') }}" class="btn btn-info btn-sm fw-bold">
                            <i class="bi bi-gear"></i> Quản lý
                        </a>
                    @elseif (Auth::user()->role === 'useradmin')
                        <a href="{{ route('useradmin.groups.index') }}" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-people"></i> Nhóm
                        </a>
                    @else
                        <a href="{{ route('user.groups.index') }}" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-people"></i> Nhóm
                        </a>
                    @endif

                    <span class="text-dark">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="btn btn-primary btn-sm">Đăng nhập</a>
                    <a href="{{ route('auth.register') }}" class="btn btn-outline-primary btn-sm">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>
</nav>
