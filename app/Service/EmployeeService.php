<?php

namespace App\Service;
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

}