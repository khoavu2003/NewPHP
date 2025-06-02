<?php

namespace App\Service;

use App\Models\Admin;
use App\Models\Employees;
class EmployeeService{
     public function searchEmployee(array $filters)
    {
        $query = Employees::where('is_delete', 0);

        if (!empty($filters['employee_name'])) {
            $query->where('employee_name', 'like', '%' . $filters['employee_name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
     public function createEmployee(array $data)
    {
       
        return Employees::create([
            'employee_name' => $data['employee_name'],
            'email' => $data['email'],
            'tel_num'=>$data['tel_num'],
            'is_active' => $data['is_active'] ?? true,
            'is_delete' => 0
        ]);
    }
    public function find($id){
        $employee = Employees::where('is_delete',0)->where('employee_id',$id)->firstOrFail();
        return $employee;
    }
    public function update($id, $attributes = []){
        $employee =$this->find($id);
        if($employee){
            $employee->update($attributes);
            return $employee;
        }
    }
    public function updateStatus($id){
        $employee=$this->find($id);
        $employee->is_active = $employee->is_active ? 0 : 1;
        $employee->save();
        return $employee;
    }
    public function delete($id){
        $employee=$this->find($id);
        $employee->is_delete=1;
        $employee->save();
        return $employee;
    }
}