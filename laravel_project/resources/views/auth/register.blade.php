<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-primary bg-gradient d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card shadow p-4" style="width: 400px; border-radius:15px;">

    <h3 class="text-center mb-3">Đăng ký</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger p-2">
            {{ $error }}
        </div>
    @endforeach

    <form method="POST" action="/register">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Nhập email">
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
        </div>

        <div class="mb-3">
            <label class="form-label">Vai trò</label>
            <select name="role" class="form-select">
                <option value="user">Người dùng</option>
                <option value="useradmin">User Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
    </form>

    <div class="text-center mt-3">
        <a href="/login">Đã có tài khoản?</a>
    </div>

</div>

</body>
</html>
