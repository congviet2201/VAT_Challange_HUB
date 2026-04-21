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
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0 fw-bold">{{ $challenge->title }}</h3>
            </div>
            <div class="card-body">
                <img
                    src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}"
                    class="img-fluid rounded mb-4"
                    style="height: 250px; object-fit: cover;"
                    onerror="this.src='{{ asset('images/default.jpg') }}'"
                    alt="Thử thách: {{ $challenge->title }}">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Danh mục:</strong></p>
                        <a href="{{ route('category.show', $category->id) }}" class="badge bg-info p-2">
                            {{ $category->name }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Cấp độ:</strong></p>
                        @if($challenge->difficulty == 'easy')
                            <span class="badge bg-success p-2">Dễ</span>
                        @elseif($challenge->difficulty == 'medium')
                            <span class="badge bg-warning p-2">Trung bình</span>
                        @else
                            <span class="badge bg-danger p-2">Khó</span>
                        @endif
                    </div>
                </div>

                <hr>

                <h5 class="fw-bold mb-3">Mô tả thử thách</h5>
                <p class="lh-lg text-muted">{{ $challenge->description }}</p>

                <div class="row text-center mt-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted">Thời gian hằng ngày</h6>
                        <h4 class="fw-bold">{{ $challenge->daily_time }} phút/ngày</h4>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted">Bắt đầu từ</h6>
                        <h4 class="fw-bold">{{ optional($progress->started_at)->format('d/m/Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($allTasks) && $allTasks->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Các bước hoàn thành thử thách</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($allTasks as $task)
                            <div class="col-md-6">
                                <div class="card h-100 border {{ in_array($task->id, $completedTaskIds) ? 'border-success bg-light-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">
                                            @if(in_array($task->id, $completedTaskIds))
                                                <span class="badge bg-success me-2">✓</span>
                                            @else
                                                <span class="badge bg-secondary me-2">{{ $task->order }}</span>
                                            @endif
                                            {{ $task->title }}
                                        </h6>

                                        <p class="small text-muted mb-3">{{ $task->description }}</p>

                                        @if(!in_array($task->id, $completedTaskIds))
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary w-100 complete-task-btn"
                                                data-challenge-id="{{ $challenge->id }}"
                                                data-task-id="{{ $task->id }}">
                                                Đánh dấu hoàn thành
                                            </button>
                                        @else
                                            <span class="badge bg-success w-100 py-2">Đã hoàn thành</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="d-grid gap-2 d-md-flex mb-4">
            <a href="{{ route('category.show', $category->id) }}" class="btn btn-secondary btn-lg">Quay lại</a>
            <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary btn-lg">Chi tiết</a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Tiến độ của bạn</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                        <svg style="width: 150px; height: 150px; transform: rotate(-90deg); margin-left: 0;">
                            <circle cx="75" cy="75" r="65" fill="none" stroke="#e0e0e0" stroke-width="8"></circle>
                            <circle
                                cx="75"
                                cy="75"
                                r="65"
                                fill="none"
                                stroke="{{ $progress->progress < 50 ? '#ffc107' : ($progress->progress < 100 ? '#17a2b8' : '#28a745') }}"
                                stroke-width="8"
                                stroke-dasharray="{{ (2 * 3.14159 * 65 * $progress->progress) / 100 }}, {{ 2 * 3.14159 * 65 }}"
                                stroke-linecap="round"></circle>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <h2 class="fw-bold mb-0" id="progressText">{{ $progress->progress }}%</h2>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                </div>

                <p class="mb-3">
                    <span
                        id="statusBadge"
                        class="badge p-3 fs-6 @if($progress->progress == 0) bg-secondary @elseif($progress->progress < 100) bg-info @else bg-success @endif">
                        @if($progress->progress == 0)
                            Chưa bắt đầu
                        @elseif($progress->progress < 100)
                            Đang tiến hành
                        @else
                            Đã hoàn thành
                        @endif
                    </span>
                </p>

                @if($progress->progress >= 100 && $progress->completed_at)
                    <p class="text-muted small mb-3">
                        Hoàn thành ngày: <strong>{{ $progress->completed_at->format('d/m/Y lúc H:i') }}</strong>
                    </p>
                @endif

                <div class="progress mb-3" style="height: 25px;">
                    <div
                        class="progress-bar @if($progress->progress < 50) bg-warning @elseif($progress->progress < 100) bg-info @else bg-success @endif"
                        role="progressbar"
                        style="width: {{ $progress->progress }}%;"
                        aria-valuenow="{{ $progress->progress }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                        {{ $progress->progress }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">AI Coach - Đánh giá và gợi ý</h5>
    </div>
    <div class="card-body">
        <p class="fs-5 fw-semibold">{{ $feedback['evaluation'] }}</p>
        <hr>
        <ul class="list-group list-group-flush">
            @foreach($feedback['suggestions'] as $suggestion)
                <li class="list-group-item">{{ $suggestion }}</li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => successToast.remove(), 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.complete-task-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const challengeId = this.getAttribute('data-challenge-id');
                const taskId = this.getAttribute('data-task-id');
                const currentButton = this;

                try {
                    const response = await fetch(`/challenge/${challengeId}/task/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        alert(data.error || 'Có lỗi xảy ra');
                        return;
                    }

                    const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Thành công!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    const breadcrumb = document.querySelector('nav');
                    if (breadcrumb) {
                        breadcrumb.insertAdjacentHTML('afterend', alertHtml);
                    }

                    setTimeout(() => {
                        document.querySelectorAll('.alert-success').forEach(alert => {
                            if (alert.id !== 'successToast') {
                                alert.remove();
                            }
                        });
                    }, 3000);

                    const taskCard = currentButton.closest('.card');
                    if (taskCard) {
                        taskCard.classList.remove('border-secondary');
                        taskCard.classList.add('border-success', 'bg-light-success');
                        const badge = taskCard.querySelector('.card-title .badge');
                        if (badge) {
                            badge.className = 'badge bg-success me-2';
                            badge.textContent = '✓';
                        }
                    }

                    currentButton.outerHTML = '<span class="badge bg-success w-100 py-2">Đã hoàn thành</span>';
                    updateProgressWithAnimation(data.progress);
                } catch (error) {
                    console.error(error);
                    alert('Có lỗi xảy ra, vui lòng thử lại');
                }
            });
        });
    });

    function updateProgressWithAnimation(targetPercentage) {
        const circle = document.querySelector('circle[stroke-dasharray]');
        const progressText = document.getElementById('progressText');
        const progressBar = document.querySelector('.progress-bar');
        const statusBadge = document.getElementById('statusBadge');

        if (!circle || !progressText || !progressBar) {
            return;
        }

        const radius = 65;
        const circumference = 2 * Math.PI * radius;
        const currentPercentage = parseInt(progressText.textContent, 10) || 0;
        const steps = Math.max(1, Math.abs(targetPercentage - currentPercentage));
        let currentStep = 0;

        const animationInterval = setInterval(() => {
            currentStep++;
            const progress = currentPercentage + (targetPercentage - currentPercentage) * (currentStep / steps);
            const rounded = Math.round(progress);

            circle.setAttribute('stroke-dasharray', `${(circumference * progress) / 100}, ${circumference}`);

            if (progress < 50) {
                circle.setAttribute('stroke', '#ffc107');
                progressBar.className = 'progress-bar bg-warning';
            } else if (progress < 100) {
                circle.setAttribute('stroke', '#17a2b8');
                progressBar.className = 'progress-bar bg-info';
            } else {
                circle.setAttribute('stroke', '#28a745');
                progressBar.className = 'progress-bar bg-success';
            }

            progressText.textContent = `${rounded}%`;
            progressBar.style.width = `${progress}%`;
            progressBar.textContent = `${rounded}%`;

            if (statusBadge) {
                if (rounded === 0) {
                    statusBadge.className = 'badge p-3 fs-6 bg-secondary';
                    statusBadge.textContent = 'Chưa bắt đầu';
                } else if (rounded < 100) {
                    statusBadge.className = 'badge p-3 fs-6 bg-info';
                    statusBadge.textContent = 'Đang tiến hành';
                } else {
                    statusBadge.className = 'badge p-3 fs-6 bg-success';
                    statusBadge.textContent = 'Đã hoàn thành';
                }
            }

            if (currentStep >= steps) {
                clearInterval(animationInterval);
            }
        }, 100);
    }
</script>
@endsection
