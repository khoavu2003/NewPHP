<?php
namespace App\Service;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
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
            ->get();

        Log::info('Retrieved bookings', ['bookings' => $bookings->toArray()]);

        return $bookings;
    }
}
