@extends('shop.layout.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 fw-bold">🔥 Danh mục thử thách</h2>

    <div class="row">
        @foreach($categories as $cate)
            <div class="col-md-4 mb-4">

                {{-- CATEGORY --}}
                <div class="card shadow-sm card-hover">

                    <img src="{{ asset('images/' . $cate->image) }}"
                         class="card-img-top">

                    <div class="card-body text-center">

                        <h5 class="fw-bold">{{ $cate->name }}</h5>
                        {{-- <p class="text-muted">{{ $cate->description }}</p> --}}

                        <a href="{{ route('category.show', $cate->id) }}" class="btn btn-primary">
                            Chi tiết
                        </a>


                    </div>
                </div>

                {{-- CHALLENGE LIST --}}
                <div id="challenge-{{ $cate->id }}" class="mt-3 d-none">

                    <div class="row">

                        @foreach($cate->challenges as $c)
                            <div class="col-md-12 mb-3">

                                <div class="card shadow-sm challenge-card">

                                    <div class="row g-0">

                                        {{-- IMAGE --}}
                                        <div class="col-md-4">
                                            <img src="{{ asset('images/' . $c->image) }}"
                                                 class="img-fluid h-100 rounded-start"
                                                 style="object-fit: cover;">
                                        </div>

                                        {{-- CONTENT --}}
                                        <div class="col-md-8">
                                            <div class="card-body d-flex flex-column">

                                                <h5 class="fw-bold">
                                                    {{ $c->title }}
                                                </h5>

                                                <p class="text-muted flex-grow-1">
                                                    {{ $c->description }}
                                                </p>

                                                {{-- LEVEL --}}
                                                <span class="badge mb-2
                                                    @if($c->difficulty=='easy') bg-success
                                                    @elseif($c->difficulty=='medium') bg-warning text-dark
                                                    @else bg-danger
                                                    @endif
                                                ">
                                                    {{ strtoupper($c->difficulty) }}
                                                </span>

                                                <small class="mb-2">
                                                    ⏱ {{ $c->daily_time }} phút/ngày
                                                </small>

                                                {{-- BUTTON --}}
                                                <button class="btn btn-primary mt-auto">
                                                    Tham gia thử thách
                                                </button>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>
        @endforeach
    </div>

</div>

{{-- CSS --}}
<style>
.card-hover {
    transition: 0.3s;
    border-radius: 12px;
}
.card-hover:hover {
    transform: translateY(-5px);
}

.card-img-top {
    height: 180px;
    object-fit: cover;
}

.challenge-card {
    border-radius: 12px;
    transition: 0.3s;
}
.challenge-card:hover {
    transform: scale(1.02);
}
</style>

{{-- JS --}}
<script>
function toggleChallenge(id) {

    // 👉 đóng tất cả trước (xịn hơn)
    document.querySelectorAll('[id^="challenge-"]').forEach(el => {
        if(el.id !== 'challenge-' + id){
            el.classList.add('d-none');
        }
    });

    // 👉 toggle cái đang click
    let el = document.getElementById('challenge-' + id);
    el.classList.toggle('d-none');
}
</script>

@endsection
