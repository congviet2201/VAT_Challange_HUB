@extends('shop.layout.app')

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Dashboard - Thử thách của bạn</h1>

            <!-- Toast Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Thành công!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Lỗi!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($userChallenges->count() > 0)
                <div class="row g-4">
                    @foreach($userChallenges as $progress)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <!-- Hình ảnh challenge -->
                                <img src="{{ asset('images/' . $progress->challenge->difficulty . '.jpg') }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;"
                                     alt="{{ $progress->challenge->title }}"
                                     onerror="this.src='{{ asset('images/default.jpg') }}'">

                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $progress->challenge->title }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($progress->challenge->description, 80) }}</p>

                                    <!-- Progress -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Tiến độ</small>
                                            <small class="fw-bold">{{ round($progress->progress) }}%</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar
                                                @if($progress->progress < 50)
                                                    bg-warning
                                                @elseif($progress->progress < 100)
                                                    bg-info
                                                @else
                                                    bg-success
                                                @endif
                                            " style="width: {{ $progress->progress }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Stats -->
                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded">
                                                <h6 class="mb-0 fw-bold text-danger">{{ $progress->streak }}</h6>
                                                <small class="text-muted">🔥 Streak</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded">
                                                <h6 class="mb-0 fw-bold text-primary">{{ $progress->completed_days }}</h6>
                                                <small class="text-muted">📅 Ngày hoàn thành</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3 text-center">
                                        @if($progress->progress == 0)
                                            <span class="badge bg-secondary">Chưa bắt đầu</span>
                                        @elseif($progress->progress < 100)
                                            <span class="badge bg-info">Đang tiến hành</span>
                                        @else
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer bg-white">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('challenge.progress', $progress->challenge) }}"
                                           class="btn btn-outline-primary btn-sm flex-grow-1">
                                            📊 Xem tiến độ
                                        </a>

                                        @if($progress->progress < 100)
                                            <form action="{{ route('checkin') }}" method="POST" class="flex-grow-1">
                                                @csrf
                                                <input type="hidden" name="challenge_id" value="{{ $progress->challenge_id }}">
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    ✅ Check-in
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <h4 class="text-muted mb-3">Bạn chưa tham gia thử thách nào</h4>
                    <a href="{{ route('challenges') }}" class="btn btn-primary btn-lg">
                        🌟 Khám phá thử thách
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
