<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\AddEmployeeRequest;
use App\Http\Requests\Employee\SearchEmployeeRequest;
use App\Models\Admin;
use App\Service\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    protected $employeeService;
    public function __construct(EmployeeService $employeeService){
        $this->employeeService=$employeeService;
    }
    public function showEmployeeManager(){
        return view('employee.manager');
    }
    public function searchEmployee(SearchEmployeeRequest $request){
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
            'name'=>$data['employee_name'],
            'email'=>$data['email'],
            'password'=>Hash::make('123456'),
            'verify_email'=>1,
            'group_role'=>'user',
            'is_delete'=>0
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

}
