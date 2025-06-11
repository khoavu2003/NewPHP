<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
     use HasFactory;
    protected $table = 'otps'; 

    protected $primaryKey = 'otp_id'; 
    public $timestamps = true; 

    protected $fillable = [
        'tel_num',
        'otp_code',
        'expires_at',
        'is_used',
        'created_at',
        'updated_at',
    ];
}
