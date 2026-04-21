@extends('shop.layout.app')
{{-- Kế thừa layout chính của shop --}}

@section('content')
{{-- Bắt đầu nội dung chính của trang --}}

{{-- Breadcrumb navigation --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $challenge->title }} - Tiến độ</li>
    </ol>
</nav>

{{-- Thông báo thành công --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successToast">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Layout chính với 2 cột --}}
<div class="row g-4">
    {{-- Cột chính chứa thông tin thử thách --}}
    <div class="col-lg-8">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h3 class="mb-0 fw-bold">{{ $challenge->title }}</h3>
            </div>
            <div class="card-body">
                {{-- Hình ảnh thử thách --}}
                <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}"
                     class="img-fluid rounded mb-4"
                     style="height: 250px; object-fit: cover;"
                     onerror="this.src='{{ asset('images/default.jpg') }}'
                     alt="Thử thách: {{ $challenge->title }}">

                {{-- Thông tin cơ bản --}}
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

                {{-- Mô tả thử thách --}}
                <h5 class="fw-bold mb-3">Mô tả thử thách</h5>
                <p class="lh-lg text-muted">{{ $challenge->description }}</p>

                {{-- Thông tin thời gian --}}
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

        {{-- Các nút hành động --}}
        <div class="d-grid gap-2 d-md-flex mb-4">
            <a href="{{ route('category.show', $category->id) }}" class="btn btn-outline-secondary px-4">← Quay lại</a>
            <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary px-4">Xem chi tiết</a>
        </div>
    </div>

    {{-- Cột sidebar hiển thị tiến độ --}}
    <div class="col-lg-4">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold text-center">Tiến độ của bạn</h5>
            </div>
            <div class="card-body text-center">
                {{-- Biểu đồ tròn hiển thị phần trăm hoàn thành --}}
                <div class="mb-4">
                    <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                        <svg style="width: 150px; height: 150px; transform: rotate(-90deg); margin-left: 0;">
                            {{-- Vòng tròn nền --}}
                            <circle cx="75" cy="75" r="65" fill="none" stroke="#e0e0e0" stroke-width="8"></circle>
                            {{-- Vòng tròn tiến độ --}}
                            <circle
                                id="progressCircle"
                                cx="75" cy="75" r="65"
                                fill="none"
                                {{-- Thay đổi màu theo tiến độ --}}
                                @if($progress->progress < 50)
                                    stroke="#ffc107" {{-- Vàng cho tiến độ thấp --}}
                                @elseif($progress->progress < 100)
                                    stroke="#17a2b8" {{-- Xanh dương cho tiến độ trung bình --}}
                                @else
                                    stroke="#28a745" {{-- Xanh lá cho hoàn thành --}}
                                @endif
                                stroke-width="10"
                                stroke-dasharray="{{ (2 * 3.14159 * 65 * $progress->progress) / 100 }}, {{ 2 * 3.14159 * 65 }}"
                                stroke-linecap="round"
                                style="transition: stroke-dasharray 0.5s ease;"
                            ></circle>
                        </svg>
                        {{-- Phần trăm ở giữa biểu đồ --}}
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <h2 class="fw-bold mb-0">{{ $progress->progress }}%</h2>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                </div>

                {{-- Trạng thái tiến độ --}}
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
                    {{-- Hiển thị ngày hoàn thành --}}
                    @if($progress->completed_at)
                        <p class="text-muted small mb-3">
                            Hoàn thành ngày: <strong>{{ $progress->completed_at->format('d/m/Y \l\ú\c H:i') }}</strong>
                        </p>
                    @endif
                @endif

                {{-- Thanh progress bar --}}
                <div class="progress mb-3" style="height: 25px;">
                    <div class="progress-bar
                        {{-- Thay đổi màu theo tiến độ --}}
                        @if($progress->progress < 50)
                            bg-warning
                        @elseif($progress->progress < 100)
                            bg-info
                        @else
                            bg-success
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

{{-- Phần phản hồi AI dành cho người dùng --}}
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

{{-- JavaScript để tự động ẩn thông báo thành công --}}
<script>
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => {
            successToast.remove(); {{-- Ẩn sau 5 giây --}}
        }, 5000);
    }
</script>

@endsection