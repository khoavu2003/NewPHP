<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\AddEmployeeRequest;
use App\Http\Requests\Employee\SearchEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Admin;
use App\Models\Employees;
use App\Service\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    protected $employeeService;
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }
    public function showEmployeeManager()
    {
        return view('employee.manager');
    }
    public function searchEmployee(SearchEmployeeRequest $request)
    {
        $employee = $this->employeeService->searchEmployee($request->validated());
        return response()->json([
            'status' => 'success',
            'employeeList' => $employee->items(),
            'pagination' => [
                'current_page' => $employee->currentPage(),
                'last_page' => $employee->lastPage(),
                'per_page' => $employee->perPage(),
                'total' => $employee->total(),
            ]
        ]);
    }
    public function addEmployees(AddEmployeeRequest $request)
    {

        $data = $request->validated();
        Admin::create([
            'name' => $data['employee_name'],
            'email' => $data['email'],
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'group_role' => 'user',
            'is_delete' => 0
        ]);
        $createEmployee = [
            'employee_name' => $data['employee_name'],
            'email' => $data['email'],
            'tel_num' => $data['tel_num'],
            'is_active' => $data['is_active'],
            'is_delete' => 0,
        ];
        $employee = $this->employeeService->createEmployee($createEmployee);

        return response()->json([
            'status' => 'success',
            'message' => 'Nhân viên đã được thêm thành công và tạo tài khoản cho nhân viên thành công.',
            'employee' => $employee
        ]);
    }
    public function getEmployeeById($id)
    {
        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $employee = $this->employeeService->find($id);

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'status' => 'success',
            'employee' => $employee
        ]);
    }
    public function updateEmployee(UpdateEmployeeRequest $request,$id){
        
        $data = $request->validated();
        $employee = $this->employeeService->find($id);
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nhân viên không tồn tại.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }
        $updateData = [
            'name' => $data['employee_name'],
            'email' => $data['email'],
            'tel_num' => $data['tel_num'],
            'is_active' => $data['is_active']
        ];
        $updateUser = $this->employeeService->update($id, $updateData);
        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng đã được cập nhật thành công.',
            'user' => $updateUser,
        ]);
    }
    public function updateStatusEmployee($id){
        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
      

       $employee = $this->employeeService->updateStatus($id);
        return response()->json([
            'status' => 'Success',
            'message' => 'Trạng thái nhân viên đã được cập nhật.',
            'is_active' => $employee->is_active
        ]);
    }
    public function deleteEmployee($id){
        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $employee = $this->employeeService->delete($id);

        return response()->json([
            'status' => 'Success',
            'message' => 'Xoá nhân viên thành công.',
            'is_active' => $employee->is_delete
        ]);
    }
}
