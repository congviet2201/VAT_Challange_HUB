@extends('shop.layout.app')

@section('content')

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active">Liên hệ</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Tiêu đề -->
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">📞 Liên hệ với chúng tôi</h1>
            <p class="text-muted fs-5">Chúng tôi sẵn sàng lắng nghe ý kiến của bạn</p>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card text-center p-4">
                    <h4 class="mb-3">📧 Email</h4>
                    <p class="text-muted">
                        <a href="mailto:support@challengehub.com" class="text-decoration-none">
                            support@challengehub.com
                        </a>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center p-4">
                    <h4 class="mb-3">📱 Điện thoại</h4>
                    <p class="text-muted">
                        <a href="tel:+84123456789" class="text-decoration-none">
                            +84 (123) 456 789
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Form liên hệ -->
        <div class="card p-5">
            <h3 class="fw-bold mb-4">📝 Gửi tin nhắn cho chúng tôi</h3>

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Lỗi!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf

                <!-- Tên -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Tên của bạn *</label>
                    <input
                        type="text"
                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        placeholder="Nhập tên của bạn"
                        value="{{ old('name') }}"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Email của bạn *</label>
                    <input
                        type="email"
                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        placeholder="your.email@example.com"
                        value="{{ old('email') }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Chủ đề -->
                <div class="mb-4">
                    <label for="subject" class="form-label fw-bold">Chủ đề *</label>
                    <input
                        type="text"
                        class="form-control form-control-lg @error('subject') is-invalid @enderror"
                        id="subject"
                        name="subject"
                        placeholder="Nhập chủ đề"
                        value="{{ old('subject') }}"
                        required
                    >
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tin nhắn -->
                <div class="mb-4">
                    <label for="message" class="form-label fw-bold">Tin nhắn *</label>
                    <textarea
                        class="form-control form-control-lg @error('message') is-invalid @enderror"
                        id="message"
                        name="message"
                        rows="6"
                        placeholder="Nhập tin nhắn của bạn ở đây..."
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nút gửi -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">← Quay lại</a>
                    <button type="submit" class="btn btn-primary btn-lg">Gửi tin nhắn</button>
                </div>
            </form>
        </div>

        <!-- Thông tin bổ sung -->
        <div class="alert alert-light border mt-5 p-4" role="alert">
            <h5 class="fw-bold mb-2">⏱️Thời gian phản hồi</h5>
            <p class="mb-0">
                Chúng tôi sẽ cố gắng phản hồi nhanh trong vòng 24 giờ.
                Cảm ơn bạn đã liên hệ với Challenge Hub!
            </p>
        </div>
    </div>
</div>

@endsection
