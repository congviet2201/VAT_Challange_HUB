{{-- Header/Navbar của trang shop --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        {{-- Logo và tên trang --}}
        <a class="navbar-brand fw-bold fs-3 text-primary" href="{{ route('home') }}">
            Challenge Hub
        </a>

        {{-- Nút toggle cho mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nội dung navbar --}}
        <div class="collapse navbar-collapse" id="navbarContent">
            {{-- Form tìm kiếm ở giữa --}}
            <form class="mx-auto flex-grow-1" action="{{ route('search') }}" method="GET">
                <input class="form-control form-control-lg"
                       type="search"
                       name="query"
                       value="{{ request('query') }}"
                       placeholder="Tìm kiếm thử thách...">
            </form>

            {{-- Menu bên phải --}}
            <div class="ms-auto d-flex gap-3 align-items-center">
                {{-- Link tĩnh --}}
                <a href="{{ route('about') }}" class="text-dark text-decoration-none">Giới thiệu</a>
                <a href="{{ route('contact') }}" class="text-dark text-decoration-none">Liên hệ</a>

                {{-- Menu cho người dùng đã đăng nhập --}}
                @if (Auth::check())
                    {{-- Nút quản lý theo vai trò --}}
                    @if (Auth::user()->role === 'admin')
                        {{-- Admin: quản lý thử thách --}}
                        <a href="{{ route('admin.challenges.index') }}" class="btn btn-info btn-sm fw-bold">
                            <i class="bi bi-gear"></i> Quản lý
                        </a>
                    @elseif (Auth::user()->role === 'useradmin')
                        {{-- Useradmin: quản lý nhóm --}}
                        <a href="{{ route('useradmin.groups.index') }}" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-people"></i> Nhóm
                        </a>
                    @else
                        {{-- User thường: xem nhóm --}}
                        <a href="{{ route('user.groups.index') }}" class="btn btn-primary btn-sm fw-bold">
                            <i class="bi bi-people"></i> Nhóm
                        </a>
                    @endif

                    {{-- Nút profile người dùng --}}
                    <a href="{{ route('profile') }}" class="btn btn-outline-dark btn-sm">
                        {{ Auth::user()->name }}
                    </a>
                    {{-- Nút đăng xuất --}}
                    <form action="{{ route('logout') }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Đăng xuất</button>
                    </form>
                @else
                    {{-- Menu cho khách chưa đăng nhập --}}
                    <a href="{{ route('auth.login') }}" class="btn btn-primary btn-sm">Đăng nhập</a>
                    <a href="{{ route('auth.register') }}" class="btn btn-outline-primary btn-sm">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>
</nav>
