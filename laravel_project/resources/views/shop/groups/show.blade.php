@extends('shop.layout.app')
{{-- Trang chi tiết nhóm thử thách dành cho người dùng --}}

@section('content')
{{-- Bắt đầu nội dung chính của trang --}}

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.groups.index') }}">Nhóm</a></li>
        <li class="breadcrumb-item active">{{ $group->name }}</li>
    </ol>
</nav>

<section class="mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <h1 class="fw-bold mb-2">{{ $group->name }}</h1>
                    <p class="text-muted mb-3">{{ $group->description ?: 'Nhóm này chưa có mô tả.' }}</p>
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <span>Tạo bởi {{ $group->creator->name ?? 'Không rõ' }}</span>
                        <span>{{ $group->users->count() }} thành viên</span>
                        <span>{{ $group->challenges->count() }} thử thách</span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    @if ($isMember)
                        <span class="badge bg-success align-self-start">Bạn đã tham gia nhóm này</span>
                        <form action="{{ route('user.groups.leave', $group->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Rời nhóm</button>
                        </form>
                    @else
                        <span class="badge bg-secondary align-self-start">Bạn chưa tham gia nhóm này</span>
                        <form action="{{ route('user.groups.join', $group->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Tham gia nhóm</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Thử thách trong nhóm</h4>
            </div>
            <div class="card-body">
                @forelse ($group->challenges as $challenge)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h5 class="mb-1">{{ $challenge->title }}</h5>
                                <p class="text-muted small mb-2">
                                    {{ $challenge->category->name ?? 'Không có danh mục' }} • {{ $challenge->daily_time }} phút/ngày
                                </p>
                                <p class="mb-0 text-muted">
                                    {{ \Illuminate\Support\Str::limit($challenge->description, 140) }}
                                </p>
                            </div>
                            <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-sm btn-outline-primary">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nhóm này chưa có thử thách nào.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Thành viên</h4>
            </div>
            <div class="card-body">
                @forelse ($group->users as $member)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <span>{{ $member->name }}</span>
                        @if ($member->id === auth()->id())
                            <span class="badge bg-primary">Bạn</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted mb-0">Nhóm này chưa có thành viên.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
