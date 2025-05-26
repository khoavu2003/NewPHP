<?php

namespace App\Http\Controllers;

use App\Service\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;
    public function __construct(CustomerService $customerService){
        $this->customerService=$customerService;
    }
    public function showCustomerBooking(){
        return view('Booking.customer');
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
    public function cancelBooking(Request $request){
        try{
            $data = $request->validate([
                'booking_id'=>'required|integer|exists:bookings,booking_id',
            ]);
            $result = $this->customerService->cancelBooking($data['booking_id']);
            return response()->json($result,200);
        }catch(\Exception $e){
            return response()->json([
                    'success'=>'false',
                    'message'=>$e->getMessage()
            ],422);
        }
    }
}
