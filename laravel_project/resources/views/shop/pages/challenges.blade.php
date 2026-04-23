{{-- File purpose: resources/views/shop/pages/challenges.blade.php --}}

@extends('shop.layout.app')

@section('content')
    @if($keyword)
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h2 class="mb-0">Kết quả tìm kiếm cho "{{ $keyword }}"</h2>
            <span class="badge rounded-pill text-bg-light">{{ $challenges->total() }} kết quả</span>
        </div>
    @else
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h2 class="mb-0">Tất cả thử thách</h2>
            <span class="badge rounded-pill text-bg-light">{{ $challenges->total() }} thử thách</span>
        </div>
    @endif

    <div class="row">
        @forelse($challenges as $challenge)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img
                        src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}"
                        class="card-img-top"
                        alt="{{ $challenge->title }}"
                        onerror="this.src='{{ asset('images/default.jpg') }}'"
                    >

                    <div class="card-body d-flex flex-column">
                        <h2 class="h4 card-title fw-bold text-dark">{{ $challenge->title }}</h2>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($challenge->description, 100) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if($challenge->difficulty === 'easy')
                                <span class="badge bg-success">Dễ</span>
                            @elseif($challenge->difficulty === 'medium')
                                <span class="badge bg-warning text-dark">Trung bình</span>
                            @else
                                <span class="badge bg-danger">Khó</span>
                            @endif

                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $challenge->daily_time }} phút
                            </small>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 pb-3 d-flex gap-2">
                        <a href="{{ route('challenge.detail', $challenge->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                            Chi tiết
                        </a>

                        @auth
                            <form action="{{ route('challenge.start', $challenge->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    Bắt đầu
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                @if($keyword)
                    <p class="text-muted mb-0">Không tìm thấy thử thách nào với từ khóa "{{ $keyword }}".</p>
                @else
                    <p class="text-muted mb-0">Chưa có thử thách nào.</p>
                @endif
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $challenges->links() }}
    </div>
@endsection
