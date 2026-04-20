@extends('shop.layout.app')

@section('content')

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $challenge->title }} - Tiến độ</li>
    </ol>
</nav>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successToast">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h3 class="mb-0 fw-bold">{{ $challenge->title }}</h3>
            </div>
            <div class="card-body">
                <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}" class="img-fluid rounded mb-4" style="height: 250px; width: 100%; object-fit: cover;" onerror="this.src='{{ asset('images/default.jpg') }}'">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2 text-muted"><strong>Danh mục:</strong></p>
                        <a href="{{ route('category.show', $category->id) }}" class="badge bg-info p-2 text-decoration-none">
                            {{ $category->name }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2 text-muted"><strong>Cấp độ:</strong></p>
                        @if($challenge->difficulty == 'easy')
                            <span class="badge bg-success p-2">Dễ</span>
                        @elseif($challenge->difficulty == 'medium')
                            <span class="badge bg-warning p-2 text-dark">Trung bình</span>
                        @else
                            <span class="badge bg-danger p-2">Khó</span>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3">Mô tả thử thách</h5>
                <p class="lh-lg text-muted">{{ $challenge->description }}</p>

                <div class="row text-center mt-4 bg-light p-3 rounded">
                    <div class="col-sm-6 border-end">
                        <h6 class="text-muted mb-1 small">Thời gian hằng ngày</h6>
                        <h4 class="fw-bold mb-0">{{ $challenge->daily_time }} phút</h4>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted mb-1 small">Ngày bắt đầu</h6>
                        <h4 class="fw-bold mb-0">{{ $progress->started_at->format('d/m/Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex mb-4">
            <a href="{{ route('category.show', $category->id) }}" class="btn btn-outline-secondary px-4">← Quay lại</a>
            <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary px-4">Xem chi tiết</a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold text-center">Tiến độ của bạn</h5>
            </div>
            <div class="card-body text-center py-4">
                <div class="mb-4">
                    <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                        <svg style="width: 150px; height: 150px; transform: rotate(-90deg);">
                            <circle cx="75" cy="75" r="65" fill="none" stroke="#e9ecef" stroke-width="10"></circle>
                            <circle
                                id="progressCircle"
                                cx="75" cy="75" r="65"
                                fill="none"
                                @if($progress->progress < 50)
                                    stroke="#ffc107"
                                @elseif($progress->progress < 100)
                                    stroke="#0dcaf0"
                                @else
                                    stroke="#198754"
                                @endif
                                stroke-width="10"
                                stroke-dasharray="{{ (2 * 3.14159 * 65 * $progress->progress) / 100 }}, {{ 2 * 3.14159 * 65 }}"
                                stroke-linecap="round"
                                style="transition: stroke-dasharray 0.5s ease;"
                            ></circle>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <h2 class="fw-bold mb-0 text-dark" id="progressText">{{ $progress->progress }}%</h2>
                        </div>
                    </div>
                </div>

                <div id="statusContainer" class="mb-3">
                    @if($progress->progress == 0)
                        <span id="statusBadge" class="badge bg-secondary p-3 fs-6 w-100 mb-3">🚀 Chưa bắt đầu</span>
                        <button type="button" id="startChallengeBtn" class="btn btn-success btn-lg w-100 shadow-sm">
                            <i class="bi bi-play-fill"></i> Bắt đầu ngay
                        </button>
                    @elseif($progress->progress < 100)
                        <span id="statusBadge" class="badge bg-info text-dark p-3 fs-6 w-100 mb-3">🔄 Đang tiến hành</span>
                    @else
                        <span id="statusBadge" class="badge bg-success p-3 fs-6 w-100 mb-2">✅ Đã hoàn thành</span>
                        @if($progress->completed_at)
                            <p class="text-muted small">
                                Hoàn thành ngày: <strong>{{ \Carbon\Carbon::parse($progress->completed_at)->format('d/m/Y lúc H:i') }}</strong>
                            </p>
                        @endif
                    @endif
                </div>

                <div class="progress mt-3" style="height: 10px; border-radius: 5px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated 
                        @if($progress->progress < 50) bg-warning @elseif($progress->progress < 100) bg-info @else bg-success @endif" 
                        role="progressbar" style="width: {{ $progress->progress }}%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successToast = document.getElementById('successToast');
        if (successToast) {
            setTimeout(() => { successToast.classList.add('fade'); successToast.remove(); }, 4000);
        }

        const startBtn = document.getElementById('startChallengeBtn');
        if (startBtn) {
            startBtn.addEventListener('click', function() {
                this.style.display = 'none';
                const badge = document.getElementById('statusBadge');
                badge.className = 'badge bg-info text-dark p-3 fs-6 w-100 mb-3';
                badge.textContent = '🔄 Đang tiến hành';
                
                // Hiển thị thông báo nhanh
                alert('Chúc mừng! Bạn đã bắt đầu thử thách. Hãy hoàn thành các task bên dưới.');
            });
        }
    });

    // Hàm cập nhật progress động (Dùng khi hoàn thành task bằng AJAX)
    function updateProgress(target) {
        const circle = document.getElementById('progressCircle');
        const text = document.getElementById('progressText');
        const bar = document.getElementById('progressBar');
        const badge = document.getElementById('statusBadge');
        
        const circumference = 2 * Math.PI * 65;
        circle.setAttribute('stroke-dasharray', `${(circumference * target) / 100}, ${circumference}`);
        text.textContent = target + '%';
        bar.style.width = target + '%';

        if (target >= 100) {
            circle.setAttribute('stroke', '#198754');
            bar.className = 'progress-bar bg-success';
            badge.className = 'badge bg-success p-3 fs-6 w-100 mb-2';
            badge.textContent = '✅ Đã hoàn thành';
        } else if (target >= 50) {
            circle.setAttribute('stroke', '#0dcaf0');
            bar.className = 'progress-bar bg-info';
        }
    }
</script>

@endsection