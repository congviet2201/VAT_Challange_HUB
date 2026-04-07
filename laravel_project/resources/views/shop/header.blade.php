<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <!-- LOGO -->
        <a class="navbar-brand fw-bold fs-3 text-primary" href="{{ route('home') }}">
            Challenge Hub
        </a>

        <!-- TOGGLER CHO MOBILE -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU & SEARCH -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- SEARCH BAR: Tìm kiếm thử thách (ẩn trên mobile) -->
            <form class="d-none d-lg-flex mx-auto">
                <input class="form-control form-control-lg" type="search" placeholder="Tìm kiếm thử thách...">
            </form>

            <!-- MENU: Giới thiệu, Liên hệ, Đăng nhập/Đăng ký/Tên user -->
            <div class="ms-auto d-flex gap-3 align-items-center">
                <a href="{{ route('about') }}" class="text-dark text-decoration-none">Giới thiệu</a>
                <a href="{{ route('contact') }}" class="text-dark text-decoration-none">Liên hệ</a>

                <!-- KIỂM TRA ĐĂNG NHẬP -->
                @if (Auth::check())
                    <span class="text-dark">👤 {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="text-dark text-decoration-none">Đăng nhập</a>
                    <a href="{{ route('auth.register') }}" class="btn btn-primary">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>
</nav>
