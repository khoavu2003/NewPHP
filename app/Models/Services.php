<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $table = 'services'; 

    protected $primaryKey = 'services_id'; 
    public $timestamps = true; 

    protected $fillable = [
        'services_id',
        'services_name',
        'duration_minutes',
        'created_at',
        'updated_at',
    ];
}
