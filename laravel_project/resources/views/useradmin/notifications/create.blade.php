@extends('useradmin.layout.app')
{{-- Trang tạo thông báo mới cho UserAdmin --}}

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-send"></i> Gửi Thông Báo</h4>
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

                    <form action="{{ route('useradmin.notifications.store') }}" method="POST">
                        @csrf

                        <!-- Chọn Nhóm -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">👥 Chọn Nhóm</label>
                            <select name="group_id" class="form-select form-select-lg @error('group_id') is-invalid @enderror" required>
                                <option value="">-- Chọn Nhóm --</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} ({{ $group->users()->count() }} thành viên)
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tiêu Đề -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">📌 Tiêu Đề</label>
                            <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   placeholder="Nhập tiêu đề thông báo" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nội Dung -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">📝 Nội Dung</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                      rows="6" placeholder="Nhập nội dung thông báo" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                <i class="bi bi-send"></i> Gửi Thông Báo
                            </button>
                            <a href="{{ route('useradmin.notifications.index') }}" class="btn btn-secondary btn-lg">
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
