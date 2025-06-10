<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $table = 'services';

    protected $primaryKey = 'service_id';
    public $timestamps = true;

    protected $fillable = [
        'service_id',
        'services_name',
        'duration_minutes',
        'price',
        'description',
        'image_url',
        'created_at',
        'updated_at',
    ];
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services', 'service_id', 'booking_id')
            ->withTimestamps();
    }
}
