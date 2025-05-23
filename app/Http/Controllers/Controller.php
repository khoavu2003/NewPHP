<?php

namespace App\Http\Controllers;

use App\Service\CustomerService;

abstract class Controller
{
    protected $customerService;
    public function __construct(CustomerService $customerService){
        $this->customerService=$customerService;
    }
    public function customerBooking(){
       try{
        $bookings = $this->customerService->getCustomerBooking();
        return response()->json([
            'status'=>'success',
            'message'=>'Tải dữ liệu dịch vụ thành công',
            'bookings'=>$bookings
        ],200);
       }catch(\Exception $e){
            return response()->json([
                'status'=>'error',
                'message'=>'không thể tải dữ liệu dịch vụ',
            ]);
       }
    }
}
