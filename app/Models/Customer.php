<?php

namespace App\Models;

use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;
    protected $table = 'customers'; 

    protected $primaryKey = 'customer_id'; 
    public $timestamps = true; 

    protected $fillable = [
        'customer_id',
        'customer_name',
        'email',
        'tel_num',
        'password',
        'created_at',
        'updated_at',
    ];
   
}
