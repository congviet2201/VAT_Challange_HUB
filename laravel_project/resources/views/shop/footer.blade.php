{{-- File purpose: resources/views/shop/footer.blade.php --}}
{{-- Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic hiá»ƒn thá»‹. --}}

{{-- Footer của trang shop --}}
<footer class="mt-auto py-5 bg-dark text-white">
    <div class="container">
        <div class="row g-4">
            {{-- Cột 1: Giới thiệu --}}
            <div class="col-md-3">
                <h5 class="fw-bold mb-3 text-primary">Challenge Hub</h5>
                <p class="text-light opacity-75 small lh-lg">Hệ thống quản lý thử thách giúp bạn phát triển bản thân và đạt được mục tiêu của mình.</p>
            </div>

            {{-- Cột 2: Danh mục --}}
            <div class="col-md-3">
                <h6 class="fw-bold mb-3">Danh mục</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Sức khỏe</a></li>
                    <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Đời sống</a></li>
                    <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Kiến thức</a></li>
                    <li><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Kỹ năng</a></li>
                </ul>
            </div>

            {{-- Cột 3: Thông tin --}}
            <div class="col-md-3">
                <h6 class="fw-bold mb-3">Thông tin</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-light opacity-75 text-decoration-none hover-link">Giới thiệu</a></li>
                    <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Điều khoản sử dụng</a></li>
                    <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-light opacity-75 text-decoration-none hover-link">Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            {{-- Cột 4: Liên hệ --}}
            <div class="col-md-3">
                <h6 class="fw-bold mb-3">Liên hệ</h6>
                <p class="text-light opacity-75 small mb-2"><i class="bi bi-envelope me-2"></i>contact@challengehub.vn</p>
                <p class="text-light opacity-75 small mb-2"><i class="bi bi-telephone me-2"></i>0123 456 789</p>
                <p class="text-light opacity-75 small mb-3"><i class="bi bi-geo-alt me-2"></i>Hà Nội, Việt Nam</p>
                {{-- Liên kết mạng xã hội --}}
                <div class="d-flex gap-3 small">
                    <a href="#" class="text-light opacity-75 hover-link"><i class="bi bi-facebook"></i> Facebook</a>
                    <a href="#" class="text-light opacity-75 hover-link"><i class="bi bi-tiktok"></i> TikTok</a>
                    <a href="#" class="text-light opacity-75 hover-link"><i class="bi bi-instagram"></i> Instagram</a>
                </div>
            </div>
        </div>

        {{-- Đường kẻ ngang --}}
        <hr class="my-4 border-secondary">

        {{-- Copyright --}}
        <p class="text-center text-light opacity-50 small mb-0">© 2026 Challenge Hub. Tất cả quyền được bảo lưu.</p>
    </div>
</footer>

<style>
    /* Hiệu ứng khi di chuột vào link ở footer */
    .hover-link:hover {
        color: #0d6efd !important; /* Màu xanh Primary */
        opacity: 1 !important;
        transition: 0.3s;
    }
</style>