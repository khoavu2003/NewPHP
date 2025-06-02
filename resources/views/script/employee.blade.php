<script>
    function loadEmployees(page, employee_name, email, is_active) {
        localStorage.setItem('currentPage', page);
        currentPage = page;
        $.ajax({
            url: '/admin/searchEmployee?page=' + page, // Action in Struts for loading customers
            type: 'GET',
            dataType: 'json',
            data: {
                page: page,
                employee_name: employee_name,
                email: email,
                is_active: is_active,
            },
            success: function(response) {
                console.log(response);
                $('#message').hide();
                if (response && response.employeeList && response.employeeList.length > 0) {
                    $('#employeeTable tbody').empty(); // Clear existing rows
                    $.each(response.employeeList, function(index, employee) {
                        var row = '<tr>';
                        row += '<td>' + employee.employee_name + '</td>';
                        row += '<td>' + employee.email + '</td>';
                        row += '<td>' + employee.tel_num + '</td>';
                        row += '<td>' + (employee.is_active ? '<span class="badge text-success">Đang hoạt động</span>' : '<span class="badge text-danger">Tạm khoá</span>') + '</td>';
                        row += '<td>';

                        // Edit button
                        row += '<a href="javascript:void(0);" class="edit-btn" data-id="' + employee.employee_id + '" title="Sửa">';
                        row += '<i class="bi bi-pencil-fill" style="color: #17a2b8; "></i></a> ';

                        // Delete button
                        row += '<a href="javascript:void(0);"  class="delete-btn" data-id="' + employee.employee_id + '" title="Xóa">';
                        row += '<i class="bi bi-trash-fill" style="color: #dc3545;"></i></a> ';

                        // Block/Unblock button
                        row += '<a href="javascript:void(0);" class="block-btn" data-id="' + employee.employee_id + '" title="Block/Unblock">';
                        row += '<i class="bi bi-person-fill-x" style="color: black"></i></a>';

                        row += '</td>';
                        row += '</tr>';
                        $('#employeeTable tbody').append(row);
                    });
                    $('#pagination-top').show(); // Ẩn phân trang
                    $('#pagination-bottom').show(); // Ẩn phân trang
                    var startIndex = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                    console.log(startIndex);
                    var endIndex = Math.min(response.pagination.current_page * response.pagination.per_page, response.pagination.total);
                    $('#start-index').text(startIndex);
                    $('#end-index').text(endIndex);
                    $('#total-employees').text(response.pagination.total);
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
                    $('#employeeTable tbody').empty();
                    $('#employeeTable tbody').append('<tr><td colspan="6" class="text-center">Không có khách hàng nào.</td></tr>');
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
    loadEmployees(1, '', '', '', '');

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
            loadEmployees(page, $('#search-name').val(), $('#search-email').val(), is_active); // Gọi lại ajax với trang mới
        });
    }
    //pop up modal add new button
    $('#add-new-button').click(function() {
        // Clear form
        $('#addEmployeeForm')[0].reset();
        $('#addEmployeeMessage').hide().text('');
        $('#addEmployeeModalLabel').text('Thêm Người Dùng Mới');
        $('#saveEmployeeBtn').data('mode', 'add'); // Đặt trạng thái là thêm mới
        $('#addEmployeeModal').modal('show');
    });
    //Handle search button click
    $('#search-button').click(function() {
        var employee_name = $('#search-name').val();
        var email = $('#search-email').val();
        var is_active = $('#search-status').val();
        var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;

        if (specialCharRegex.test(employee_name)) {
            Swal.fire({
                icon: 'warning',
                title: 'Ký tự không hợp lệ!',
                text: 'Vui lòng không nhập các ký tự đặc biệt!',
            });
            $('#search-name').val('');
            return;
        }

        // Trước khi gửi, kiểm tra giá trị của isActive
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else if (is_active === "Tạm Khoá") {
            is_active = 0;
        } else {
            is_active = '';
        }
        currentPage = 1;
        // Tiến hành gọi hàm AJAX
        loadEmployees(currentPage, employee_name, email, is_active);
    });
    //Handle clear search
    $('#clear-button').click(function() {
        $('#search-name').val('');
        $('#search-email').val('');
        $('#search-status').val('');
        currentPage = 1;
        // Load first page with no search filters
        loadEmployees(currentPage, '', '', '');
    });
    //handle save employee button
    $('#saveEmployeeBtn').click(function(e) {
        e.preventDefault();

        const employee_name = $('#employee-name').val();
        const email = $('#employee-email').val();
        const tel_num = $('#tel_num').val();
        var is_active = $('#employee-status').val();
        console.log(email);
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else {
            is_active = 0;
        }
        console.log("o day tra is active", is_active)
        // Kiểm tra mật khẩu và xác nhận mật khẩu có khớp không

        const mode = $(this).data('mode');
        const employeeId = $(this).data('id');
        console.log('userId', employeeId)
        let url = '';
        if (mode === 'edit') {
            url = '/admin/updateEmployees/' + employeeId;
        } else {
            url = '/admin/addEmployees';
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                employee_name: employee_name,
                email: email,
                tel_num: tel_num,
                is_active: is_active
            },
            success: function(response) {
                console.log('Phản hồi từ server:', response);

                if (response && response.message) {
                    if (response.message.includes('thành công')) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#addEmployeeModal').modal('hide');
                            loadEmployees(currentPage, '', '', '', '');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: response.message
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Không nhận được phản hồi từ server.'
                    });
                }
            },
            error: function(xhr, status, error) {
                let message = 'Đã xảy ra lỗi.';

                if (xhr.responseJSON) {
                    const res = xhr.responseJSON;

                    // Gán message mặc định
                    message = res.message || message;

                    // Nếu có chi tiết lỗi
                    if (res.errors) {
                        const firstError = Object.values(res.errors)[0][0]; // Lấy lỗi đầu tiên
                        message = firstError;
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: message
                });
            }
        });
    });
    $(document).on('click', '.edit-btn', function() {
        var employeeId = $(this).data('id');
        $.ajax({
            url: '/admin/getEmployeeById/' + employeeId,
            type: 'GET',
            dataType: 'json',
            data: {
                id: employeeId
            },

            success: function(response) {
                //console.log(" Response từ server:", response);
                console.log(" Response từ server:", response.employee);
                if (response && response.employee) {
                    const employee = response.employee;
                    console.log(" Dữ liệu người dùng nhận được:", employee);

                    // Gán dữ liệu vào form
                    $('#employee-name').val(employee.employee_name);
                    $('#employee-email').val(employee.email);

                    $('#tel_num').val(employee.tel_num);

                    console.log(employee.is_active);
                    console.log("o day tra user" + employee.is_active);

                    // Gán trạng thái vào form
                    if (employee.is_active === 0) {
                        $('#employee-status').val('Tạm Khoá'); // Tạm Khoá
                    } else {
                        $('#employee-status').val('Đang Hoạt Động'); // Đang Hoạt Động
                    }


                    // Set chế độ và id user đang sửa
                    $('#addEmployeeModalLabel').text('Chỉnh sửa người dùng');
                    $('#saveEmployeeBtn').data('mode', 'edit').data('id', employee.employee_id);

                    $('#addEmployeeMessage').hide().text('');
                    $('#addEmployeeModal').modal('show');
                }
            },
            error: function() {
                alert('Không thể tải dữ liệu người dùng.');
            }
        });
    });
    $(document).on('click', '.block-btn', function() {
        var employeeId = $(this).data('id');
        var button = $(this);
        var employee_name = $('#search-name').val();
        var email = $('#search-email').val();
        var is_active = $('#search-status').val();
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else if (is_active === "Tạm Khoá") {
            is_active = 0;
        } else {
            is_active = '';
        }
        $.ajax({
            url: '/admin/updateStatusEmployees/' + employeeId, // Gọi action blockUser
            type: 'POST',
            dataType: 'json',
            data: {
                id: employeeId
            }, // Truyền userId để xử lý
            success: function(response) {
               
                if (response.status.includes('Success')) {
                    loadEmployees(currentPage, name, email, is_active);
                } else {
                    alert('Lỗi khi thay đổi trạng thái người dùng!');
                }
            },
            error: function(xhr, status, error) {
                console.log("Lỗi khi thay đổi trạng thái người dùng:", error);
                alert('Lỗi khi thay đổi trạng thái người dùng!');
            }
        });
    });
    $(document).on('click', '.delete-btn', function() {
        var employeeId = $(this).data('id'); // Lấy userId từ data-id
        var name = $('#search-name').val();
        var email = $('#search-email').val();
        var group_role = $('#search-group').val();
        var is_active = $('#search-status').val();
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else if (is_active === "Tạm Khoá") {
            is_active = 0;
        } else {
            is_active = '';
        }
        console.log(is_active);

        // Sử dụng SweetAlert2 thay cho confirm
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Bạn sẽ không thể hoàn tác sau khi xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng xác nhận thì mới gửi AJAX
                $.ajax({
                    url: '/admin/deleteEmployees/' + employeeId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: employeeId
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status.includes('Success')) {

                            Swal.fire(
                                'Đã xoá!',
                                'Người dùng đã được xoá thành công.',
                                'success'
                            )
                            loadEmployees(currentPage, name, email, is_active);
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message || 'Xóa người dùng thất bại!',
                                'error'
                            )
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Lỗi khi xóa người dùng:", error);
                        Swal.fire(
                            'Lỗi hệ thống!',
                            'Không thể xóa người dùng do lỗi máy chủ.',
                            'error'
                        )
                    }
                });
            }
        });
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>