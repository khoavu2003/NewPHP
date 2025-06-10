<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $primaryKey = 'booking_id';
    protected $fillable = [
        'booking_id',
        'booking_date',
        'customer_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'vehicle_id',
        'technician_note',
        'total_cost',
        'is_delete',
        'created_at',
        'updated_at'
    ];
    public function services()
    {
        return $this->belongsToMany(Services::class, 'booking_services', 'booking_id', 'service_id')
            ->withTimestamps();
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function rating(){
        return $this->hasMany(BookingReview::class,'booking_id');
    }
    public function repairParts()
    {
        return $this->hasMany(RepairPart::class, 'booking_id');
    }
}
