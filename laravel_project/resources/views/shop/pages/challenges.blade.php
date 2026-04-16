@extends('shop.layout.app')

@section('content')


    @if($keyword)
        <h2 class="mb-4">Kết quả tìm kiếm cho "{{ $keyword }}"</h2>
    @else
        <h2 class="mb-4">Tất cả thử thách</h2>
    @endif

<div class="row">
    @forelse($challenges as $challenge)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ asset('images/' . $challenge->difficulty . '.jpg') }}"
                     class="card-img-top"
                     style="height: 200px; object-fit: cover;"
                     alt="{{ $challenge->title }}"
                     onerror="this.src='{{ asset('images/default.jpg') }}'">

                <div class="card-body">
                    <h2 class="card-title fw-bold text-dark">{{ $challenge->title }}</h2>
                    <h4 class="card-text text-muted small">
                        {{ Str::limit($challenge->description, 100) }}
                    </h4>

                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        @if($challenge->difficulty == 'easy')
                            <span class="badge bg-success">Dễ</span>
                        @elseif($challenge->difficulty == 'medium')
                            <span class="badge bg-warning text-dark">Trung bình</span>
                        @else
                            <span class="badge bg-danger">Khó</span>
                        @endif

                        <small class="text-muted"><i class="far fa-clock"></i> {{ $challenge->daily_time }} phút</small>
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 pb-3 d-flex gap-2">
                    <a href="{{ route('challenge.detail', $challenge->id) }}"
                       class="btn btn-outline-primary btn-sm flex-grow-1">
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
                <p class="text-muted">Không tìm thấy thử thách nào với từ khóa "{{ $keyword }}".</p>
            @else
                <p class="text-muted">Chưa có thử thách nào.</p>
            @endif
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $challenges->appends(['keyword' => $keyword])->links() }}
</div>
</div>

@endsection
