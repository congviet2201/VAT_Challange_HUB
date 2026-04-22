<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
            Challenge Hub
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <form class="search-form mx-auto flex-grow-1 px-lg-4" action="{{ route('search') }}" method="GET">
                <div class="input-group search-input-group">
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
                    <button class="btn btn-primary" type="submit">Tìm</button>
                </div>
            </form>

            <div class="ms-auto d-flex gap-2 align-items-center flex-wrap">
                <a href="{{ route('challenges') }}" class="btn btn-outline-primary btn-sm px-2 py-1">Thử thách</a>
                <a href="{{ route('about') }}" class="btn btn-outline-primary btn-sm px-2 py-1">Giới thiệu</a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm px-2 py-1">Liên hệ</a>
                <a href="{{ route('goals.index') }}" class="btn btn-success btn-sm px-2 py-1">Mục tiêu</a>
                @if (Auth::check())
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.challenges.index') }}" class="btn btn-info btn-sm px-2 py-1">
                            <i class="bi bi-gear"></i> Quản lý
                        </a>
                    @elseif (Auth::user()->role === 'useradmin')
                        <a href="{{ route('useradmin.groups.index') }}" class="btn btn-primary btn-sm px-2 py-1">
                            <i class="bi bi-people"></i> Nhóm
                        </a>
                    @else
                        <a href="{{ route('user.groups.index') }}" class="btn btn-primary btn-sm px-2 py-1">
                            <i class="bi bi-people"></i> Nhóm
                        </a>
                    @endif

                    <a href="{{ route('profile') }}" class="btn btn-secondary btn-sm px-2 py-1">
                        <i class="bi bi-person"></i> {{ Auth::user()->name }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm px-2 py-1">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="btn btn-primary btn-sm px-2 py-1">Đăng nhập</a>
                    <a href="{{ route('auth.register') }}" class="btn btn-outline-primary btn-sm px-2 py-1">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>
</nav>
