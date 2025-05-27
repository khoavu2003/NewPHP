<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
      use HasFactory;
    

    protected $table = 'admin'; 

    protected $primaryKey = 'id'; 

    public $timestamps = true; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'verify_email',
        'is_active',
        'is_delete',
        'group_role',
        'last_login_at',
        'last_login_ip',
        'created_at',
        'updated_at',
    ];
}
