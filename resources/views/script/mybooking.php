<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });
    function formatDate(dateStr){
        const date = new Date(dateStr);
        return date.toLocaleDateString('vi-VN',{
            day:'2-digit',
            month:'2-digit',
            year:"numeric"
        });
    }
    function getStatusBadge(status){
        switch(status){
            case 'pending':
                return '<span class="badge bg-warning text-dark">Chờ xác nhận </span>';
            case 'confirmed':
                return '<span class="badge bg-success">Đã xác nhận</span>';
            case 'completed':
                return '<span class="badge bg-primary">Hoàn thành</span>';
            case 'cancelled':
                return '<span class="badge bg-danger"> Đã huỷ</span>';
            default:
                return '<span class="badge bg-secondary text-dark">Không xác định</span>';
        }
    }
    $.ajax({
        url:'/loadCustomerBooking',
        type:'GET',
        success:function(response){
            console.log(response)
            const $bookingList=$('#bookingList');
            $bookingList.empty();
            if(response.bookings.length===0){
                $bookingList.html('<p class="text-muted text-center">Bạn chưa có lịch hẹn nao</p>');
                return ;
            }
             const $table = $(`
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giờ bắt đầu</th>
                                <th>Giờ kết thúc</th>
                                <th>Dịch vụ</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            `);
            response.bookings.forEach(booking=>{
                const $row = $(`
                    <tr>
                        <td>${formatDate(booking.booking_date)}</td>
                        <td>${booking.start_time}</td>
                        <td>${booking.end_time}</td>
                        <td>${booking.service_name}</td>
                        <td>${getStatusBadge(booking.status)}</td>
                    </tr>
                `);
                $table.find('tbody').append($row);
            });
            $bookingList.append($table);
        },
        error:function(xhr){
            const $bookingList = $('#bookingList');
            $bookingList.empty();
            const message = xhr.responseJSON?.message || 'Đã xảy ra lỗi khi tải lịch hẹn.';
            $bookingList.html(`<div class="alert alert-danger text-center">${message}</div>`);
        }
    })

</script>