<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Roboto, Helvetica, Arial, Verdana, sans-serif;
            padding-top: 56px;
            /* Offset for fixed navbar */
        }

        .navbar {
            background-color: #333333;
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: bold;
            padding: 10px 15px;
            transition: background-color 0.3s;
        }

        .navbar .nav-link:hover {
            background-color: #e60000;
        }

        .navbar .nav-link.active {
            background-color: #e60000;
            color: white !important;
        }

        .navbar-nav .nav-item+.nav-item {
            margin-left: 20px;
        }

        .navbar-nav.ms-auto {
            margin-left: auto;
        }

        .dropdown-menu {
            background-color: #333333;
            border: none;
            z-index: 1000;
            /* Ensure dropdown is above other elements */
        }

        .dropdown-item {
            color: white;
        }

        .dropdown-item:hover {
            background-color: #e60000;
        }

        .dropdown-divider {
            border-color: #555;
        }

        .navbar-toggler-icon {
            filter: invert(1);
            /* White toggler icon for dark navbar */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('booking*') ? 'active' : '' }}" href="/booking">Đặt lịch sửa xe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('my-booking*') ? 'active' : '' }}" href="/my-booking">Dịch vụ đã đặt</a>
                    </li>

                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/login" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                @if (!session('customer_name'))
                                <a class="dropdown-item" href="/login">
                                    Login
                                </a>
                                @endif

                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        // Ensure CSRF token is included in AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>

</html>