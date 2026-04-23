@extends('shop.layout.app')

@section('content')

<div class="container mt-4">
    <h2 class="mb-3">Đặt mục tiêu</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->has('goal_ai'))
        <div class="alert alert-danger">{{ $errors->first('goal_ai') }}</div>
    @endif

    <button class="btn btn-primary mb-3" onclick="toggleForm()">
        <i class="bi bi-plus-circle"></i> Đặt mục tiêu
    </button>

    <form id="goalForm" action="/goals/store" method="POST" style="display:none;">
        @csrf

        <div id="goals-wrapper">
            <div class="goal-item border p-3 mb-3 rounded position-relative">

                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                        onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>

                <input class="form-control mb-2" type="text" name="goals[0][title]" placeholder="Tên mục tiêu" required>

                <select name="goals[0][category_id]" class="form-select mb-2" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cate)
                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                    @endforeach
                </select>

                <textarea class="form-control" name="goals[0][description]" placeholder="Mô tả"></textarea>

                <input class="form-control mt-2" type="number" min="1" max="365" name="goals[0][duration_days]" placeholder="Thời hạn mục tiêu (số ngày)" value="30" required>
            </div>
        </div>

        <div class="d-flex gap-2 mb-3">
            <button type="button" class="btn btn-success" onclick="addGoal()">
                <i class="bi bi-plus-lg"></i> Thêm mục tiêu
            </button>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Lưu mục tiêu
            </button>
        </div>
    </form>
</div>

@endsection
<script>
    window.categories = @json($categories);
</script>

<script src="/js/goals/script.js"></script>
