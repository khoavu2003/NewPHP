<?php
namespace App\Service;

use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService{

    public function getCustomerBooking(){
       $customerId = Auth::id();
        if (!$customerId) {
            throw new \Exception('Vui lòng đăng nhập xem lịch đã đặt');
        }

        Log::info('Fetching bookings for customer', ['customer_id' => $customerId]);

        $bookings = Booking::where('customer_id', $customerId)
            ->join('services', 'bookings.service_id', '=', 'services.service_id')
            ->select(
                'bookings.booking_id',
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.status',
                'services.service_name'
            )
            ->orderBy('bookings.booking_date', 'desc')
            ->orderBy('bookings.start_time', 'desc')
            ->paginate(10);

        Log::info('Retrieved bookings', ['bookings' => $bookings->toArray()]);

        return $bookings;
    }
    public function cancelBooking(int $id){
        return DB::transaction(function() use ($id){
            $booking = Booking::where('booking_id',$id)
            ->where('customer_id',Auth::id())
            ->with('services')
            ->firstOrFail();

            if($booking->status==='cancelled'){
                throw new \Exception('Lịch hẹn đã bị huỷ trước đó');
            }
            $bookingDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
            if($bookingDateTime<Carbon::now()){
                throw new \Exception('Không thể huỷ lịch hẹn trong quá khứ');
            }
            $booking->status='cancelled';
            $booking->save();
            return [
                'success'=>true,
                'message'=>'Huỷ lịch hẹn thành công!'
            ];
        });
    }
}
