@extends('shop.layout.app')

@section('content')
    <section class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="fw-bold mb-2">Nhóm thử thách</h1>
                <p class="text-muted mb-0">Xem các nhóm đang hoạt động và tham gia nhóm phù hợp với bạn.</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Quay lại trang chủ</a>
        </div>
    </section>

    <section>
        <div class="row g-4">
            @forelse ($groups as $group)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="fw-bold mb-1">{{ $group->name }}</h4>
                                    <p class="text-muted small mb-0">
                                        Tạo bởi {{ $group->creator->name ?? 'Không rõ' }}
                                    </p>
                                </div>
                                @if (in_array($group->id, $joinedGroupIds))
                                    <span class="badge bg-success">Đã tham gia</span>
                                @else
                                    <span class="badge bg-secondary">Chưa tham gia</span>
                                @endif
                            </div>

                            <p class="text-muted flex-grow-1">
                                {{ $group->description ?: 'Nhóm này chưa có mô tả.' }}
                            </p>

                            <div class="d-flex justify-content-between small text-muted mb-3">
                                <span>{{ $group->users_count }} thành viên</span>
                                <span>{{ $group->challenges_count }} thử thách</span>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('user.groups.show', $group->id) }}" class="btn btn-outline-primary">
                                    Xem chi tiết
                                </a>

                                @if (in_array($group->id, $joinedGroupIds))
                                    <form action="{{ route('user.groups.leave', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100">Rời nhóm</button>
                                    </form>
                                @else
                                    <form action="{{ route('user.groups.join', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">Tham gia nhóm</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        Hiện chưa có nhóm nào đang hoạt động.
                    </div>
                </div>
            @endforelse
        </div>
    </section>
@endsection
