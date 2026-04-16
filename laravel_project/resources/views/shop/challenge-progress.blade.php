@extends('shop.layout.app')

@section('content')

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $challenge->title }} - Tiến độ</li>
    </ol>
</nav>

<!-- Toast Notification -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successToast">
        <strong>✅ Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    <!-- PHẦN NỘI DUNG CHÍNH -->
    <div class="col-lg-8">
        <!-- Thông tin thử thách -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0 fw-bold">{{ $challenge->title }}</h3>
            </div>
            <div class="card-body">
                <!-- Hình ảnh -->
                <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}" class="img-fluid rounded mb-4" style="height: 250px; object-fit: cover;" onerror="this.src='{{ asset('images/default.jpg') }}'">

                <!-- Danh mục & Cấp độ -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>📚 Danh mục:</strong></p>
                        <a href="{{ route('category.show', $category->id) }}" class="badge bg-info p-2">
                            {{ $category->name }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>🎯 Cấp độ:</strong></p>
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

                <!-- Mô tả -->
                <h5 class="fw-bold mb-3">📝 Mô tả thử thách</h5>
                <p class="lh-lg text-muted">{{ $challenge->description }}</p>

                <div class="row text-center mt-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted">⏱ Thời gian hàng ngày</h6>
                        <h4 class="fw-bold">{{ $challenge->daily_time }}/ngày</h4>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted">📅 Bắt đầu từ</h6>
                        <h4 class="fw-bold">{{ $progress->started_at->format('d/m/Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách Tasks -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📋 Các bước hoàn thành thử thách</h5>
            </div>
            <div class="card-body">
                @if($allTasks->count() > 0)
                    <div class="row g-3">
                        @foreach($allTasks as $task)
                            <div class="col-md-6">
                                <div class="card h-100 border {{ in_array($task->id, $completedTaskIds) ? 'border-success bg-light-success' : 'border-secondary' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    @if(in_array($task->id, $completedTaskIds))
                                                        <span class="badge bg-success me-2">✅</span>
                                                    @else
                                                        <span class="badge bg-secondary me-2">{{ $task->order }}</span>
                                                    @endif
                                                    {{ $task->title }}
                                                </h6>
                                                <p class="small text-muted mb-2">{{ $task->description }}</p>
                                            </div>
                                        </div>

                                        @if(!in_array($task->id, $completedTaskIds))
                                            <button type="button" class="btn btn-sm btn-primary w-100 complete-task-btn"
                                                    data-challenge-id="{{ $challenge->id }}"
                                                    data-task-id="{{ $task->id }}">
                                                <i class="bi bi-check-circle"></i> Đánh dấu hoàn thành
                                            </button>
                                        @else
                                            <span class="badge bg-success w-100 py-2">Đã hoàn thành</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Chưa có tasks cho thử thách này
                    </div>
                @endif
            </div>
        </div>

        <!-- Đánh giá & Điều hướng -->
        <div class="d-grid gap-2 d-md-flex mb-4">
            <a href="{{ route('category.show', $category->id) }}" class="btn btn-secondary btn-lg">← Quay lại</a>
            <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary btn-lg">ℹ️ Chi tiết</a>
        </div>
    </div>

    <!-- PHẦN SIDEBAR - TIẾN ĐỘ -->
    <div class="col-lg-4">
        <!-- Tiến độ chính -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">📊 Tiến độ của bạn</h5>
            </div>
            <div class="card-body text-center">
                <!-- Progress Circle -->
                <div class="mb-4">
                    <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                        <svg style="width: 150px; height: 150px; transform: rotate(-90deg); margin-left: 0;">
                            <!-- Background circle -->
                            <circle cx="75" cy="75" r="65" fill="none" stroke="#e0e0e0" stroke-width="8"></circle>
                            <!-- Progress circle -->
                            <circle
                                cx="75" cy="75" r="65"
                                fill="none"
                                @if($progress->progress < 50)
                                    stroke="#ffc107"
                                @elseif($progress->progress < 100)
                                    stroke="#17a2b8"
                                @else
                                    stroke="#28a745"
                                @endif
                                stroke-width="8"
                                stroke-dasharray="{{ (2 * 3.14159 * 65 * $progress->progress) / 100 }}, {{ 2 * 3.14159 * 65 }}"
                                stroke-linecap="round"
                            ></circle>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <h2 class="fw-bold mb-0">{{ $progress->progress }}%</h2>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <p class="mb-3">
                    <span id="statusBadge" class="badge p-3 fs-6
                        @if($progress->progress == 0)
                            bg-secondary
                        @elseif($progress->progress < 100)
                            bg-info
                        @else
                            bg-success
                        @endif
                    ">
                        @if($progress->progress == 0)
                            🚀 Chưa bắt đầu
                        @elseif($progress->progress < 100)
                            🔄 Đang tiến hành
                        @else
                            ✅ Đã hoàn thành
                        @endif
                    </span>
                </p>

                @if($progress->progress == 0)
                    <button type="button" id="startChallengeBtn" class="btn btn-lg btn-success w-100 mb-3">
                        <i class="bi bi-play-circle"></i> Bắt đầu thử thách
                    </button>
                @endif

                @if($progress->progress == 100 && $progress->completed_at)
                    <p class="text-muted small mb-3">
                        Hoàn thành ngày: <strong>{{ $progress->completed_at->format('d/m/Y lúc H:i') }}</strong>
                    </p>
                @endif

                <!-- Progress Bar -->
                <div class="progress mb-3" style="height: 25px;">
                    <div class="progress-bar
                        @if($progress->progress < 50)
                            bg-warning
                        @elseif($progress->progress < 100)
                            bg-info
                        @else
                            bg-success
                        @endif
                    " role="progressbar" style="width: {{ $progress->progress }}%;" aria-valuenow="{{ $progress->progress }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $progress->progress }}%
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

<script>
    // Ẩn toast thành công sau 5 giây
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => {
            successToast.remove();
        }, 5000);
    }

    // Handle start challenge button
    document.addEventListener('DOMContentLoaded', function() {
        const startBtn = document.getElementById('startChallengeBtn');
        console.log('Start button:', startBtn); // Debug

        if (startBtn) {
            startBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Start button clicked'); // Debug

                // Ẩn nút và update status
                this.style.display = 'none';

                // Cập nhật status badge
                const statusBadge = document.getElementById('statusBadge');
                console.log('Status badge:', statusBadge); // Debug

                if (statusBadge) {
                    statusBadge.className = 'badge bg-info p-3 fs-6';
                    statusBadge.textContent = '🔄 Đang tiến hành';
                }

                // Hiển thị thông báo
                const alertHtml = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>✅ Bắt đầu thử thách!</strong> Hãy click vào từng bước để hoàn thành.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                const nav = document.querySelector('nav');
                if (nav) {
                    nav.insertAdjacentHTML('afterend', alertHtml);
                }

                setTimeout(() => {
                    const alert = document.querySelector('.alert-success');
                    if (alert) alert.remove();
                }, 3000);
            });
        }
    });

    // Handle complete task
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.complete-task-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const challengeId = this.getAttribute('data-challenge-id');
                const taskId = this.getAttribute('data-task-id');
                const btn = this;

                try {
                    const response = await fetch(`/challenge/${challengeId}/task/${taskId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Hiển thị thông báo
                        const alertHtml = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>✅ ${data.message}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        const nav = document.querySelector('nav');
                        if (nav) {
                            nav.insertAdjacentHTML('afterend', alertHtml);
                        }

                        // Tìm alert và ẩn sau 3 giây
                        setTimeout(() => {
                            const alert = document.querySelector('.alert-success');
                            if (alert) alert.remove();
                        }, 3000);

                        // Cập nhật UI task card
                        const taskCard = btn.closest('.card');
                        if (taskCard) {
                            taskCard.classList.add('border-success', 'bg-light-success');
                            const badge = taskCard.querySelector('.card-title .badge');
                            if (badge) {
                                badge.className = 'badge bg-success me-2';
                                badge.textContent = '✅';
                            }
                        }
                        btn.outerHTML = '<span class="badge bg-success w-100 py-2">Đã hoàn thành</span>';

                        // Cập nhật progress với animation
                        updateProgressWithAnimation(data.progress);

                        // Cập nhật status badge nếu đã hoàn thành
                        if (data.progress === 100) {
                            const statusBadge = document.getElementById('statusBadge');
                            if (statusBadge) {
                                statusBadge.className = 'badge bg-success p-3 fs-6';
                                statusBadge.textContent = '✅ Đã hoàn thành';
                            }
                        }
                    } else {
                        alert('❌ ' + (data.error || 'Có lỗi xảy ra'));
                    }
                } catch (error) {
                    console.error('Lỗi:', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại');
                }
            });
        });
    });

    // Cập nhật progress circle với animation
    function updateProgressWithAnimation(targetPercentage) {
        const circle = document.querySelector('circle[stroke-dasharray]');
        const progressText = document.querySelector('h2.fw-bold');
        const progressBar = document.querySelector('.progress-bar');

        if (!circle || !progressText || !progressBar) return;

        const radius = 65;
        const circumference = 2 * Math.PI * radius;

        // Lấy percentage hiện tại từ text
        const currentPercentage = parseInt(progressText.textContent);

        // Animate từ từ
        const steps = Math.abs(targetPercentage - currentPercentage);
        let currentStep = 0;

        const animationInterval = setInterval(() => {
            currentStep++;
            const progress = currentPercentage + (targetPercentage - currentPercentage) * (currentStep / steps);

            // Update SVG circle
            const strokeDasharray = (circumference * progress) / 100 + ', ' + circumference;
            circle.setAttribute('stroke-dasharray', strokeDasharray);

            // Update color based on progress
            if (progress < 50) {
                circle.setAttribute('stroke', '#ffc107');
            } else if (progress < 100) {
                circle.setAttribute('stroke', '#17a2b8');
            } else {
                circle.setAttribute('stroke', '#28a745');
            }

            // Update text
            progressText.textContent = Math.round(progress) + '%';

            // Update progress bar
            progressBar.style.width = progress + '%';
            progressBar.textContent = Math.round(progress) + '%';

            // Update progress bar color
            if (progress < 50) {
                progressBar.className = 'progress-bar bg-warning';
            } else if (progress < 100) {
                progressBar.className = 'progress-bar bg-info';
            } else {
                progressBar.className = 'progress-bar bg-success';
            }

            if (currentStep >= steps) {
                clearInterval(animationInterval);
            }
        }, 100);
    }
</script>
