<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    use HasFactory;
    protected $table = 'working_hours'; 

    protected $primaryKey = 'working_id'; 
    public $timestamps = true; 

    protected $fillable = [
        'working_id',
        'day_of_week',
        'start_time',
        'end_time',
        'break_time_start',
        'break_time_end',
        'is_closed',
        'created_at',
        'updated_at'
        
    ];
}
