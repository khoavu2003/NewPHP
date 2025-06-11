<!DOCTYPE html>
<html>

<head>
    <title>Đăng nhập</title>
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
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="background-color: #ccc;">
    @include('navbar')

    <div class="d-flex justify-content-center align-items-center flex-column mt-5">
        <div class="mb-3" style="color: #333333; font-weight: bold; font-size: 36px;">Đăng nhập</div>
        <div class="card p-4 shadow">
            <div id="loginForm">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" id="telNum" name="telNum" placeholder="Nhập số điện thoại" style="background-color: #ffffff;" required>
                        <button id="sendOtpBtn" class="btn btn-primary">Gửi OTP</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill"></i></span>
                        <input type="text" class="form-control" id="otpCode" name="otpCode" placeholder="Nhập mã otp" style="background-color: #ffffff;" required>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="text-start items-center">
                                <a href="/register" class="register-link">Đăng kí</a>
                            </div>

                        </div>
                        <div class="col">
                            <div class="text-end">
                                <button type="submit" id="verifyOtpBtn" class="btn btn-custom-login w-20 ">Đăng nhập</button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <div class="fixed-bottom">
        @include('footer')
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @include('script.login')

</body>

</html>