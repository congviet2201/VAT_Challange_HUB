{{-- File purpose: resources/views/useradmin/groups/index.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

@extends('useradmin.layout.app')
{{-- Trang danh sách nhóm cho UserAdmin --}}

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">👥 Quản Lý Nhóm</h1>
    <a href="{{ route('useradmin.groups.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Tạo Nhóm Mới
    </a>
</div>

<div class="row">
    @forelse($groups as $group)
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">{{ $group->name }}</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">{{ $group->description ?? 'Không có mô tả' }}</p>
                <div class="mb-3">
                    <small class="text-secondary">
                        <i class="bi bi-people"></i> {{ $group->users()->count() }} thành viên
                    </small>
                </div>
                <div class="mb-3">
                    <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $group->is_active ? '✓ Hoạt động' : '✗ Vô hiệu' }}
                    </span>
                </div>
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('useradmin.groups.show', $group->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye"></i> Xem
                    </a>
                    <a href="{{ route('useradmin.groups.edit', $group->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="{{ route('useradmin.groups.add-users', $group->id) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-person-plus"></i> Thêm User
                    </a>
                    <form action="{{ route('useradmin.groups.toggle', $group->id) }}" method="POST" style="display: inline; flex: 1;">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100"
                                onclick="return confirm('Xác nhận thay đổi trạng thái?')">
                            <i class="bi bi-toggle-off"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox fs-3"></i>
            <p class="mt-2">Bạn chưa tạo nhóm nào. Hãy tạo nhóm đầu tiên của bạn!</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
