<script>
    function loadServices(page, service_name, duration_minute, price) {
        localStorage.setItem('currentPage', page);
        currentPage = page;
        $.ajax({
            url: '/admin/searchServices?page=' + page, // Action in Struts for loading customers
            type: 'GET',
            dataType: 'json',
            data: {
                page: page,
                service_name: service_name,
                duration_minute: duration_minute,
                price: price,
            },
            success: function(response) {
                console.log(response);
                $('#message').hide();
                if (response && response.serviceList && response.serviceList.length > 0) {
                    $('#serviceTable tbody').empty(); // Clear existing rows
                    $.each(response.serviceList, function(index, service) {
                        var row = '<tr>';
                        row += '<td><input type="checkbox" class="user-checkbox" data-id="' + service.service_id + '" /></td>';
                        row += '<td>' + service.service_name + '</td>';
                        row += '<td><img src="' + (service.image_url || 'https://via.placeholder.com/50') + '" alt="' + service.service_name + '" style="width: 70px; height: 50px; object-fit: cover;" /></td>';
                        row += '<td>' + service.description + '</td>';
                        row += '<td>' + service.price + '</td>';
                        row += '<td>' + service.duration_minute + '</td>';
                        row += '<td>';

                        // Edit button
                        row += '<a href="javascript:void(0);" class="edit-btn" data-id="' + service.service_id + '" title="Sửa">';
                        row += '<i class="bi bi-pencil-fill" style="color: #17a2b8; "></i></a> ';

                        // Delete button
                        row += '<a href="javascript:void(0);"  class="delete-btn" data-id="' + service.service_id + '" title="Xóa">';
                        row += '<i class="bi bi-trash-fill" style="color: #dc3545;"></i></a> ';

                        // Block/Unblock button
                        row += '<a href="javascript:void(0);" class="block-btn" data-id="' + service.service_id + '" title="Block/Unblock">';
                        row += '<i class="bi bi-person-fill-x" style="color: black"></i></a>';

                        row += '</td>';
                        row += '</tr>';
                        $('#serviceTable tbody').append(row);
                    });
                    $('#pagination-top').show(); // Ẩn phân trang
                    $('#pagination-bottom').show(); // Ẩn phân trang
                    var startIndex = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                    console.log(startIndex);
                    var endIndex = Math.min(response.pagination.current_page * response.pagination.per_page, response.pagination.total);
                    $('#start-index').text(startIndex);
                    $('#end-index').text(endIndex);
                    $('#total-services').text(response.pagination.total);
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
                    $('#serviceTable tbody').empty();
                    $('#serviceTable tbody').append('<tr><td colspan="6" class="text-center">Không có khách hàng nào.</td></tr>');
                    $('#pagination-top').hide();
                    $('#pagination-bottom').hide();
                }

            },
            error: function(xhr, status, error) {
                $('#message').text('Lỗi khi tải danh sách khách hàng!').show();
                $('#serviceTable tbody').empty();
            }
        });
    }
    loadServices(1, '', '', '', '');

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
            loadUsers(page, $('#search-name').val(), $('#search-email').val(), $('#search-group').val(), is_active); // Gọi lại ajax với trang mới
        });
    }
    $('#add-new-button').click(function() {
        // Clear form
        $('#addUserForm')[0].reset();
        $('#addMessage').hide().text('');
        $('#addUserModalLabel').text('Thêm Người Dùng Mới');
        $('#saveUserBtn').data('mode', 'add'); // Đặt trạng thái là thêm mới
        $('#addUserModal').modal('show');
    });
    $('#search-button').click(function() {
        var service_name = $('#search-name').val();
        var duration_minute = $('#search-duration-minute').val();
        var price = $('#search-price').val();
        var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;

        if (specialCharRegex.test(service_name)) {
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
        loadServices(currentPage, service_name, duration_minute, price);
    });
    $('#clear-button').click(function() {
        $('#search-name').val('');
        $('#search-duration-minute').val('');
        $('#search-price').val('');
        currentPage = 1;
        // Load first page with no search filters
        loadServices(currentPage, '', '', '');
    });
</script>