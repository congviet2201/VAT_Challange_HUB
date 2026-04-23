{{-- File purpose: resources/views/useradmin/groups/add-challenges.blade.php --}}

@extends('useradmin.layout.app')
{{-- Trang thêm thử thách vào nhóm cho UserAdmin --}}

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm Thử Thách Vào Nhóm: {{ $group->name }}</h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($availableChallenges->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle"></i> Tất cả thử thách đã thuộc về nhóm này hoặc không có thử thách nào khả dụng.
                        </div>
                        <a href="{{ route('useradmin.groups.challenges', $group->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay Lại
                        </a>
                    @else
                        <form action="{{ route('useradmin.groups.add-challenges-store', $group->id) }}" method="POST">
                            @csrf

                            <div class="accordion mb-4" id="challengesAccordion">
                                @foreach($availableChallenges as $categoryName => $challenges)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapse{{ Str::slug($categoryName) }}">
                                                <strong>📂 {{ $categoryName }}</strong>
                                                <span class="badge bg-info ms-2">{{ $challenges->count() }} thử thách</span>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ Str::slug($categoryName) }}" class="accordion-collapse collapse" 
                                             data-bs-parent="#challengesAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    @foreach($challenges as $challenge)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-check border rounded p-3">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="challenge_ids[]"
                                                                       value="{{ $challenge->id }}" 
                                                                       id="challenge_{{ $challenge->id }}">
                                                                <label class="form-check-label w-100" for="challenge_{{ $challenge->id }}">
                                                                    <strong>{{ $challenge->title }}</strong><br>
                                                                    <small class="text-muted">{{ Str::limit($challenge->description, 80) }}</small><br>
                                                                    <div class="mt-2">
                                                                        @if($challenge->difficulty === 'easy')
                                                                            <span class="badge bg-success">🟢 Dễ</span>
                                                                        @elseif($challenge->difficulty === 'medium')
                                                                            <span class="badge bg-warning text-dark">🟡 Trung Bình</span>
                                                                        @else
                                                                            <span class="badge bg-danger">🔴 Khó</span>
                                                                        @endif
                                                                        <span class="badge bg-secondary">
                                                                            <i class="bi bi-clock"></i> {{ $challenge->daily_time }}p
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg flex-grow-1"
                                        onclick="return confirm('Xác nhận thêm thử thách cho nhóm?')">
                                    <i class="bi bi-check-circle"></i> Thêm Thử Thách
                                </button>
                                <a href="{{ route('useradmin.groups.challenges', $group->id) }}" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-arrow-left"></i> Quay Lại
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
