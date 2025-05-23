<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees'; 

    protected $primaryKey = 'employee_id'; 
    public $timestamps = true; 

    protected $fillable = [
        'employee_id',
        'employee_name',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
