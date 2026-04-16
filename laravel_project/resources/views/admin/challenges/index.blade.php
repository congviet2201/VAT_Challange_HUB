@extends('admin.layout.app')
{{-- Trang danh sách thử thách trong Admin --}}

@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">🎯 Quản lý Thử Thách</h1>
        <a href="{{ route('admin.challenges.create') }}" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Tạo Thử Thách Mới
        </a>
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
                    <th>Tiêu Đề</th>
                    <th>Danh Mục</th>
                    <th>Độ Khó</th>
                    <th>Thời Gian</th>
                    <th class="text-center">👥 Người Tham Gia</th>
                    <th class="text-center">✅ Hoàn Thành</th>
                    <th class="text-center">⏳ Đang Làm</th>
                    <th class="text-center">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($challenges as $challenge)
                    <tr>
                        <td class="fw-bold">{{ $challenge->title }}</td>
                        <td>
                            <span class="badge bg-info">{{ $challenge->category->name }}</span>
                        </td>
                        <td>
                            @if($challenge->difficulty == 'easy')
                                <span class="badge bg-success">🟢 Dễ</span>
                            @elseif($challenge->difficulty == 'medium')
                                <span class="badge bg-warning text-dark">🟡 Trung bình</span>
                            @else
                                <span class="badge bg-danger">🔴 Khó</span>
                            @endif
                        </td>
                        <td>{{ $challenge->daily_time }} phút</td>
                        <td class="text-center">
                            <span class="badge bg-primary fs-6">{{ $challenge->total_users }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success fs-6">{{ $challenge->completed_users }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark fs-6">{{ $challenge->in_progress_users }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.challenges.show', $challenge->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.challenges.edit', $challenge->id) }}" class="btn btn-sm btn-warning" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.challenges.destroy', $challenge->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> Chưa có thử thách nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
