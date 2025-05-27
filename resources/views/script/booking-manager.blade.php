<script>
    var $services = [];

    function loadBookings(page, customer_name, customer_email, status, date) {
        localStorage.setItem('currentPage', page);
        currentPage = page;
        $.ajax({
            url: '/admin/searchBooking?page=' + page, // Action in Struts for loading customers
            type: 'GET',
            dataType: 'json',
            data: {
                page: page,
                customer_name: customer_name,
                customer_email: customer_name,
                status: status,
                booking_date: date,
            },
            success: function(response) {
                console.log(response);
                $('#message').hide();
                if (response && response.bookingList && response.bookingList.length > 0) {
                    $('#bookingTable tbody').empty(); // Clear existing rows
                    $.each(response.bookingList, function(index, booking) {
                        var row = '<tr>';

                        row += '<td>' + booking.customer_name + '</td>';
                        row += '<td>' + booking.customer_email + '</td>';
                        row += '<td>' + booking.service_name + '</td>';
                        row += '<td>' + booking.booking_date + '</td>';
                        row += '<td>' + booking.start_time + '</td>';
                        row += '<td>' + booking.end_time + '</td>';
                        row += '<td>' + getStatusBadge(booking.status) + '</td>';
                        row += '<td>';

                        // Edit button
                        row += '<a href="javascript:void(0);" class="edit-btn" data-id="' + booking.booking_id + '" title="Sửa">';
                        row += '<i class="bi bi-pencil-fill" style="color: #17A2B8;"></i></a> ';

                        row += '</td>';
                        row += '</tr>';
                        $('#bookingTable tbody').append(row);
                    });
                    $services = response.services;
                    console.log($services);
                    $('#pagination-top').show(); // Ẩn phân trang
                    $('#pagination-bottom').show(); // Ẩn phân trang
                    var startIndex = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                    console.log(startIndex);
                    var endIndex = Math.min(response.pagination.current_page * response.pagination.per_page, response.pagination.total);
                    $('#start-index').text(startIndex);
                    $('#end-index').text(endIndex);
                    $('#total-customers').text(response.pagination.total);
                    if (response.pagination.total <= 10) {
                        $('#pagination-top').hide(); // Ẩn phân trang
                        $('#pagination-bottom').hide(); // Ẩn phân trang
                    } else {
                        updatePagination('#pagination-top', response.pagination.current_page, response.pagination.last_page);
                        updatePagination('#pagination-bottom', response.pagination.current_page, response.pagination.last_page);
                    }
                } else {
                    $('#start-index').text(0);
                    $('#end-index').text(0);
                    $('#total-customers').text(response.pagination.total);
                    $('#bookingTable tbody').empty();
                    $('#bookingTable tbody').append('<tr><td colspan="6" class="text-center">Không có khách hàng nào.</td></tr>');
                    $('#pagination-top').hide();
                    $('#pagination-bottom').hide();
                }

            },
            error: function(xhr, status, error) {
                $('#message').text('Lỗi khi tải danh sách khách hàng!').show();
                $('#bookingTable tbody').empty();
            }
        });
    }
    loadBookings(1, '', '', '', '');
    //Update pagination
    function updatePagination(paginationId, currentPage, totalPages) {
        const $pagination = $(paginationId);
        currentPage = currentPage;
        $(paginationId).html('');
        if (currentPage > 1) {
            $pagination.append('<a href="#" class="page-control prev" data-page="' + (currentPage - 1) + '">‹</a> ');
        }
        for (var i = 1; i <= totalPages; i++) {
            if (i == currentPage) {
                $(paginationId).append('<a href="#" class="page active" data-page="' + i + '">' + i + '</a> ');
            } else {
                $(paginationId).append('<a href="#" class="page" data-page="' + i + '">' + i + '</a> ');
            }
        }
        if (currentPage < totalPages) {
            $pagination.append('<a href="#" class="page-control next" data-page="' + (currentPage + 1) + '">›</a>');
        }
        $pagination.find('a.page, a.page-control').off('click').on('click', function(e) {
            e.preventDefault();

            const page = $(this).data('page');
            loadBookings(page, $('#search-name').val(), $('#search-email').val(), $('#search-status').val(), $('#booking_date').val()); // Gọi lại ajax với trang mới
        });
    }
    $('#search-button').click(function() {
        var customer_name = $('#search-name').val();
        var customer_email = $('#search-email').val();
        var status = $('#search-status').val();
        var booking_date = $('#booking_date').val();
        var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;

        if (specialCharRegex.test(customer_name)) {
            Swal.fire({
                icon: 'warning',
                title: 'Ký tự không hợp lệ!',
                text: 'Vui lòng không nhập các ký tự đặc biệt!',
            });
            $('#search-name').val('');
            return;
        }

        // Trước khi gửi, kiểm tra giá trị của isActive

        currentPage = 1;
        // Tiến hành gọi hàm AJAX
        loadBookings(currentPage, customer_name, customer_email, status, booking_date);
    });
    $('#clear-button').click(function() {
        $('#search-name').val('');
        $('#search-email').val('');
        $('#search-status').val('');
        $('#booking_date').val('');
        currentPage = 1;
        // Load first page with no search filters
        loadBookings(currentPage, '', '', '');
    });

    function getStatusBadge(status) {
        switch (status) {
            case 'pending':
                return '<span class="badge bg-warning text-dark">Chờ xác nhận</span>';
            case 'confirmed':
                return '<span class="badge bg-success">Đã xác nhận</span>';
            case 'completed':
                return '<span class="badge bg-primary">Hoàn thành</span>';
            case 'cancelled':
                return '<span class="badge bg-danger">Đã hủy</span>';
            default:
                return '<span class="badge bg-secondary">Không xác định</span>';
        }
    }
    $('#add-new-booking-button').click(function() {

        $('#addBookingForm')[0].reset();
        $('#addBookingMessage').hide().text('');
        $('#addBookingModalLabel').text('Đặt lịch mới');
        var $serviceSelect = $('#service_id');
        $serviceSelect.empty();
        $serviceSelect.append('<option value="">Chọn dịch vụ</option>');
        if ($services.length > 0) {
            $.each($services, function(index, service) {
                $serviceSelect.append(
                    `<option value="${service.service_id}">${service.service_name} - ${service.duration_minute} phút</option>`
                );
            });
        } else {
            $serviceSelect.append('<option value="">Không có dịch vụ</option>');
        }

        $('#addBookingModal').modal('show');
    });
    $('#saveBookingBtn').on('click', function(e) {
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
        let data = {
            service_id: serviceId,
            booking_date: bookingDate,
            start_time: bookingTime,
        };

        if ($('#customer-name').length) {
            let guestName = $('#customer-name').val();
            let guestEmail = $('#customer-email').val();
            let guestPhone = $('#guest_phone').val();
            console.log(guestEmail)
            console.log(guestName)
            console.log(guestPhone)
            if (!guestName || !guestEmail ) {
                alert('Vui lòng điền đầy đủ thông tin khách hàng.');
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
                    alert(response.message);
                    // Reset form fields
                    $('#service_id').val('');
                    $('#booking_date').val('');
                    $('#booking_time').val('');
                    $('#guest_name').val('');
                    $('#guest_email').val('');
                    $('#guest_phone').val('');
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
    $('#booking_date').on('change', updateTimeSlot);

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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>