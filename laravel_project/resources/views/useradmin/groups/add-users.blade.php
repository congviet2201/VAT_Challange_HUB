{{-- File purpose: resources/views/useradmin/groups/add-users.blade.php --}}

@extends('useradmin.layout.app')
{{-- Trang thêm user vào nhóm cho UserAdmin --}}

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Thêm Thành Viên Vào Nhóm: {{ $group->name }}</h4>
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

                    @if($availableUsers->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle"></i> Không có người dùng nào khả dụng để thêm.
                            Tất cả người dùngjoinđã là thành viên của nhóm hoặc bị khóa.
                        </div>
                    @else
                        <form action="{{ route('useradmin.groups.add-users-store', $group->id) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">👤 Chọn Thành Viên</label>
                                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($availableUsers as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="user_ids[]" 
                                               value="{{ $user->id }}" id="user_{{ $user->id }}">
                                        <label class="form-check-label" for="user_{{ $user->id }}">
                                            <strong>{{ $user->name }}</strong>
                                            <small class="text-muted">({{ $user->email }})</small>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg flex-grow-1"
                                        onclick="return confirm('Xác nhận thêm thành viên?')">
                                    <i class="bi bi-check-circle"></i> Thêm Thành Viên
                                </button>
                                <a href="{{ route('useradmin.groups.show', $group->id) }}" class="btn btn-secondary btn-lg">
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
