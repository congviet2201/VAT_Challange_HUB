@extends('useradmin.layout.app')
{{-- Trang tạo nhóm mới cho UserAdmin --}}

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Tạo Nhóm Mới</h4>
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

                    <form action="{{ route('useradmin.groups.store') }}" method="POST">
                        @csrf

                        <!-- Tên Nhóm -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">👥 Tên Nhóm</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="Nhập tên nhóm" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô Tả -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">📝 Mô Tả (tuỳ chọn)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="Nhập mô tả về nhóm của bạn">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Tạo Nhóm
                            </button>
                            <a href="{{ route('useradmin.groups.index') }}" class="btn btn-secondary btn-lg">
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
