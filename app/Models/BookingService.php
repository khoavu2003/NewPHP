<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    use HasFactory;
    

    protected $table = 'booking_services'; 

    protected $primaryKey = 'booking_services_id'; 

    public $timestamps = true; 

    protected $fillable = [
        'service_id',
        'booking_id',
        'created_at',
        'updated_at',
    ];
}
