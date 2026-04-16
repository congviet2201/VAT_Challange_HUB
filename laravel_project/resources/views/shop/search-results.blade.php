@extends('shop.layout.app')
{{-- Kế thừa layout chính của shop --}}

@section('content')
{{-- Bắt đầu nội dung chính của trang --}}

{{-- Phần tiêu đề kết quả tìm kiếm --}}
<section class="mb-5 pb-4 border-bottom">
    <div class="row align-items-center g-4">
        <div class="col-md-12">
            <h1 class="fw-bold mb-3">Kết quả tìm kiếm</h1>
            {{-- Hiển thị từ khóa tìm kiếm --}}
            @if ($query)
                <p class="text-muted">Tìm kiếm: <strong>{{ $query }}</strong></p>
            @else
                <p class="text-muted">Hiển thị tất cả thử thách.</p>
            @endif
        </div>
    </div>
</section>

{{-- Phần hiển thị kết quả --}}
<section>
    {{-- Kiểm tra có kết quả hay không --}}
    @if ($challenges->isEmpty())
        {{-- Thông báo không tìm thấy --}}
        <div class="alert alert-warning">Không tìm thấy thử thách nào phù hợp với từ khóa.</div>
        {{-- Nút quay lại trang chủ --}}
        <a href="{{ route('home') }}" class="btn btn-secondary">Quay lại trang chủ</a>
    @else
        {{-- Hiển thị danh sách kết quả --}}
        <div class="row g-4">
            {{-- Vòng lặp qua từng thử thách --}}
            @foreach ($challenges as $challenge)
                <div class="col-md-6 col-lg-4">
                    {{-- Card Bootstrap cho mỗi thử thách --}}
                    <div class="card h-100 shadow-sm">
                        {{-- Hình ảnh theo độ khó --}}
                        <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}"
                             class="card-img-top"
                             style="height: 150px; object-fit: cover;"
                             onerror="this.src='{{ asset('images/default.jpg') }}'
                             alt="Độ khó: {{ $challenge->difficulty }}">

                        {{-- Nội dung card --}}
                        <div class="card-body d-flex flex-column text-center">
                            {{-- Badge hiển thị độ khó --}}
                            <div class="mb-2">
                                @if ($challenge->difficulty == 'easy')
                                    <span class="badge bg-success">Dễ</span>
                                @elseif($challenge->difficulty == 'medium')
                                    <span class="badge bg-warning text-dark">Trung bình</span>
                                @else
                                    <span class="badge bg-danger">Khó</span>
                                @endif
                            </div>

                            {{-- Tiêu đề thử thách --}}
                            <h5 class="fw-bold mb-3">{{ $challenge->title }}</h5>

                            {{-- Mô tả thử thách (cắt ngắn) --}}
                            <p class="text-muted small flex-grow-1 mb-3">
                                {{ substr($challenge->description, 0, 80) }}...
                            </p>

                            {{-- Hiển thị tên danh mục --}}
                            <div class="mb-3">
                                <small class="text-muted">Thể loại: {{ $challenge->category->name ?? 'Không rõ' }}</small>
                            </div>

                            {{-- Thời gian thực hiện hàng ngày --}}
                            @if ($challenge->daily_time > 0)
                                <small class="text-muted mb-3">{{ $challenge->daily_time }} phút/ngày</small>
                            @endif

                            {{-- Nút xem chi tiết --}}
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
