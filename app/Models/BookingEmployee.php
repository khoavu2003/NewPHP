<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingEmployee extends Model
{
   use HasFactory;
    protected $table = 'booking_employee'; 

    protected $primaryKey = 'booking_employee_id'; 
    public $timestamps = true; 

    protected $fillable = [
        'booking_employee_id',
        'booking_id',
        'employee_id'
    ];
}
