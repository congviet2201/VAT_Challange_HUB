{{-- File purpose: resources/views/useradmin/notifications/show.blade.php --}}

@extends('useradmin.layout.app')
{{-- Trang xem chi tiết thông báo cho UserAdmin --}}

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold">{{ $notification->title }}</h1>
                <small class="text-muted">
                    <i class="bi bi-calendar"></i> {{ $notification->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
            <div>
                <form action="{{ route('useradmin.notifications.destroy', $notification->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger me-2"
                            onclick="return confirm('Xóa thông báo này?')">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
                <a href="{{ route('useradmin.notifications.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-3">
                <h5 class="mb-0">📌 Nội Dung Thông Báo</h5>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap;">{{ $notification->message }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $groupMembers->count() }}</h3>
                <p class="mb-0">Thành Viên Nhận</p>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <h6 class="fw-bold mb-3">📌 Nhóm</h6>
                <p class="mb-0">
                    <strong>{{ $notification->group->name }}</strong><br>
                    <small class="text-muted">{{ $notification->group->description ?? 'Không có mô tả' }}</small>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="bi bi-people"></i> Danh Sách Thành Viên Nhận Thông Báo</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupMembers as $member)
                        <tr>
                            <td class="fw-bold">{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>
                                @if($member->is_active)
                                    <span class="badge bg-success">✓ Hoạt Động</span>
                                @else
                                    <span class="badge bg-danger">✗ Bị Khóa</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
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
