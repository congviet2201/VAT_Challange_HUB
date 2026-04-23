{{-- File purpose: resources/views/shop/profile.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

@extends('shop.layout.app')

@section('content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active">Thông tin cá nhân</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Trang cá nhân</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; font-size: 2rem; color: white;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>

                <h4 class="fw-bold text-center mb-2">{{ $user->name }}</h4>
                <p class="text-center text-muted mb-4">{{ ucfirst($user->role) }}</p>

                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2">Email</h6>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2">Trạng thái tài khoản</h6>
                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }} p-2">
                        {{ $user->is_active ? 'Đang hoạt động' : 'Tạm khóa' }}
                    </span>
                </div>

                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2">Tham gia từ</h6>
                    <p class="mb-0">{{ optional($user->created_at)->format('d/m/Y') }}</p>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Tổng thử thách</span>
                        <strong>{{ $totalCount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Đang thực hiện</span>
                        <strong>{{ $activeCount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Hoàn thành</span>
                        <strong>{{ $completedCount }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Mục tiêu của bạn</h5>
            </div>
            <div class="card-body">
                @php
                    $userGoals = \App\Models\Goal::where('user_id', $user->id)->with('category')->latest()->take(3)->get();
                @endphp
                @if($userGoals->isEmpty())
                    <p class="mb-0">Bạn chưa đặt mục tiêu nào. <a href="{{ route('goals.create') }}">Đặt mục tiêu ngay</a></p>
                @else
                    <div class="list-group">
                        @foreach($userGoals as $goal)
                            <a href="{{ route('goals.show', $goal) }}" class="list-group-item list-group-item-action">
                                <strong>{{ $goal->title }}</strong>
                                <br><small class="text-muted">{{ $goal->category->name }}</small>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('goals.index') }}" class="btn btn-outline-warning mt-2">Xem tất cả mục tiêu</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Tiến độ thử thách</h5>
            </div>
            <div class="card-body">
                @if($progressItems->isEmpty())
                    <p class="mb-0">Bạn hiện chưa tham gia thử thách nào. Hãy chọn thử thách và bắt đầu ngay!</p>
                @else
                    <div class="row g-3">
                        @foreach($progressItems as $progress)
                            @php
                                $challenge = $progress->challenge;
                                $category = optional($challenge)->category;
                            @endphp

                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="mb-1">{{ $challenge->title ?? 'Thử thách đã xóa' }}</h5>
                                                @if($category)
                                                    <span class="badge bg-info">{{ $category->name }}</span>
                                                @endif
                                            </div>
                                            <span class="badge {{ $progress->progress >= 100 ? 'bg-success' : 'bg-warning' }}">
                                                {{ $progress->progress }}%
                                            </span>
                                        </div>

                                        @if($challenge)
                                            <p class="mb-3 text-muted">Cấp độ:
                                                @if($challenge->difficulty === 'easy')
                                                    Dễ
                                                @elseif($challenge->difficulty === 'medium')
                                                    Trung bình
                                                @else
                                                    Khó
                                                @endif
                                            </p>
                                        @endif

                                        <div class="progress mb-3" style="height: 18px;">
                                            <div class="progress-bar {{ $progress->progress >= 100 ? 'bg-success' : 'bg-info' }}" role="progressbar"
                                                 style="width: {{ $progress->progress }}%;"
                                                 aria-valuenow="{{ $progress->progress }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ $progress->progress }}%
                                            </div>
                                        </div>

                                        <div class="row text-muted small">
                                            <div class="col-sm-4 mb-2">
                                                <strong>{{ $progress->completed_days }}</strong>
                                                <div>Ngày đã hoàn thành</div>
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <strong>{{ $progress->streak }}</strong>
                                                <div>Chuỗi liên tiếp</div>
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <strong>{{ optional($progress->started_at)->format('d/m/Y') }}</strong>
                                                <div>Bắt đầu từ</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 d-flex gap-2 flex-wrap">
                                            @if($challenge)
                                                <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary btn-sm">Chi tiết thử thách</a>
                                                <a href="{{ route('challenge.progress', $challenge->id) }}" class="btn btn-primary btn-sm">Xem tiến độ</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
