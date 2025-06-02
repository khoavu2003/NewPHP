<script>
    $('#loadingModal').fadeOut();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const $serviceId = $('#service_id');
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    if (serviceId && $serviceId.find(`option[value="${serviceId}"]`).length) {
        $serviceId.val(serviceId);
    }
    // Handle booking submission
    $('#submitBooking').on('click', function(e) {
        e.preventDefault();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#loadingModal').fadeIn();
        // Collect form data
        let serviceId = $('#service_id').val();
        let bookingDate = $('#booking_date').val();
        let bookingTime = $('#booking_time').val();
        var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var phoneRegex = /^[0-9+\s\-()]{9,11}$/;

        let isError = false;
        if (!serviceId) {
            $('#service_id').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng chọn dịch vụ</div>');
            isError = true;
        }

        if (!bookingDate) {
            $('#booking_date').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng chọn ngày</div>');
            isError = true;
        }
        if (!bookingTime) {
            $('#booking_time').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng chọn thời gian</div>');
            isError = true;
        }
        if (isError) {
            $('#loadingModal').fadeOut();
            return;
        }
        let data = {
            service_id: serviceId,
            booking_date: bookingDate,
            start_time: bookingTime,
        };

        if ($('#guest_name').length) {
            let guestName = $('#guest_name').val();
            let guestEmail = $('#guest_email').val();
            let guestPhone = $('#guest_phone').val();
            let isError = false;
            if (!guestName) {
                $('#guest_name').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập tên khách hàng</div>');
                isError = true;
            } else if (specialCharRegex.test(guestName)) {
                $('#guest_name').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng không nhập kí tự đặc biệt</div>');
                isError = true;
            }
            if (!guestPhone) {
                $('#guest_phone').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập số điện thoại</div>')
                isError = true;
            }else if(!phoneRegex.test(guestPhone)){
                $('#guest_phone').addClass('is-invalid').after('<div class="invalid-feedback">Số điện thoại không đúng định dạng</div>')
                isError=true;
            }
            if (!guestEmail) {
                $('#guest_email').addClass('is-invalid').after('<div class="invalid-feedback">Vui lòng nhập email</div>');
                isError = true;
            }else if(!emailRegex.test(guestEmail)){
                 $('#guest_email').addClass('is-invalid').after('<div class="invalid-feedback">Email sai định dạng</div>');
                isError = true;
            }
            if (isError) {
                 $('#loadingModal').fadeOut();
                return;
            }
            data.guest_name = guestName;
            data.guest_email = guestEmail;
            data.guest_phone = guestPhone;
        }

        // Disable button to prevent multiple submissions
        let submitButton = $(this);
        submitButton.prop('disabled', true).text('Đang xử lý...');

        // Perform AJAX request
        $.ajax({
            url: '/createBooking',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log(response)
                if (response.message.includes('thành công')) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt lịch thành công!',
                        html: `
                            <p>${response.message}</p>
                            <p><strong>Thời gian:</strong> ${response.data.start_time} - ${response.data.end_time}</p>
                            <p><strong>Ngày:</strong> ${response.data.booking_date}</p>
                        `,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reload trang khi bấm OK
                            window.location.reload();
                        }
                    });

                    updateTimeSlot();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể đặt lịch!',
                        html: `<p>${response.message}</p>`

                    });
                    updateTimeSlot();
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
            complete: function() {
                // Re-enable button
                submitButton.prop('disabled', false).text('Đặt lịch');
                $('#loadingModal').fadeOut();
            }
        });
    });
    $('#booking_date').on('change', updateTimeSlot);
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    function updateTimeSlot() {
        const bookingDate = $('#booking_date').val();
        const $timeContainer = $('#timeSlotsContainer');
        const $bookingTime = $('#booking_time');


        if (!bookingDate) {
            $timeContainer.empty().html('<p class="text-muted">Vui lòng chọn ngày</p>');
            $bookingTime.val('');
            return;
        }


        $.ajax({
            url: '/getWorkingHour',
            type: 'GET',
            data: {
                date: bookingDate
            },
            success: function(response) {
                const workingHours = response.working_hours;
                $timeContainer.empty();

                if (workingHours.is_closed) {
                    $timeContainer.html('<p class="text-muted">Cửa hàng đóng cửa vào ngày này</p>');
                    $bookingTime.val('');
                    return;
                }

                const startTime = moment(workingHours.start_time, 'HH:mm:ss');
                const endTime = moment(workingHours.end_time, 'HH:mm:ss');
                let breakStart = workingHours.break_start ? moment(workingHours.break_start, 'HH:mm:ss') : null;
                let breakEnd = workingHours.break_end ? moment(workingHours.break_end, 'HH:mm:ss') : null;

                // Check if the selected date is today
                const today = moment().format('YYYY-MM-DD');
                const isToday = bookingDate === today;
                const currentTime = isToday ? moment() : null;

                let current = startTime.clone();
                while (current < endTime) {
                    const currentTimeStr = current.format('HH:mm');
                    let isValid = true;
                    if (breakStart && breakEnd && current >= breakStart && current < breakEnd) {
                        isValid = false;
                    }
                    if (isToday && currentTime && current < currentTime) {
                        isValid = false;
                    }
                    if (isValid) {
                        const $button = $(`
                            <div class="time-slot" data-time="${currentTimeStr}">
                                ${currentTimeStr}
                            </div>
                        `);
                        $timeContainer.append($button);
                    }

                    current.add(20, 'minutes');
                }

                // Handle time slot selection
                $('.time-slot').on('click', function() {
                    $('.time-slot').removeClass('selected');
                    $(this).addClass('selected');
                    $bookingTime.val($(this).data('time'));
                });

                // If no valid slots, show message
                if ($timeContainer.children().length === 0) {
                    $timeContainer.html('<p class="text-muted">Không có khung giờ khả dụng</p>');
                }
            },
            error: function() {
                alert('Không thể tải giờ làm việc');
                $timeContainer.empty().html('<p class="text-muted">Không thể tải giờ làm việc</p>');
                $bookingTime.val('');
            }
        });
    }
</script>