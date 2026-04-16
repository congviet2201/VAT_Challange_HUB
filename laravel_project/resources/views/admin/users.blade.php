@extends('admin.layout.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">👥 Quản lý Người Dùng</h1>
    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success me-2">
            <i class="bi bi-plus-circle"></i> Thêm Người Dùng
        </a>
        <a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-hover table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai Trò</th>
                <th>Trạng Thái</th>
                <th class="text-center">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="fw-bold">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 'useradmin')
                        <span class="badge bg-info text-dark">Trưởng Nhóm</span>
                    @else
                        <span class="badge bg-secondary">Thành Viên</span>
                    @endif
                </td>
                <td>
                    @if($user->is_active)
                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Hoạt Động</span>
                    @else
                        <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Đang Khóa</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                onclick="return confirm('Xác nhận thay đổi trạng thái?')">
                            @if($user->is_active)
                                <i class="bi bi-lock"></i> Khóa
                            @else
                                <i class="bi bi-unlock"></i> Mở Khóa
                            @endif
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="bi bi-inbox"></i> Chưa có người dùng nào
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
