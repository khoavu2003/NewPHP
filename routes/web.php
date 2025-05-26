<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WorkingHourController;
use App\Http\Controllers\HomeController;
use App\Models\Booking;

Route::get('/',[HomeController::class,'index']);
Route::get('/login',[LoginController::class,'showLoginForm']);
Route::get('/booking',[BookingController::class,'showBooking']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/createBooking',[BookingController::class,'createBooking']);

Route::get('/getWorkingHour',[WorkingHourController::class,'getWorkingHours']);
Route::get('/loadCustomerBooking',[CustomerController::class,'customerBooking']);
Route::get('/my-booking',[CustomerController::class,'showCustomerBooking']);
