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
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Mail;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/booking', [BookingController::class, 'showBooking']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/createBooking', [BookingController::class, 'createBooking']);

Route::get('/getWorkingHour', [WorkingHourController::class, 'getWorkingHours']);
Route::get('/loadCustomerBooking', [CustomerController::class, 'customerBooking']);
Route::get('/my-booking', [CustomerController::class, 'showCustomerBooking']);
Route::Post('/cancelBooking', [CustomerController::class, 'cancelBooking']);
Route::Post('/logout', [LogoutController::class, 'logout']);

Route::get('/test-mail', function () {
    Mail::raw('Đây là nội dung test email gửi từ Laravel!', function ($message) {
        $message->to('vu.khoa.rcvn2012@gmail.com') // Địa chỉ email bạn muốn nhận
                ->subject('Test Email từ Laravel');
    });
    return 'Đã gửi email!';
});

Route::get('/admin', [AdminLoginController::class, 'showLoginForm']);
Route::Post('/adminLogin', [AdminLoginController::class, 'login']);

Route::prefix('admin')->middleware(['auth.admin', 'admin.role'])->group(function () {
    Route::get('/servicesManager', [ServicesController::class, 'showServicesManager']);
    Route::get('/searchServices', [ServicesController::class, 'searchServices']);
    Route::get('/bookingManager', [BookingController::class, 'showBookingManager']);
    Route::get('/getAllBooking', [BookingController::class, 'getAllBooking']);
    Route::get('/searchBooking', [BookingController::class, 'searchBooking']);
    Route::post('/cancelBooking/{id}',[BookingController::class,'cancelBooking']);
    Route::post('/confirmBooking/{id}',[BookingController::class,'confirmBooking']);
    Route::get('/getBookById/{id}',[BookingController::class,'getBookingById']);
    Route::get('/employeesManager',[EmployeeController::class,'showEmployeeManager']);
    Route::get('/searchEmployee',[EmployeeController::class,'searchEmployee']);
    Route::post('/addEmployees',[EmployeeController::class,'addEmployees']);
    Route::get('/getEmployeeById/{id}',[EmployeeController::class,'getEmployeeById']);
});
