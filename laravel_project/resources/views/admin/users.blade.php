<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">
                <i class="bi bi-people-fill me-2"></i>
                {{ Auth::user()->role === 'admin' ? 'Quản trị hệ thống' : 'Quản lý nhóm thử thách' }}
            </h5>
            <span class="badge bg-light text-dark">Tổng số: {{ $users->count() }}</span>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'useradmin')
                                    <span class="badge bg-info text-dark">Trưởng nhóm</span>
                                @else
                                    <span class="badge bg-secondary text-white">Thành viên</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Hoạt động</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Đang khóa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" onsubmit="return confirm('Xác nhận thay đổi trạng thái người dùng này?')">
                                    @csrf
                                    <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} w-100 px-3">
                                        @if($user->is_active)
                                            <i class="bi bi-lock"></i> Khóa
                                        @else
                                            <i class="bi bi-unlock"></i> Mở khóa
                                        @endif
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có người dùng nào thuộc phạm vi quản lý.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
