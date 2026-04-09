@extends('shop.layout.app')

@section('content')

<div class="mb-5">
    <h2 class="fw-bold mb-4"> Danh mục thử thách</h2>

    <!-- Lưới 3 cột: danh mục -->
    <div class="row g-4">
        @foreach($categories as $cate)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <!-- Hình ảnh danh mục -->
                    <img src="{{ asset('images/' . $cate->image) }}" class="card-img-top" onerror="this.src='{{ asset('images/default.jpg') }}'">

                    <!-- Nội dung -->
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">{{ $cate->name }}</h5>
                        <p class="text-muted small">{{ substr($cate->description, 0, 100) }}...</p>
                        <a href="{{ route('category.show', $cate->id) }}" class="btn btn-primary w-100">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
