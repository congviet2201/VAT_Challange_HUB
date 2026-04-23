{{-- File purpose: resources/views/useradmin/groups/edit.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

@extends('useradmin.layout.app')
{{-- Trang chỉnh sửa nhóm cho UserAdmin --}}

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Chỉnh Sửa Nhóm</h4>
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

                    <form action="{{ route('useradmin.groups.update', $group->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Tên Nhóm -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">👥 Tên Nhóm</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="Nhập tên nhóm" value="{{ old('name', $group->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô Tả -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">📝 Mô Tả (tuỳ chọn)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="Nhập mô tả về nhóm của bạn">{{ old('description', $group->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info mb-4">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                Nhóm có <strong>{{ $group->users()->count() }}</strong> thành viên
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Lưu Thay Đổi
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
