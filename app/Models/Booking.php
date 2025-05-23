<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $primaryKey='booking_id';
    protected $fillable= [
        'booking_id',
        'booking_date',
        'customer_id',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'created_at',
        'updated_at'
    ];


}
