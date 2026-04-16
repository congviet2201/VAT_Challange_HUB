@extends('shop.layout.app')

@section('content')

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $challenge->title }}</li>
    </ol>
</nav>

<div class="row g-4">

    <!-- PHẦN NỘI DUNG CHÍNH -->
    <div class="col-lg-8">
        <div class="card h-100">
            <!-- Hình ảnh -->
            <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}" class="card-img-top" style="height: 300px; object-fit: cover;" onerror="this.src='{{ asset('images/default.jpg') }}'">

            <!-- Nội dung -->
            <div class="card-body">
                <!-- Danh mục -->
                <a href="{{ route('category.show', $category->id) }}" class="badge bg-info mb-3">
                    {{ $category->name }}
                </a>

                <!-- Tiêu đề -->
                <h1 class="fw-bold mb-4">{{ $challenge->title }}</h1>

                <!-- Cấp độ & Thời gian -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <strong>🎯 Cấp độ:</strong>
                        @if($challenge->difficulty == 'easy')
                            <span class="badge bg-success">Dễ</span>
                        @elseif($challenge->difficulty == 'medium')
                            <span class="badge bg-warning">Trung bình</span>
                        @else
                            <span class="badge bg-danger">Khó</span>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <strong>⏱ Thời gian:</strong> {{ $challenge->daily_time }} phút/ngày
                    </div>
                </div>

                <hr>

                <!-- Mô tả -->
                <h4 class="fw-bold mb-3">📝 Mô tả thử thách</h4>
                <p class="lh-lg mb-4">{{ $challenge->description }}</p>

                <hr>

                <!-- Nút hành động -->
                @if (Auth::check())
                    <form action="{{ route('challenge.start', $challenge->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg" id="startChallengeBtn">✨ Bắt đầu thử thách</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="btn btn-primary btn-lg">
                        ✨ Bắt đầu thử thách
                    </a>
                @endif
                <a href="{{ route('category.show', $category->id) }}" class="btn btn-secondary btn-lg">← Quay lại</a>
            </div>
        </div>
    </div>

    <!-- PHẦN SIDEBAR -->
    <div class="col-lg-4">
        <!-- Thông tin danh mục -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📚 {{ $category->name }}</h5>
            </div>
            <div class="card-body">
                <img src="{{ asset('images/' . $category->image) }}" class="img-fluid rounded mb-3" style="height: 120px; object-fit: cover;" onerror="this.src='{{ asset('images/default.jpg') }}'">
                <p>{{ $category->description }}</p>
                <a href="{{ route('category.show', $category->id) }}" class="btn btn-outline-primary w-100">
                    Xem tất cả thử thách
                </a>
            </div>
        </div>

        <!-- Thử thách liên quan -->
        @if($relatedChallenges->count() > 0)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">🔗 Thử thách khác</h5>
                </div>
                <div class="card-body">
                    @foreach($relatedChallenges as $related)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-2">
                                <a href="{{ route('challenge.detail', $related->id) }}" class="text-decoration-none">
                                    {{ $related->title }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-2">
                                {{ substr($related->description, 0, 70) }}...
                            </p>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="badge
                                    @if($related->difficulty == 'easy') bg-success
                                    @elseif($related->difficulty == 'medium') bg-warning
                                    @else bg-danger
                                    @endif
                                ">
                                    @if($related->difficulty == 'easy') Dễ @elseif($related->difficulty == 'medium') Trung bình @else Khó @endif
                                </span>
                                <small>⏱ {{ $related->daily_time }}p</small>
                            </div>
                            <a href="{{ route('challenge.detail', $related->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                Chi tiết
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>

@endsection
