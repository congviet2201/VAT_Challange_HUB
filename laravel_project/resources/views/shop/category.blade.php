@extends('shop.layout.app')

@section('content')

<div class="container mt-4">

    {{-- TOP: CATEGORY INFO --}}
    <div class="row mb-5 align-items-center">

        {{-- IMAGE --}}
        <div class="col-md-7">
        <img src="{{ asset('images/' . $category->image) }}"
            class="img-fluid rounded shadow w-100 category-img">
        </div>

        {{-- INFO --}}
        <div class="col-md-5">
            <h2 class="fw-bold mb-3">
                {{ $category->name }}
            </h2>

            <p class="text-muted fs-5">
                {{ $category->description }}
            </p>

            <a href="/" class="btn btn-outline-secondary mt-3">
                ← Quay lại
            </a>
        </div>

    </div>

    {{-- LEVEL / CHALLENGE --}}
    <h3 class="mb-4 fw-bold">🚀 Chọn cấp độ</h3>

    <div class="row">

        @foreach($challenges as $c)
            <div class="col-md-4 mb-4">

                <div class="card shadow-sm h-100 level-card">

                    {{-- IMAGE --}}
                    <img src="{{ asset('images/' . $c->difficulty . '.jpg') }}"
                        class="card-img-top">

                    <div class="card-body d-flex flex-column text-center">

                        {{-- LEVEL --}}
                        <h4 class="
                            @if($c->difficulty=='easy') text-success
                            @elseif($c->difficulty=='medium') text-warning
                            @else text-danger
                            @endif
                        ">
                            {{ strtoupper($c->difficulty) }}
                        </h4>

                        {{-- TITLE --}}
                        <h5 class="fw-bold">
                            {{ $c->title }}
                        </h5>

                        {{-- DESC --}}
                        <p class="text-muted flex-grow-1">
                            {{ $c->description }}
                        </p>

                        {{-- TIME --}}
                        <small class="mb-2">
                            ⏱ {{ $c->daily_time }} phút/ngày
                        </small>

                        {{-- BUTTON --}}
                        <button>
                            Tham gia
                        </button>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

</div>

{{-- CSS --}}
<style>
.level-card {
    border-radius: 12px;
    transition: 0.3s;
}
.level-card:hover {
    transform: translateY(-6px);
}
.category-img {
    height: 350px;
    object-fit: cover;
}
.card-img-top {
    height: 180px;
    object-fit: cover;
}
</style>

@endsection
