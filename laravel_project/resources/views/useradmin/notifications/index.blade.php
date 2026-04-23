{{-- File purpose: resources/views/useradmin/notifications/index.blade.php --}}

@extends('useradmin.layout.app')
{{-- Trang danh sách thông báo cho UserAdmin --}}

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">🔔 Quản Lý Thông Báo</h1>
    <a href="{{ route('useradmin.notifications.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Gửi Thông Báo
    </a>
</div>

<div class="row">
    @forelse($notifications as $notification)
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-3">
                <h5 class="mb-0">{{ $notification->title }}</h5>
            </div>
            <div class="card-body">
                <p class="card-text text-muted mb-2">{{ Str::limit($notification->message, 100) }}</p>
                <div class="mb-3">
                    <small class="text-secondary">
                        <i class="bi bi-people"></i> Nhóm: <strong>{{ $notification->group->name }}</strong>
                    </small>
                </div>
                <div class="mb-3">
                    <small class="text-secondary">
                        <i class="bi bi-calendar"></i> {{ $notification->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('useradmin.notifications.show', $notification->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye"></i> Xem Chi Tiết
                    </a>
                    <form action="{{ route('useradmin.notifications.destroy', $notification->id) }}" method="POST" style="display: inline; flex: 1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                onclick="return confirm('Xóa thông báo này?')">
                            <i class="bi bi-trash"></i> Xóa
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
            <p class="mt-2">Bạn chưa gửi thông báo nào. Hãy gửi thông báo đầu tiên!</p>
        </div>
    </div>
    @endforelse
</div>

@endsection
