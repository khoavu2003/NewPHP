<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   
   $('#registerBtn').on('click', function(e) {
        e.preventDefault();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val();
        let confirmPassword = $('#password_confirmation').val();
        let tel_num=$('#tel_num').val();
         let hasError = false;
         var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;
         console.log(email)
        if (!name) {
            $('#name').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập tên</div>');
            hasError = true;
        }else if (specialCharRegex.test(name)) {
                $('#name').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng không nhập kí tự đặc biệt</div>');
                isError = true;
            }

        if (!email) {
            $('#email').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập địa chỉ email</div>');
            hasError = true;
        }

        if (!password) {
            $('#password').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập mật khẩu</div>');
            hasError = true;
        }else if(password.length<6){
              $('#password').addClass('is-invalid').after('<div class="invalid-feedback">Mật khẩu có ít nhất 6 kí tự</div>');
            hasError = true;
        }
        if (!tel_num) {
            $('#tel_num').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập số điện thoại</div>');
            hasError = true;
        }
        
        if (!confirmPassword) {
            $('#password_confirmation').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng xác nhận mật khẩu</div>');
            hasError = true;
        } else if (password !== confirmPassword) {
            $('#password_confirmation').addClass('is-invalid').after('<div class="invalid-feedback">Mật khẩu xác nhận không khớp</div>');
            hasError = true;
        }

        if (hasError) return;


        $.ajax({
            url: '/register',
            type: 'POST',
            data: { 
                name: name,
                email: email,
                password: password,
                tel_num:tel_num
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng ký thành công!',
                        text: 'Chuyển hướng đến trang đăng nhập...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/login';
                    });
                }
            },
           error: function(xhr) {
                // Clear previous errors
                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        let input = $(`#${field}`);
                        input.addClass('is-invalid');

                        // Chèn thông báo lỗi sau input
                        let errorElement = $(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                        input.after(errorElement);
                    }
                } else {
                    let errorMessage = xhr.responseJSON?.message || 'Đã xảy ra lỗi khi gửi yêu cầu.';
                    Swal.fire({ 
                        icon: 'error',
                        title: 'Lỗi',
                        text: errorMessage
                    });
                }
            },
        });
    });
     $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
</script>