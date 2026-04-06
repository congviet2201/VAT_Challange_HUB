<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-img-top { height: 200px; object-fit: cover; border-radius: 8px 8px 0 0; }
        footer { background-color: #0b1120; color: white; padding: 40px 0; }
        .nav-link { color: #333; }
        .btn-register { background-color: #1a73e8; color: white; border-radius: 8px; }
    </style>
</head>
<body>

    {{-- Header --}}
    @include('shop.header')

    {{-- Nội dung --}}
    @yield('content')

    {{-- Footer --}}
    @include('shop.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</html>
