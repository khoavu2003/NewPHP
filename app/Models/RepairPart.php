<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairPart extends Model
{
    use HasFactory;
    

    protected $table = 'repair_parts'; 

    protected $primaryKey = 'repair_parts_id'; 

    public $timestamps = true; 

    protected $fillable = [
        'booking_id',
        'employee_id',
        'part_name',
        'quantity',
        'part_cost',
        'created_at',
        'updated_at',
    ];
}
