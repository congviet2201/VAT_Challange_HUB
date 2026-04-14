@extends('shop.layout.app')

@section('content')
    <!-- PHẦN HEADER: DANH MỤC INFO -->
    <section class="mb-5 pb-4 border-bottom">
        <div class="row align-items-center g-4">
            <!-- Hình ảnh -->
            <div class="col-md-5">
                <img src="{{ asset('images/' . $category->image) }}" class="img-fluid rounded shadow"
                    style="width: 100%; height: 300px; object-fit: cover;"
                    onerror="this.src='{{ asset('images/default.jpg') }}'">
            </div>

            <!-- Thông tin -->
            <div class="col-md-7">
                <h1 class="fw-bold mb-3">{{ $category->name }}</h1>
                <p class="text-muted fs-5 lh-lg mb-4">{{ $category->description }}</p>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    ← Quay lại
                </a>
            </div>
        </div>
    </section>

    <!-- PHẦN THỬ THÁCH: DANH SÁCH CÁC THỬ THÁCH -->
    <section>
        <h3 class="fw-bold mb-4">🎯 Danh sách thử thách {{ $category->name }}</h3>

        <div class="row g-4">
            @foreach ($challenges as $c)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Hình ảnh -->
                        <img src="{{ asset('images/' . $c->difficulty . '.jpg') }}" class="card-img-top"
                            style="height: 150px; object-fit: cover;"
                            onerror="this.src='{{ asset('images/default.jpg') }}'">

                        <!-- Nội dung -->
                        <div class="card-body d-flex flex-column text-center">
                            <!-- Cấp độ (nhỏ) -->
                            <div class="mb-2">
                                @if ($c->difficulty == 'easy')
                                    <span class="badge bg-success">🟢 Dễ</span>
                                @elseif($c->difficulty == 'medium')
                                    <span class="badge bg-warning text-dark">🟡 Trung bình</span>
                                @else
                                    <span class="badge bg-danger">🔴 Khó</span>
                                @endif
                            </div>

                            <!-- Tiêu đề (nổi bật) -->
                            <h5 class="fw-bold mb-3">{{ $c->title }}</h5>

                            <!-- Mô tả -->
                            <p class="text-muted small flex-grow-1 mb-3">
                                {{ substr($c->description, 0, 80) }}...
                            </p>

                            <!-- Thời gian -->
                            @if ($c->daily_time > 0)
                                <small class="text-muted mb-3">⏱ {{ $c->daily_time }} phút/ngày</small>
                            @endif

                            <!-- Nút -->
                            <a href="{{ route('challenge.detail', $c->id) }}" class="btn btn-primary w-100 mt-auto">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
