<!DOCTYPE html>
<html>
<head>
    <title>Đặt mục tiêu</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-3">Đặt mục tiêu</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" onclick="toggleForm()">
        <i class="bi bi-plus-circle"></i> Đặt mục tiêu
    </button>

    <form id="goalForm" action="/goals/store" method="POST" style="display:none;">
        @csrf

        <div id="goals-wrapper">
            <!-- FORM ĐẦU TIÊN -->
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
            </div>
        </div>

        <button type="button" class="btn btn-success mb-3" onclick="addGoal()">
            <i class="bi bi-plus-lg"></i> Thêm mục tiêu
        </button>

        <br>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu mục tiêu
        </button>
    </form>
</div>

<script>
    window.categories = @json($categories);
</script>

<script src="/js/goals/script.js"></script>

</body>
</html>
