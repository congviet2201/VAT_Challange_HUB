<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="{{ route('home') }}">
            Challenge Hub
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <form class="search-form mx-auto flex-grow-1 px-lg-4" action="{{ route('search') }}" method="GET">
                <div class="input-group input-group-lg search-input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input
                        class="form-control border-start-0 ps-0"
                        type="search"
                        name="keyword"
                        value="{{ request('keyword', request('query')) }}"
                        placeholder="Tìm kiếm thử thách..."
                        aria-label="Tìm kiếm thử thách"
                    >
                    <button class="btn btn-primary px-4" type="submit">Tìm</button>
                </div>
            </form>

            <div class="ms-auto d-flex gap-3 align-items-center">
                <a href="{{ route('challenges') }}" class="text-dark text-decoration-none">Thử thách</a>
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
