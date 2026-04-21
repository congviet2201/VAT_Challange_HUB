@extends('shop.layout.app')
{{-- Trang dashboard hiển thị tiến độ challenge của người dùng --}}

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">📊 Dashboard</h1>
            <p class="text-muted">Theo dõi tiến độ thử thách đã tham gia và check-in hàng ngày.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($userChallenges as $uc)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Challenge ID: {{ $uc->challenge_id }}</h5>
                        <small class="text-muted">Theo dõi tiến độ và check-in mỗi ngày</small>
                    </div>
                    <span class="badge bg-primary">{{ round($uc->progress) }}%</span>
                </div>

                <div class="progress mb-3" style="height: 22px;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $uc->progress }}%;"
                         aria-valuenow="{{ $uc->progress }}" aria-valuemin="0" aria-valuemax="100">
                        {{ round($uc->progress) }}%
                    </div>
                </div>

                <p class="mb-1"><strong>Streak:</strong> {{ $uc->streak }} ngày</p>
                <p class="mb-3"><strong>Hoàn thành:</strong> {{ $uc->completed_days }} ngày</p>

                <form method="POST" action="/checkin">
                    @csrf
                    <input type="hidden" name="challenge_id" value="{{ $uc->challenge_id }}">
                    <button type="submit" class="btn btn-success">Check-in hôm nay</button>
                </form>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            <i class="bi bi-inbox"></i> Bạn chưa bắt đầu thử thách nào. Hãy chọn thử thách và bắt đầu ngay!
        </div>
    @endforelse
</div>
@endsection
