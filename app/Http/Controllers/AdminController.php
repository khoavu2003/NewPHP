<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showAdmin(){
        return view('Admin.index');
    }
    public function showServicesManager(){
        return view('Admin.services');
    }
}
