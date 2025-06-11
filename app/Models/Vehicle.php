<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    

    protected $table = 'vehicles'; 

   protected $primaryKey = 'vehicle_id';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true; 

    protected $fillable = [
        
        'lisense-plate',
        'model',
        'created_at',
        'updated_at',
    ];
    

}