<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('vi-VN', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

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

    function loadBookings(page = 1) {
        $.ajax({
            url: '/loadCustomerBooking',
            type: 'GET',
            data: {
                page: page
            },
            success: function(response) {
                console.log(response);
                const $bookingsBody = $('#bookingsBody');
                const $pagination = $('#pagination');
                const $bookingsMessage = $('#bookingsMessage');
                $bookingsBody.empty();
                $pagination.empty();
                $bookingsMessage.empty();

                if (!response.is_authenticated) {
                    $bookingsBody.html('<tr><td colspan="6" class="text-muted text-center" style="font-size:30px">Vui lòng đăng nhập để xem lịch đã đặt</td></tr>');
                    return;
                }
                if (response.bookings.data.length === 0) {
                    $bookingsBody.html('<tr><td colspan="6" class="text-muted text-center">Bạn chưa có lịch hẹn nào</td></tr>');
                    return;
                }

                response.bookings.data.forEach(booking => {
                    const bookingDateTime = moment(booking.booking_date + ' ' + booking.start_time);
                    const isCancellable = booking.status !== 'cancelled' && booking.status !== 'completed' && bookingDateTime > moment();
                    const cancelButton = isCancellable ?
                        `<button class="btn btn-danger btn-sm btn-cancel" data-id="${booking.booking_id}">Hủy</button>` :
                        '';

                    $bookingsBody.append(`
                    <tr>
                        <td>${formatDate(booking.booking_date)}</td>
                        <td>${booking.start_time}</td>
                        <td>${booking.end_time}</td>
                        <td>${booking.service_name}</td>
                        <td>${getStatusBadge(booking.status)}</td>
                        <td>${cancelButton}</td>
                    </tr>
                `);
                });

                // Render pagination
                if (response.bookings.last_page > 1) {
                    const currentPage = response.bookings.current_page;
                    const lastPage = response.bookings.last_page;

                    $pagination.append(`
                    <ul class="pagination">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="#" data-page="${currentPage - 1}">Trước</a>
                        </li>
                `);

                    for (let i = 1; i <= lastPage; i++) {
                        $pagination.find('.pagination').append(`
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                    }

                    $pagination.find('.pagination').append(`
                    <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">Sau</a>
                    </li>
                    </ul>
                `);
                }
            },
            error: function(xhr) {
                const $bookingsBody = $('#bookingsBody');
                const $bookingsMessage = $('#bookingsMessage');
                $bookingsBody.empty();
                const message = xhr.responseJSON?.message || 'Đã xảy ra lỗi khi tải lịch hẹn.';
                $bookingsMessage.html(`<div class="alert alert-danger text-center">${message}</div>`);
            }
        });
    }

    $(document).on('click', '.btn-cancel', function() {
        const bookingId = $(this).data('id');
        if (confirm('Bạn có chắc muốn hủy lịch hẹn này?')) {
            $.ajax({
                url: '/cancelBooking',
                type: 'POST',
                data: {
                    booking_id: bookingId
                },
                success: function(response) {
                    const $bookingsMessage = $('#bookingsMessage');
                    if (response.success) {
                        $bookingsMessage.html('<div class="alert alert-success text-center">Hủy lịch hẹn thành công!</div>');
                        loadBookings(); // Refresh table
                    } else {
                        $bookingsMessage.html(`<div class="alert alert-danger text-center">${response.message}</div>`);
                    }
                },
                error: function(xhr) {
                    const $bookingsMessage = $('#bookingsMessage');
                    const message = xhr.responseJSON?.message || 'Lỗi khi hủy lịch hẹn.';
                    $bookingsMessage.html(`<div class="alert alert-danger text-center">${message}</div>`);
                }
            });
        }
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            loadBookings(page);
        }
    });

    // Initial load
    loadBookings();
</script>