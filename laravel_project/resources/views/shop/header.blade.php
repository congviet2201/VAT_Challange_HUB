{{-- Mục đích file: resources/views/shop/header.blade.php --}}
{{-- Khung hiển thị thanh điều hướng (header) trên cùng của các trang giao diện người dùng. --}}

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
                        name="query"
                        value="{{ request('keyword', request('query')) }}"
                        placeholder="Tìm kiếm thử thách..."
                        aria-label="Tìm kiếm thử thách"
                    >
                    <button class="btn btn-primary" type="submit">Tìm</button>
                </div>
            </form>

            <div class="ms-auto d-flex gap-2 align-items-center flex-wrap">
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm px-3 py-1 dropdown-toggle" type="button" id="headerMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-grid"></i> Menu
                    </button>
                    <ul class="dropdown-menu shadow-sm" aria-labelledby="headerMenuDropdown">
                        <li><a class="dropdown-item" href="{{ route('challenges') }}">Thử thách</a></li>
                        <li><a class="dropdown-item" href="{{ route('about') }}">Giới thiệu</a></li>
                        <li><a class="dropdown-item" href="{{ route('contact') }}">Liên hệ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-success" href="{{ route('goals.index') }}"><i class="bi bi-bullseye"></i> Mục tiêu</a></li>
                        @if (Auth::check())
                            @if (Auth::user()->role === 'admin')
                                <li><a class="dropdown-item text-info" href="{{ route('admin.challenges.index') }}"><i class="bi bi-gear"></i> Quản lý</a></li>
                            @elseif (Auth::user()->role === 'useradmin')
                                <li><a class="dropdown-item text-primary" href="{{ route('useradmin.groups.index') }}"><i class="bi bi-people"></i> Nhóm</a></li>
                            @else
                                <li><a class="dropdown-item text-primary" href="{{ route('user.groups.index') }}"><i class="bi bi-people"></i> Nhóm</a></li>
                            @endif
                        @endif
                    </ul>
                </div>
                @if (Auth::check())
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
