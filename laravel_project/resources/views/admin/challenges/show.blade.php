@extends('admin.layout.app')
{{-- Trang xem chi tiết thử thách trong Admin --}}

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold">{{ $challenge->title }}</h1>
                <a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>

            <!-- Thông Tin Cơ Bản -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <strong>📋 Thông Tin Cơ Bản</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>📚 Danh Mục:</strong> {{ $challenge->category->name }}</p>
                            <p><strong>📝 Mô Tả:</strong> {{ $challenge->description }}</p>
                            <p>
                                <strong>🎯 Độ Khó:</strong>
                                @if($challenge->difficulty == 'easy')
                                    <span class="badge bg-success">🟢 Dễ</span>
                                @elseif($challenge->difficulty == 'medium')
                                    <span class="badge bg-warning text-dark">🟡 Trung bình</span>
                                @else
                                    <span class="badge bg-danger">🔴 Khó</span>
                                @endif
                            </p>
                            <p><strong>⏱️ Thời Gian:</strong> {{ $challenge->daily_time }}/ngày</p>
                        </div>
                    </div>
                </div>

                <!-- Thống Kê -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <strong>📊 Thống Kê Người Tham Gia</strong>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h3 class="text-primary">{{ $challenge->total_users }}</h3>
                                    <p class="text-muted small">👥 Tổng Tham Gia</p>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-success">{{ $challenge->completed_users }}</h3>
                                    <p class="text-muted small">✅ Hoàn Thành</p>
                                </div>
                                <div class="col-4">
                                    <h3 class="text-warning">{{ $challenge->in_progress_users }}</h3>
                                    <p class="text-muted small">⏳ Đang Làm</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng Người Tham Gia -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong>👥 Danh Sách Người Tham Gia ({{ $progressDetails->count() }})</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Người Dùng</th>
                                <th class="text-center">Tiến Độ</th>
                                <th class="text-center">Ngày Hoàn Thành</th>
                                <th class="text-center">Ngày Bắt Đầu</th>
                                <th class="text-center">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($progressDetails as $progress)
                                <tr>
                                    <td class="fw-bold">{{ $progress->user->name }}</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: {{ $progress->progress ?? 0 }}%;"
                                                 aria-valuenow="{{ $progress->progress ?? 0 }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $progress->progress ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($progress->completed_at)
                                            {{ $progress->completed_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="badge bg-secondary">Chưa hoàn thành</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $progress->started_at ? $progress->started_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        @if($progress->completed_at)
                                            <span class="badge bg-success">✅ Hoàn Thành</span>
                                        @else
                                            <span class="badge bg-warning text-dark">⏳ Đang Làm</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> Chưa có ai tham gia thử thách này
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.challenges.edit', $challenge->id) }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-pencil"></i> Chỉnh Sửa
                </a>
                <form action="{{ route('admin.challenges.destroy', $challenge->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
