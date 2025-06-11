<script>
    $(document).ready(function() {
        // Gửi OTP
        $('#sendOtpBtn').on('click', function(e) {
            e.preventDefault(); // Ngăn submit form mặc định
            const telNum = $('#telNum').val().trim();
            if (!telNum) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lỗi',
                    text: 'Vui lòng nhập số điện thoại!',
                    timer: 2000
                });
                return;
            }

            $(this).prop('disabled', true).text('Đang gửi...');

            $.ajax({
                url: '/send-otp',
                type: 'POST',
                data: {
                    tel_num: telNum,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            timer: 2000
                        });
                        $('#otpCode').focus(); // Focus vào input OTP
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: xhr.responseJSON?.message || 'Không thể gửi OTP.',
                        timer: 2000
                    });
                },
                complete: function() {
                    $('#sendOtpBtn').prop('disabled', false).text('Gửi OTP');
                }
            });
        });

        // Xác thực OTP
        $('#verifyOtpBtn').on('click', function(e) {
            e.preventDefault(); // Ngăn submit form mặc định
            const telNum = $('#telNum').val().trim();
            const otpCode = $('#otpCode').val().trim();

            if (!telNum) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lỗi',
                    text: 'Vui lòng nhập số điện thoại!',
                    timer: 2000
                });
                return;
            }

            if (!otpCode) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lỗi',
                    text: 'Vui lòng nhập mã OTP!',
                    timer: 2000
                });
                return;
            }

            $(this).prop('disabled', true).text('Đang xác nhận...');

            $.ajax({
                url: '/verify-otp',
                type: 'POST',
                data: {
                    tel_num: telNum,
                    otp_code: otpCode,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            window.location.href = '/'; // Chuyển hướng về trang chính
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: xhr.responseJSON?.message || 'OTP không hợp lệ.',
                        timer: 2000
                    });
                },
                complete: function() {
                    $('#verifyOtpBtn').prop('disabled', false).text('Đăng nhập');
                }
            });
        });
    });
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>