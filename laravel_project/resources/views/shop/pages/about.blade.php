{{-- File purpose: resources/views/shop/pages/about.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

@extends('shop.layout.app')
{{-- Trang Giới thiệu dùng layout chính của shop --}}

@section('content')
{{-- Bắt đầu nội dung chính của trang --}}

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active">Giới thiệu</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-8 mx-auto">
        {{-- Tiêu đề giới thiệu --}}
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">Giới thiệu Challenge Hub</h1>
            <p class="text-muted fs-5">Nền tảng học tập và phát triển kỹ năng qua các thử thách thực tế</p>
        </div>

        {{-- Nội dung chính giới thiệu --}}
        <div class="card mb-4">
            <div class="card-body p-5">
                <h3 class="fw-bold mb-4">Về chúng tôi</h3>
                <p class="lh-lg mb-4">
                    Challenge Hub là một nền tảng học tập hiện đại, được thiết kế để giúp bạn phát triển kỹ năng
                    thông qua các thử thách thực tiễn. Chúng tôi tin rằng cách tốt nhất để học là thực hành,
                    và mỗi thử thách được tạo ra để cung cấp trải nghiệm học tập sâu sắc và bổ ích.
                </p>

                <hr class="my-5">

                <h3 class="fw-bold mb-4">Tính năng nổi bật</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <h4>Học</h4>
                            </div>
                            <div>
                                <h5>Đa dạng thử thách</h5>
                                <p class="text-muted small">Nhiều thử thách từ cơ bản đến nâng cao trong các lĩnh vực khác nhau</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <h4>Theo dõi</h4>
                            </div>
                            <div>
                                <h5>Theo dõi tiến độ</h5>
                                <p class="text-muted small">Xem chi tiết mức độ hoàn thành và tiến độ của bạn</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <h4>Thành tựu</h4>
                            </div>
                            <div>
                                <h5>Đạt thành tựu</h5>
                                <p class="text-muted small">Hoàn thành các thử thách và nhận công nhận từ cộng đồng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <h4>Cộng đồng</h4>
                            </div>
                            <div>
                                <h5>Cộng đồng hỗ trợ</h5>
                                <p class="text-muted small">Kết nối với người học khác và chia sẻ kinh nghiệm</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-5">

                <h3 class="fw-bold mb-4">Bắt đầu ngay</h3>
                <p class="lh-lg">
                    Sẵn sàng để bắt đầu hành trình học tập của bạn?
                    <a href="{{ route('home') }}" class="text-decoration-none fw-bold">Khám phá các thử thách</a>
                    ngay bây giờ hoặc
                    <a href="{{ route('contact') }}" class="text-decoration-none fw-bold">liên hệ với chúng tôi</a>
                    nếu bạn có bất kỳ câu hỏi nào.
                </p>
            </div>
        </div>

        {{-- Call to action --}}
        <div class="alert alert-info text-center" role="alert">
            <h5 class="fw-bold mb-2">Bạn đã sẵn sàng chưa?</h5>
            <p class="mb-3">Tham gia hàng ngàn người học tập khác và bắt đầu hành trình phát triển kỹ năng của bạn</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                Khám phá thử thách
            </a>
        </div>
    </div>
</div>

@endsection
