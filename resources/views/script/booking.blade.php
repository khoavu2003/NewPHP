<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle booking submission
    $('#submitBooking').on('click', function(e) {
        e.preventDefault();

        // Collect form data
        let serviceId = $('#service_id').val();
        let bookingDate = $('#booking_date').val();
        let bookingTime = $('#booking_time').val();
        console.log(serviceId)

        // Basic client-side validation
        if (!serviceId || !bookingDate || !bookingTime) {
            alert('Vui lòng điền đầy đủ thông tin.');
            return;
        }

        // Disable button to prevent multiple submissions
        let submitButton = $(this);
        submitButton.prop('disabled', true).text('Đang xử lý...');

        // Perform AJAX request
        $.ajax({
            url: '/createBooking',
            type: 'POST',
            data: {
                service_id: serviceId,
                booking_date: bookingDate,
                start_time: bookingTime
            },
            success: function(response) {
                console.log(response)
                if (response.message.includes('thành công')) {
                    alert(response.message);
                    // Reset form fields
                    $('#service_id').val('');
                    $('#booking_date').val('');
                    $('#booking_time').val('');
                    updateTimeSlot();
                } else {
                    alert('Có lỗi xảy ra: ' + (response.message || 'Không xác định'));
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Đã xảy ra lỗi khi gửi yêu cầu.';
                alert(errorMessage);
            },
            complete: function() {
                // Re-enable button
                submitButton.prop('disabled', false).text('Đặt lịch');
            }
        });
    });
    $('#booking_date').on('change',updateTimeSlot);
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