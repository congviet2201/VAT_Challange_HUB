@extends('shop.layout.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow p-4" style="width: 400px; border-radius: 15px;">
        <h3 class="text-center mb-3">Đăng ký</h3>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên</label>
                <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Nhập email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Vai trò</label>
                <select name="role" class="form-select" required>
                    <option value="user">Người dùng</option>
                    <option value="useradmin">Quản trị nhóm</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('auth.login') }}" class="text-decoration-none">Đã có tài khoản?</a>
        </div>
    </div>
</div>
@endsection
