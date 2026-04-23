{{-- File purpose: resources/views/useradmin/groups/challenges.blade.php --}}

@extends('useradmin.layout.app')
{{-- Trang danh sách thử thách của nhóm cho UserAdmin --}}

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold">📚 Thử Thách Của Nhóm: {{ $group->name }}</h1>
            </div>
            <div>
                <a href="{{ route('useradmin.groups.add-challenges', $group->id) }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> Thêm Thử Thách
                </a>
                <a href="{{ route('useradmin.groups.show', $group->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>
</div>

@if($challenges->isEmpty())
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-inbox fs-3"></i>
        <p class="mt-2">Nhóm chưa có thử thách nào. Hãy thêm thử thách cho nhóm!</p>
        <a href="{{ route('useradmin.groups.add-challenges', $group->id) }}" class="btn btn-success mt-2">
            <i class="bi bi-plus-circle"></i> Thêm Thử Thách Ngay
        </a>
    </div>
@else
    <div class="row">
        @foreach($challenges as $challenge)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0">
                            <span class="badge bg-light text-dark">{{ $challenge->category->name }}</span>
                            @if($challenge->difficulty === 'easy')
                                <span class="badge bg-success">🟢 Dễ</span>
                            @elseif($challenge->difficulty === 'medium')
                                <span class="badge bg-warning text-dark">🟡 Trung Bình</span>
                            @else
                                <span class="badge bg-danger">🔴 Khó</span>
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $challenge->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($challenge->description, 100) }}</p>
                        <div class="mb-3">
                            <small class="text-secondary">
                                <i class="bi bi-clock"></i> {{ $challenge->daily_time }}/ngày
                            </small>
                        </div>
                        <div>
                            <form action="{{ route('useradmin.groups.remove-challenge', [$group->id, $challenge->id]) }}"
                                  method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Xóa thử thách này khỏi nhóm?')">
                                    <i class="bi bi-trash"></i> Xóa Khỏi Nhóm
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
