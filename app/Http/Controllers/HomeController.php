<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showHome(){
        return view('home');
    }
    public function index(){
        $services = Services::select('service_id', 'service_name', 'description', 'price','image_url','duration_minute')->get();
        return view('home', compact('services'));
    }
}
