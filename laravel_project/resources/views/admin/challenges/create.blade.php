@extends('admin.layout.app')
{{-- Trang tạo thử thách mới trong Admin --}}

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Tạo Thử Thách Mới</h4>
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

                    <form action="{{ route('admin.challenges.store') }}" method="POST">
                        @csrf

                        <!-- Danh Mục -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">📚 Danh Mục</label>
                            <select name="category_id" class="form-select form-select-lg @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn Danh Mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tiêu Đề -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">✏️ Tiêu Đề</label>
                            <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   placeholder="Nhập tiêu đề thử thách" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô Tả -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">📝 Mô Tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="Nhập mô tả chi tiết" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Độ Khó -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">🎯 Độ Khó</label>
                                <select name="difficulty" class="form-select form-select-lg @error('difficulty') is-invalid @enderror" required>
                                    <option value="">-- Chọn Độ Khó --</option>
                                    <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>🟢 Dễ</option>
                                    <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>🟡 Trung bình</option>
                                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>🔴 Khó</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thời Gian -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">⏱️ Thời Gian (phút/ngày)</label>
                                <input type="number" name="daily_time" class="form-control form-control-lg @error('daily_time') is-invalid @enderror" 
                                       placeholder="Ví dụ: 30" value="{{ old('daily_time') }}" min="1" required>
                                @error('daily_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Hình Ảnh (tuỳ chọn) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">🖼️ Hình Ảnh (tuỳ chọn)</label>
                            <input type="text" name="image" class="form-control form-control-lg" 
                                   placeholder="Ví dụ: easy.jpg" value="{{ old('image') }}">
                            <small class="text-muted">Nhập tên file trong thư mục public/images/</small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Tạo Thử Thách
                            </button>
                            <a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary btn-lg">
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
