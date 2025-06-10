<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceShedule extends Model
{
   use HasFactory;
    

    protected $table = 'maintenance_shedules'; 

    protected $primaryKey = 'maintenance_id'; 

    public $timestamps = true; 

    protected $fillable = [
        'vehicle_id',
        'last_maintenance_date',
        'next_maintenance_date',
        'notified',
        'created_at',
        'updated_at',
    ]; 
}
