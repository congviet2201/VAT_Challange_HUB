<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #007bff;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --light-bg: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            padding: 20px 0;
        }

        /* HEADER */
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #007bff !important;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 8px;
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        /* BUTTONS */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* BADGES */
        .badge {
            border-radius: 20px;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }

        /* FOOTER */
        footer {
            background-color: #0b1120;
            color: white;
            padding: 40px 0;
            margin-top: auto;
            text-align: center;
        }

        footer h5, footer h6 {
            color: #ffffff;
            font-weight: 700;
        }

        footer p, footer ul {
            color: #c0c0c0;
        }

        footer .text-muted {
            color: #c0c0c0 !important;
        }

        footer a {
            color: #a8a8a8;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
            transition: 0.3s ease;
        }

        footer small {
            color: #b0b0b0;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar form {
                display: none;
            }

            .card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER (navigation bar) -->
    @include('shop.header')

    <!-- NỘI DUNG CHÍNH -->
    <main class="container">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('shop.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
