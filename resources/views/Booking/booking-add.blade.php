<!-- Modal Add Customer -->
<style>
    .time-slot {
        width: 80px;
        height: 60px;
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f8f9fa;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .time-slot:hover {
        background-color: #e9ecef;
    }

    .time-slot.selected {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .time-slot.disabled {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
        border-color: #ced4da;
    }

    .time-slots-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }
</style>
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-labelledby="addBookingModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addBookingForm">
                <div class="text-danger text-center" id="addBookingMessage" style="display:none;"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Thêm lịch mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer-name" class="form-label">Họ tên khách hàng</label>
                        <input type="text" class="form-control" id="customer-name" name="customerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer-email" class="form-label">Email khách hàng</label>
                        <input type="email" class="form-control" id="customer-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Dịch vụ</label>
                        <select name="service_id" id="service_id" class="form-select" required>
                            <option value="">Chọn dịch vụ</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add-group" class="form-label">Trạng thái</label>
                        <select class="form-select" id="add-status" name="add-status" required>
                            <option value="cancelled">Đã huỷ</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="pending">Chờ xác nhận</option>
                            <option value="completed">Hoàn thành</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="customer-phone" class="form-label">Ngày đặt</label>
                        <input type="date" name="booking_date" id="booking_date" class="form-control" style="max-width: 250px;"
                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::today()->addDays(14)->format('Y-m-d') }}">
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label">Giờ bắt đầu</label>
                        <input type="hidden" name="booking_time" id="booking_time" required>
                        <div class="time-slots-container" id="timeSlotsContainer">
                            <option value="">Vui lòng chọn ngày</option>
                            <!-- Time slot buttons will be dynamically inserted here -->

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="saveBookingBtn">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>