@extends('shop.layout.app')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- TIÊU ĐỀ -->
            <div class="text-center mb-5">
                <h1>Đăng ký tài khoản</h1>
                <p class="text-muted">Tham gia cộng đồng người theo đuổi những thử thách tuyệt vời</p>
            </div>

            <!-- FORM CARD -->
            <div class="card shadow">
                <div class="card-body p-4">

                    <!-- HIỂN THỊ LỖI NẾU CÓ -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <!-- HIỂN THỊ THÔNG BÁO THÀNH CÔNG -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- FORM ĐĂNG KÝ -->
                    <form action="{{ route('auth.register') }}" method="POST">
                        @csrf

                        <!-- TÊN INPUT -->
                        <div class="mb-3">
                            <label class="form-label">Tên của bạn</label>
                            <input
                                type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Nhập tên của bạn"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- EMAIL INPUT -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Nhập email của bạn"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PASSWORD INPUT -->
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input
                                type="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                                required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONFIRM PASSWORD INPUT -->
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input
                                type="password"
                                class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation"
                                placeholder="Xác nhận mật khẩu"
                                required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Đăng ký
                        </button>

                    </form>

                    <hr>

                    <!-- LINK ĐẾN TRANG ĐĂNG NHẬP -->
                    <p class="text-center text-muted">
                        Đã có tài khoản?
                        <a href="{{ route('auth.login') }}" class="text-primary text-decoration-none">
                            Đăng nhập tại đây
                        </a>
                    </p>

                </div>
            </div>

            <!-- LINK QUAY LẠI TRANG CHỦ -->
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    ← Quay lại trang chủ
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
