<!DOCTYPE html>
<html>

<head>
    <title>Đăng ký</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .card {
            width: 500px;
            height: auto;
        }

        .form-control {
            width: 100%;
            max-width: 100%;
            padding-left: 30px;
        }

        .input-group-text i {
            font-size: 18px;
        }

        .form-group {
            margin: 40px 40px;
        }

        .alert {
            margin-top: 10px;
            font-size: 14px;
        }

        .register-link {
            color: #005599;
            text-decoration: none;
            margin-left: 35px;
            font-weight: 300;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            transition: color 0.2s ease;
        }

        .register-link:hover {
            color: #79CDCD;
        }

        .btn-custom-login {
            background-color: #333333 !important;
            border-color: #333333 !important;
            color: white !important;
            transition: background-color 0.3s ease;
            margin-right: 35px;
        }

        .btn-custom-login:hover {
            background-color: #555555 !important;
            border-color: #555555 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="background-color: #ccc;">
    @include('navbar')

    <div class="d-flex justify-content-center align-items-center flex-column mt-5">
        <div class="mb-3" style="color: #333333; font-weight: bold; font-size: 36px;">Đăng ký</div>
        <div class="card p-4 shadow">
            <!-- Thay thế <form> bằng <div> chứa input -->
            <div id="registerBox">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" id="name" placeholder="Họ tên" style="background-color: #ffffff;">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="Email" style="background-color: #ffffff;">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" class="form-control" id="tel_num" placeholder="Nhập số diện thoại" style="background-color: #ffffff;">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Mật khẩu" style="background-color: #ffffff;">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Nhập lại mật khẩu" style="background-color: #ffffff;">
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="text-start items-center">
                                <a href="/login" class="register-link">Đăng nhập</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-end">
                                <button id="registerBtn" class="btn btn-custom-login w-20">Đăng ký</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="">
        @include('footer')
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @include('script.register')
</body>

</html>