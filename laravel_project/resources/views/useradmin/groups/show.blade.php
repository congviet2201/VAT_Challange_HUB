@extends('useradmin.layout.app')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold">{{ $group->name }}</h1>
                <p class="text-muted">{{ $group->description ?? 'Không có mô tả' }}</p>
            </div>
            <div>
                <a href="{{ route('useradmin.groups.edit', $group->id) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil"></i> Sửa
                </a>
                <a href="{{ route('useradmin.groups.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $members->count() }}</h3>
                <p class="mb-0">Thành Viên</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $group->challenges()->count() }}</h3>
                <p class="mb-0">Thử Thách</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $group->notifications()->count() }}</h3>
                <p class="mb-0">Thông Báo</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card {{ $group->is_active ? 'bg-success' : 'bg-danger' }} text-white">
            <div class="card-body text-center">
                <h3>{{ $group->is_active ? '✓' : '✗' }}</h3>
                <p class="mb-0">{{ $group->is_active ? 'Hoạt Động' : 'Vô Hiệu' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('useradmin.groups.challenges', $group->id) }}" class="btn btn-warning btn-lg w-100">
            <i class="bi bi-book"></i> Quản Lý Thử Thách ({{ $group->challenges()->count() }})
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('useradmin.groups.add-users', $group->id) }}" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-person-plus"></i> Thêm Thành Viên
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="bi bi-people"></i> Danh Sách Thành Viên</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        <tr>
                            <td class="fw-bold">{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>
                                @if($member->is_active)
                                    <span class="badge bg-success">Hoạt Động</span>
                                @else
                                    <span class="badge bg-danger">Bị Khóa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('useradmin.groups.remove-user', [$group->id, $member->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Xóa {{ $member->name }} khỏi nhóm?')">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Nhóm chưa có thành viên nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
