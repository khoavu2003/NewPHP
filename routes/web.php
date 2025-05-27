<?php

use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WorkingHourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Models\Booking;

Route::get('/',[HomeController::class,'index']);
Route::get('/login',[LoginController::class,'showLoginForm']);
Route::get('/booking',[BookingController::class,'showBooking']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/createBooking',[BookingController::class,'createBooking']);

Route::get('/getWorkingHour',[WorkingHourController::class,'getWorkingHours']);
Route::get('/loadCustomerBooking',[CustomerController::class,'customerBooking']);
Route::get('/my-booking',[CustomerController::class,'showCustomerBooking']);
Route::Post('/cancelBooking',[CustomerController::class,'cancelBooking']);
Route::Post('/logout',[LogoutController::class,'logout']);

Route::get('/admin',[AdminLoginController::class,'showLoginForm']);
Route::Post('/adminLogin',[AdminLoginController::class,'login']);
Route::get('/admin/servicesManager',[ServicesController::class,'showServicesManager']);

Route::get('/admin/searchServices',[ServicesController::class,'searchServices']);