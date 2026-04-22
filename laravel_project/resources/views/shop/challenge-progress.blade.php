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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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
                <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}" class="img-fluid rounded mb-4" style="height: 250px; object-fit: cover;" onerror="this.src='{{ asset('images/default.jpg') }}'">

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

                <div class="row text-center mt-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted">Thời gian hằng ngày</h6>
                        <h4 class="fw-bold">{{ $challenge->daily_time }} phút</h4>
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
            <div class="card-body text-center">
                <div class="mb-4">
                    <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                        <svg style="width: 150px; height: 150px; transform: rotate(-90deg); margin-left: 0;">
                            <circle cx="75" cy="75" r="65" fill="none" stroke="#e0e0e0" stroke-width="8"></circle>
                            <circle
                                id="progressCircle"
                                cx="75" cy="75" r="65"
                                fill="none"
                                @if($progress->progress < 50)
                                    stroke="#ffc107"
                                @elseif($progress->progress < 100)
                                    stroke="#17a2b8"
                                @else
                                    stroke="#28a745"
                                @endif
                                stroke-width="10"
                                stroke-dasharray="{{ (2 * 3.14159 * 65 * $progress->progress) / 100 }}, {{ 2 * 3.14159 * 65 }}"
                                stroke-linecap="round"
                                style="transition: stroke-dasharray 0.5s ease;"
                            ></circle>
                        </svg>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <h2 class="fw-bold mb-0">{{ $progress->progress }}%</h2>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                </div>

                @if($aiTaskCount > 0)
                    <p class="text-muted small mb-3">
                        {{ $completedAiTaskCount }}/{{ $aiTaskCount }} task AI đã hoàn thành
                    </p>
                @endif
                @if($progress->progress == 0)
                    <p class="mb-3">
                        <span class="badge bg-secondary p-3 fs-6">Chưa bắt đầu</span>
                    </p>
                @elseif($progress->progress < 100)
                    <p class="mb-3">
                        <span class="badge bg-info p-3 fs-6">Đang tiến hành</span>
                    </p>
                @else
                    <p class="mb-3">
                        <span class="badge bg-success p-3 fs-6">Đã hoàn thành</span>
                    </p>
                    @if($progress->completed_at)
                        <p class="text-muted small mb-3">
                            Hoàn thành ngày: <strong>{{ $progress->completed_at->format('d/m/Y \l\ú\c H:i') }}</strong>
                        </p>
                    @endif
                @endif

                <div class="progress mt-3" style="height: 10px; border-radius: 5px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated 
                        @if($progress->progress < 50) bg-warning @elseif($progress->progress < 100) bg-info @else bg-success @endif" 
                        role="progressbar" style="width: {{ $progress->progress }}%;"
                        aria-valuenow="{{ $progress->progress }}" aria-valuemin="0" aria-valuemax="100">
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

<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Lộ trình AI cá nhân hóa</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Nhập trình độ hiện tại của bạn để AI phân tích challenge này và tạo các task phù hợp riêng cho bạn.
        </p>

        <form action="{{ route('challenge.ai-roadmap', $challenge->id) }}" method="POST" class="mb-4">
            @csrf
            <label for="current_level" class="form-label fw-semibold">Trình độ hiện tại</label>
            <textarea
                id="current_level"
                name="current_level"
                rows="3"
                class="form-control @error('current_level') is-invalid @enderror"
                placeholder="Ví dụ: Tôi mới biết căn bản, từ vựng còn yếu và nghe chưa tốt."
            >{{ old('current_level', $latestAiPlan->current_level ?? '') }}</textarea>
            @error('current_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-dark mt-3">
                {{ $latestAiPlan ? 'Tạo lại lộ trình AI' : 'Tạo lộ trình AI' }}
            </button>
        </form>

        @if($latestAiPlan)
            <div class="border rounded p-3 bg-light mb-3">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <div class="small text-muted mb-1">Nguồn kế hoạch</div>
                        <span class="badge {{ $latestAiPlan->source === 'openai' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $latestAiPlan->source === 'openai' ? 'OpenAI' : 'Fallback local' }}
                        </span>
                    </div>
                    <div class="text-muted small">
                        Cập nhật: {{ $latestAiPlan->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                @if($latestAiPlan->summary)
                    <p class="mt-3 mb-0">{{ $latestAiPlan->summary }}</p>
                @endif
            </div>

            <div class="list-group">
                @foreach($latestAiPlan->tasks as $task)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $task->order }}. {{ $task->title }}</div>
                                @if($task->description)
                                    <div class="text-muted mt-1">{{ $task->description }}</div>
                                @endif

                                @if($task->completed_at)
                                    <div class="mt-2">
                                        <span class="badge bg-success">Đã hoàn thành</span>
                                        <span class="small text-muted ms-2">
                                            {{ $task->completed_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    @if($task->proof_image_path)
                                        <div class="mt-3">
                                            <div class="small text-muted mb-2">Ảnh minh chứng</div>
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($task->proof_image_path) }}"
                                                alt="Ảnh minh chứng cho {{ $task->title }}"
                                                class="img-fluid rounded border"
                                                style="max-width: 220px;"
                                            >
                                        </div>
                                    @endif
                                @else
                                    <form
                                        action="{{ route('challenge.ai-task.complete', [$challenge->id, $task->id]) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                        class="mt-3"
                                    >
                                        @csrf
                                        <label for="proof_image_{{ $task->id }}" class="form-label small fw-semibold">
                                            Tải ảnh minh chứng
                                        </label>
                                        <input
                                            id="proof_image_{{ $task->id }}"
                                            type="file"
                                            name="proof_image"
                                            accept="image/*"
                                            class="form-control form-control-sm"
                                            required
                                        >
                                        <button type="submit" class="btn btn-success btn-sm mt-2">
                                            Hoàn thành task
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="text-end small text-muted">
                                @if($task->estimated_minutes)
                                    <div>{{ $task->estimated_minutes }} phút</div>
                                @endif
                                @if($task->due_in_days)
                                    <div>Hoàn thành trong {{ $task->due_in_days }} ngày</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light border mb-0">
                Bạn chưa tạo lộ trình AI cho challenge này.
            </div>
        @endif
    </div>
</div>
<script>
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => {
            successToast.remove();
        }, 5000);
    }
</script>

@endsection
