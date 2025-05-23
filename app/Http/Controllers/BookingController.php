<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Models\WorkingHour;
use App\Service\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class BookingController extends Controller
{
    protected $bookingService;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function showBooking(){
        $services = Services::all();
        return view('Booking.booking',compact('services'));
    }
    public function createBooking(Request $request){
        $data = $request->validate([
            'booking_date'=>'required',
            'service_id'=>'required',
            'start_time'=>'required',
        ]);
        $data['customer_id']=session('customer_id');
        try{
            $booking =$this->bookingService->createBooking($data);
            return response()->json([
                'status'=>'success',
                'message'=>'Đặt lịch thành công',
                'data'=>$booking
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    
}
