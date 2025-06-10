<script>
    function loadBookings(page = 1) {
        localStorage.setItem('currentPage', page);
        currentPage = page;
        const $resultContainer = $('#resultContainer');
        const $loadingState = $('#loadingState');
        const $noResults = $('#noResults');
        const $resultsContent = $('#resultsContent');
        const $historyContainer = $('#bookingHistory');

        $resultContainer.show();
        $loadingState.show();
        $noResults.hide();
        $resultsContent.hide();
        $historyContainer.empty();

        $.ajax({
            url: '/searchHistory',
            type: 'GET',
            data: {
                page: page,
                search: $('#searchInput').val()
            },
            success: function(response) {
                $loadingState.hide();
                console.log(response);

                if (response.status === 'success' && response.bookings.length > 0) {
                    const bookings = response.bookings;
                    const firstBooking = bookings[0];
                    let allHistoryHtml = '';

                    bookings.forEach(booking => {
                        const servicesText = booking.services.map(service =>
                            `${service.service_name} (<span class="highlight-cost">${Number(service.price).toLocaleString()} VND</span>)`
                        ).join(', ');
                        const partsText = booking.repair_parts.map(part =>
                            `${part.part_name} (x${part.quantity}, <span class="highlight-cost">${Number(part.part_cost).toLocaleString()} VND</span>)`
                        ).join(', ');
                        const isRated = booking.reviews.length > 0;
                        const ratingValue = isRated ? booking.reviews[0].rating : 0;
                        const commentText = isRated ? booking.reviews[0].comment : '';
                        console.log(ratingValue)
                        let bookingHtml = `
                        <div class="booking-group fade-in mb-4 p-4 border rounded">
                            <div class="booking-header font-bold text-lg">
                                Booking ID: ${booking.booking_id} | Ngày: ${booking.booking_date} | Model: ${booking.model} | Biển số xe: ${booking.license_plate}
                            </div>
                            <div class="booking-details mt-2">
                                ${servicesText ? `
                                <div class="history-item">
                                    <div class="history-service">Dịch vụ: ${servicesText}</div>
                                    <div class="mt-1"><em>${booking.services[0]?.description || ''}</em></div>
                                </div>` : ''}
                                ${partsText ? `
                                <div class="history-item">
                                    <div class="history-service">Phụ tùng: ${partsText}</div>
                                </div>` : ''}
                                <div class="history-item">
                                    <div class="history-service">Ghi chú kĩ thuật: ${booking.technician_note ? booking.technician_note : 'không có'}</div>
                                </div>
                                <div class="booking-total mt-3 font-semibold">
                                    Tổng chi phí: <span class="highlight-cost">${Number(booking.total_cost).toLocaleString()} VND</span>
                                </div>
                               <div class="rating-section mt-3">
                                    <div class="rating-label">Đánh giá:</div>
                                    <div class="star-rating" data-booking-id="${booking.booking_id}" ${isRated ? 'data-rated="true"' : ''}>
                                        ${[1, 2, 3, 4, 5].map(i => `
                                            <i class="fas fa-star star ${i <= ratingValue ? 'selected' : ''}" data-value="${i}" ${isRated ? 'style="pointer-events: none;"' : ''}></i>
                                        `).join('')}
                                    </div>
                                </div>
                                <div class="comment-input" data-booking-id="${booking.booking_id}" ${isRated ? 'style="display: none;"' : ''}>
                                    <textarea placeholder="Nhập bình luận của bạn..." rows="3" ${isRated ? 'disabled' : ''}></textarea>
                                    <button class="submit-comment" ${isRated ? 'disabled' : ''}>Gửi bình luận</button>
                                </div>
                                <div class="comment-display" data-booking-id="${booking.booking_id}">
                                    ${commentText ? `<div class="comment-text">${commentText}</div>` : ''}
                                </div>
                            </div>
                        </div>
                    `;

                        allHistoryHtml += bookingHtml;
                    });

                    $historyContainer.html(allHistoryHtml);
                    $resultsContent.show();
                    $('.star').on('click', function() {
                        const $star = $(this);
                        const rating = $star.data('value');
                        const bookingId = $star.parent().data('booking-id');
                        const $stars = $star.parent().find('.star');
                        const $commentInput = $(`.comment-input[data-booking-id="${bookingId}"]`);

                        $stars.each(function() {
                            if ($(this).data('value') <= rating) {
                                $(this).addClass('selected');
                            } else {
                                $(this).removeClass('selected');
                            }
                        });

                        // Show comment input
                        $commentInput.show();

                        // Submit rating to backend
                        $.ajax({
                            url: '/submitRating',
                            type: 'POST',
                            data: {
                                booking_id: bookingId,
                                rating: rating
                            },
                            success: function(response) {
                                console.log(`Đánh giá ${rating} sao cho booking ${bookingId} thành công`);
                            },
                            error: function(xhr) {
                                console.error('Lỗi khi gửi đánh giá:', xhr.responseJSON?.message || 'Đã xảy ra lỗi');
                            }
                        });
                    });

                    // Handle comment submission (interface-only)
                    $('.submit-comment').on('click', function() {
                        const $button = $(this);
                        const bookingId = $button.parent().data('booking-id');
                        const $commentInput = $button.siblings('textarea');
                        const comment = $commentInput.val().trim();

                        if (comment) {
                            const $commentDisplay = $(`.comment-display[data-booking-id="${bookingId}"]`);
                            $commentDisplay.text(comment).show();
                            $commentInput.val(''); // Clear input
                            $button.parent().hide(); // Hide comment input
                        }
                    });
                } else {
                    $noResults.show();
                    $resultsContent.hide();
                }
            },
            error: function(xhr) {
                $loadingState.hide();
                const $bookingsBody = $('#bookingsBody');
                const $bookingsMessage = $('#bookingsMessage');
                $bookingsBody.empty();
                const message = xhr.responseJSON?.message || 'Đã xảy ra lỗi khi tải lịch hẹn.';
                $bookingsMessage.html(`<div class="alert alert-danger text-center">${message}</div>`);
            }
        });
    }
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        const searchValue = $('#searchInput').val().trim();
        if (searchValue) {
            loadBookings(1);
        } else {
            $('#noResults').show();
            $('#resultsContent').hide();
            $('#loadingState').hide();
        }
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>