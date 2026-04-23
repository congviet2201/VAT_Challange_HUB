{{-- File purpose: resources/views/goals/index.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

@extends('shop.layout.app')

@section('content')
<div class="container mt-4">
    <h2>Mục tiêu của bạn</h2>

    <a href="{{ route('goals.create') }}" class="btn btn-primary mb-3">Đặt mục tiêu mới</a>

    @if($goals->isEmpty())
        <p>Bạn chưa có mục tiêu nào.</p>
    @else
        <div class="row">
            @foreach($goals as $goal)
                <div class="col-md-6 mb-4">
                    <div class="card {{ $goal->status === 'completed' ? 'border-success' : 'border-warning' }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $goal->title }}</h5>
                            <p class="card-text">{{ $goal->description }}</p>
                            <p class="text-muted">Danh mục: {{ $goal->category->name }}</p>
                            <p class="text-muted">Sub-goals: {{ $goal->subGoals->count() }}</p>
                            <p><strong>Trạng thái:</strong> {{ $goal->status === 'completed' ? 'Hoàn thành' : 'Đang tiến hành' }}</p>
                            <a href="{{ route('goals.show', $goal) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
