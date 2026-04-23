{{-- File purpose: resources/views/shop/home.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

@extends('shop.layout.app')
{{-- Kế thừa layout chính của shop --}}

@section('content')
{{-- Bắt đầu nội dung chính của trang --}}

<div class="mb-5">
    {{-- Tiêu đề trang --}}
    <h2 class="fw-bold mb-4">Danh mục thử thách</h2>

    {{-- Container chứa các card danh mục --}}
    <div class="row g-4">
        {{-- Vòng lặp qua từng danh mục --}}
        @foreach($categories as $cate)
            <div class="col-md-6 col-lg-4">
                {{-- Card Bootstrap cho mỗi danh mục --}}
                <div class="card h-100">
                    {{-- Hình ảnh danh mục --}}
                    <img src="{{ asset('images/' . $cate->image) }}"
                         class="card-img-top"
                         onerror="this.src='{{ asset('images/default.jpg') }}'"
                         alt="{{ $cate->name }}">

                    {{-- Nội dung card --}}
                    <div class="card-body text-center">
                        {{-- Tên danh mục --}}
                        <h5 class="card-title fw-bold">{{ $cate->name }}</h5>

                        {{-- Mô tả danh mục (cắt ngắn) --}}
                        <p class="text-muted small">{{ substr($cate->description, 0, 100) }}...</p>

                        {{-- Nút xem chi tiết --}}
                        <a href="{{ route('category.show', $cate->id) }}" class="btn btn-primary w-100">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Phân trang - chỉ hiển thị nếu có nhiều trang --}}
    @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Danh mục thử thách pagination">
                <ul class="pagination pagination-lg">
                    {{-- Nút Trang Trước --}}
                    @if ($categories->onFirstPage())
                        {{-- Nếu đang ở trang đầu, disable nút --}}
                        <li class="page-item disabled">
                            <span class="page-link">&laquo; Trước</span>
                        </li>
                    @else
                        {{-- Nếu không phải trang đầu, tạo link --}}
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->previousPageUrl() }}" rel="prev">&laquo; Trước</a>
                        </li>
                    @endif

                    {{-- Các số trang --}}
                    @for ($page = 1; $page <= $categories->lastPage(); $page++)
                        @if ($page == $categories->currentPage())
                            {{-- Trang hiện tại - active --}}
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            {{-- Các trang khác - có link --}}
                            <li class="page-item">
                                <a class="page-link" href="{{ $categories->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endfor

                    {{-- Nút Trang Sau --}}
                    @if ($categories->hasMorePages())
                        {{-- Nếu còn trang sau, tạo link --}}
                        <li class="page-item">
                            <a class="page-link" href="{{ $categories->nextPageUrl() }}" rel="next">Sau &raquo;</a>
                        </li>
                    @else
                        {{-- Nếu hết trang, disable nút --}}
                        <li class="page-item disabled">
                            <span class="page-link">Sau &raquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>

        {{-- Thông tin số lượng danh mục đang hiển thị --}}
        <div class="text-center mt-3 text-muted">
            <small>Hiển thị {{ $categories->firstItem() }}-{{ $categories->lastItem() }} trong tổng số {{ $categories->total() }} danh mục</small>
        </div>
    @endif

    </div>


{{-- CSS tùy chỉnh cho pagination --}}
<style>
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #0d6efd;
    border-color: #dee2e6;
    padding: 0.75rem 1rem;
    font-weight: 500;
    border-radius: 0.375rem !important;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #adb5bd;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

@media (max-width: 576px) {
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
}
</style>

@endsection
