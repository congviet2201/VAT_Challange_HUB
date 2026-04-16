@extends('shop.layout.app')

@section('content')
    <section class="mb-5 pb-4 border-bottom">
        <div class="row align-items-center g-4">
            <div class="col-md-12">
                <h1 class="fw-bold mb-3">Kết quả tìm kiếm</h1>
                @if ($query)
                    <p class="text-muted">Tìm kiếm: <strong>{{ $query }}</strong></p>
                @else
                    <p class="text-muted">Hiển thị tất cả thử thách.</p>
                @endif
            </div>
        </div>
    </section>

    <section>
        @if ($challenges->isEmpty())
            <div class="alert alert-warning">Không tìm thấy thử thách nào phù hợp với từ khóa.</div>
            <a href="{{ route('home') }}" class="btn btn-secondary">Quay lại trang chủ</a>
        @else
            <div class="row g-4">
                @foreach ($challenges as $challenge)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}" class="card-img-top"
                                style="height: 150px; object-fit: cover;"
                                onerror="this.src='{{ asset('images/default.jpg') }}'">

                            <div class="card-body d-flex flex-column text-center">
                                <div class="mb-2">
                                    @if ($challenge->difficulty == 'easy')
                                        <span class="badge bg-success">Dễ</span>
                                    @elseif($challenge->difficulty == 'medium')
                                        <span class="badge bg-warning text-dark">Trung bình</span>
                                    @else
                                        <span class="badge bg-danger">Khó</span>
                                    @endif
                                </div>

                                <h5 class="fw-bold mb-3">{{ $challenge->title }}</h5>

                                <p class="text-muted small flex-grow-1 mb-3">
                                    {{ substr($challenge->description, 0, 80) }}...
                                </p>

                                <div class="mb-3">
                                    <small class="text-muted">Thể loại: {{ $challenge->category->name ?? 'Không rõ' }}</small>
                                </div>

                                @if ($challenge->daily_time > 0)
                                    <small class="text-muted mb-3">{{ $challenge->daily_time }} phút/ngày</small>
                                @endif

                                <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-primary w-100 mt-auto">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
