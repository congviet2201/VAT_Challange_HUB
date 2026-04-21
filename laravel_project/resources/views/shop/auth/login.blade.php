@extends('shop.layout.app')
{{-- Kế thừa layout chính của shop --}}

@section('content')
{{-- Bắt đầu nội dung chính của trang --}}

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                {{-- Tiêu đề và mô tả --}}
                <div class="text-center mb-5">
                    <h1>Đăng nhập</h1>
                    <p class="text-muted">Bước vào thế giới của những thử thách không giới hạn</p>
                </div>

                {{-- Card chứa form đăng nhập --}}
                <div class="card shadow">
                    <div class="card-body p-4">
                        {{-- Hiển thị lỗi validation --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Hiển thị thông báo thành công --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="Nhập email của bạn" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" placeholder="Nhập mật khẩu" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ tôi lần tới
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Đăng nhập
                            </button>
                        </form>

                        <hr>

                        <p class="text-center text-muted">
                            Bạn chưa có tài khoản?
                            <a href="{{ route('auth.register') }}" class="text-primary text-decoration-none">
                                Đăng ký ngay
                            </a>
                        </p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                        ← Quay lại trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
