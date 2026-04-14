@extends('admin.layout.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Chỉnh Sửa Người Dùng</h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Tên Người Dùng -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">👤 Tên Người Dùng</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="Nhập tên người dùng" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">📧 Email</label>
                            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   placeholder="Nhập email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (tuỳ chọn) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">🔐 Mật Khẩu (để trống nếu không đổi)</label>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   placeholder="Nhập mật khẩu mới hoặc bỏ trống">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">🔐 Xác Nhận Mật Khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" 
                                   placeholder="Xác nhận mật khẩu mới">
                        </div>

                        <!-- Vai Trò -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">👨‍💼 Vai Trò</label>
                            <select name="role" class="form-select form-select-lg @error('role') is-invalid @enderror" required>
                                <option value="">-- Chọn Vai Trò --</option>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>👤 Thành Viên</option>
                                <option value="useradmin" {{ old('role', $user->role) == 'useradmin' ? 'selected' : '' }}>👨‍💼 Trưởng Nhóm</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Lưu Thay Đổi
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
