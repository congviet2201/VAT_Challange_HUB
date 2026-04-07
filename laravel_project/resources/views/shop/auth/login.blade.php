@extends('shop.layout.app')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- TIÊU ĐỀ -->
            <div class="text-center mb-5">
                <h1>Đăng nhập</h1>
                <p class="text-muted">Bước vào thế giới của những thử thách không giới hạn</p>
            </div>

            <!-- FORM CARD -->
            <div class="card shadow">
                <div class="card-body p-4">

                    <!-- HIỂN THỊ LỖIAUX NẾU CÓ -->
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

                    <!-- FORM ĐĂNG NHẬP -->
                    <form action="{{ route('auth.login') }}" method="POST">
                        @csrf

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
                                placeholder="Nhập mật khẩu"
                                required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Đăng nhập
                        </button>

                    </form>

                    <hr>

                    <!-- LINK ĐẾN TRANG ĐĂNG KÝ -->
                    <p class="text-center text-muted">
                        Bạn chưa có tài khoản?
                        <a href="{{ route('auth.register') }}" class="text-primary text-decoration-none">
                            Đăng ký ngay
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
