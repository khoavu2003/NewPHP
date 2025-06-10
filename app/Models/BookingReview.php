<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingReview extends Model
{
    use HasFactory;
    

    protected $table = 'booking_reviews'; 

    protected $primaryKey = 'rating_id'; 

    public $timestamps = true; 

    protected $fillable = [
        'booking_id',
        'customer_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];
}
