<!-- Modal Add Customer -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addEmployeeForm">
                <div class="text-danger text-center" id="addEmployeeMessage" style="display:none;"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Thêm Khách Hàng Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employee-name" class="form-label">Họ tên nhân viên</label>
                        <input type="text" class="form-control" id="employee-name" name="employee_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="employee-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee-phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="tel_num" name="telNum" required>
                    </div>
                   
                    <div class="mb-3">
                        <label for="employee-status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="employee-status" name="isActive" required>
                            <option value="Đang Hoạt Động">Đang hoạt động</option>
                            <option value="Tạm Khoá">Tạm khoá</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="saveEmployeeBtn">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>