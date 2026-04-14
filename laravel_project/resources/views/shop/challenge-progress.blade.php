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
                        <h4 class="fw-bold">{{ $challenge->daily_time }} phút</h4>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="text-muted">📅 Bắt đầu từ</h6>
                        <h4 class="fw-bold">{{ $progress->started_at->format('d/m/Y') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đánh giá & Điều hướng -->
        <div class="d-grid gap-2 d-md-flex mb-4">
            <a href="{{ route('category.show', $category->id) }}" class="btn btn-secondary btn-lg">← Quay lại</a>
            <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary btn-lg">ℹ️ Chi tiết</a>
        </div>

        <!-- Danh sách Tasks -->
        @if($tasks && count($tasks) > 0)
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">✓ Danh Sách Công Việc (<span id="completedCount">{{ $completedTasks }}</span>/{{ $totalTasks }})</h5>
                </div>
                <div class="card-body">
                    <div class="task-list">
                        @foreach($tasks as $task)
                            <div class="task-item mb-3 pb-3 border-bottom" style="display: flex; align-items: center; gap: 12px;">
                                <input type="checkbox"
                                    class="form-check-input task-checkbox"
                                    id="task-{{ $task->id }}"
                                    data-task-id="{{ $task->id }}"
                                    data-challenge-id="{{ $challenge->id }}"
                                    {{ $task->completions->count() > 0 ? 'checked' : '' }}
                                    style="width: 20px; height: 20px; cursor: pointer; margin-top: 0;">
                                <label for="task-{{ $task->id }}" style="margin-bottom: 0; cursor: pointer; flex-grow: 1;">
                                    <span class="task-title {{ $task->completions->count() > 0 ? 'text-decoration-line-through text-muted' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
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
                        <svg id="progressCircle" style="width: 150px; height: 150px; transform: rotate(-90deg); margin-left: 0;">
                            <!-- Background circle -->
                            <circle cx="75" cy="75" r="65" fill="none" stroke="#e0e0e0" stroke-width="8"></circle>
                            <!-- Progress circle -->
                            <circle id="progressCircleStroke"
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
                            <h2 class="fw-bold mb-0" id="progressPercent">{{ $progress->progress }}%</h2>
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

                <!-- Progress Bar -->
                <div class="progress mb-3" style="height: 25px;">
                    <div id="progressBar" class="progress-bar
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

    // Handle task checkbox click
    document.querySelectorAll('.task-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const challengeId = this.dataset.challengeId;

            // Send AJAX request
            fetch('{{ route("challenge.toggleTask") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    task_id: taskId,
                    challenge_id: challengeId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the task text styling
                    const taskLabel = document.querySelector(`label[for="task-${taskId}"]`);
                    if (taskLabel) {
                        const titleSpan = taskLabel.querySelector('.task-title');
                        if (data.is_completed) {
                            titleSpan.classList.add('text-decoration-line-through', 'text-muted');
                        } else {
                            titleSpan.classList.remove('text-decoration-line-through', 'text-muted');
                        }
                    }

                    // Update completed count
                    document.getElementById('completedCount').textContent = data.completed_tasks;

                    // Update progress percentage
                    const progressPercent = data.progress_percent;
                    document.getElementById('progressPercent').textContent = progressPercent + '%';

                    // Update progress bar
                    const progressBar = document.getElementById('progressBar');
                    progressBar.style.width = progressPercent + '%';
                    progressBar.setAttribute('aria-valuenow', progressPercent);
                    progressBar.textContent = progressPercent + '%';

                    // Update progress bar color
                    progressBar.className = 'progress-bar';
                    if (progressPercent < 50) {
                        progressBar.classList.add('bg-warning');
                    } else if (progressPercent < 100) {
                        progressBar.classList.add('bg-info');
                    } else {
                        progressBar.classList.add('bg-success');
                    }

                    // Update circle stroke
                    const circleStroke = document.getElementById('progressCircleStroke');
                    const circumference = 2 * 3.14159 * 65;
                    const strokeDasharray = (circumference * progressPercent) / 100 + ', ' + circumference;
                    circleStroke.setAttribute('stroke-dasharray', strokeDasharray);

                    // Update circle color
                    if (progressPercent < 50) {
                        circleStroke.setAttribute('stroke', '#ffc107');
                    } else if (progressPercent < 100) {
                        circleStroke.setAttribute('stroke', '#17a2b8');
                    } else {
                        circleStroke.setAttribute('stroke', '#28a745');
                    }

                    // Update status badge
                    const statusBadge = document.getElementById('statusBadge');
                    statusBadge.className = 'badge p-3 fs-6';
                    if (progressPercent === 0) {
                        statusBadge.classList.add('bg-secondary');
                        statusBadge.textContent = '🚀 Chưa bắt đầu';
                    } else if (progressPercent < 100) {
                        statusBadge.classList.add('bg-info');
                        statusBadge.textContent = '🔄 Đang tiến hành';
                    } else {
                        statusBadge.classList.add('bg-success');
                        statusBadge.textContent = '✅ Đã hoàn thành';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
