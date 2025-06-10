<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\RepairPart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService
{

    public function getCustomerBooking()
    {
        $bookings = Booking::with([
            'services' => function ($query) {
                $query->select('services.service_id', 'service_name', 'price');
            },
            'repairParts' => function ($query) {
                $query->select('booking_id', 'part_name', 'quantity', 'part_cost');
            },
        ])


            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.vehicle_id')
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->select(
                'bookings.booking_id',
                'bookings.booking_date',
                'vehicles.lisense-plate',
                'bookings.technician_note',
                'bookings.total_cost',
                'vehicles.model',
                'bookings.guest_phone'
            )
            ->paginate(10);

        Log::info('Retrieved bookings', ['bookings' => $bookings->toArray()]);

        return $bookings;
    }
    public function cancelBooking(int $id)
    {
        return DB::transaction(function () use ($id) {
            $booking = Booking::where('booking_id', $id)
                ->where('customer_id', Auth::id())
                ->join('services', 'bookings.service_id', '=', 'services.service_id')
                ->firstOrFail();

            if ($booking->status === 'cancelled') {
                throw new \Exception('Lịch hẹn đã bị huỷ trước đó');
            }
            $bookingDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
            if ($bookingDateTime < Carbon::now()) {
                throw new \Exception('Không thể huỷ lịch hẹn trong quá khứ');
            }
            if ($booking->status != 'pending') {
                throw new \Exception('Lịch hẹn đã được xác nhận liên hệ tới admin để huỷ');
            }
            $booking->status = 'cancelled';
            $booking->save();
            return [
                'success' => true,
                'message' => 'Huỷ lịch hẹn thành công!',
                'customer_id' => Auth::id()
            ];
        });
    }
    public function isEmailExists(string $email): bool
    {
        return Customer::where('email', $email)
            ->exists();
    }
    public function create($attributes = [])
    {
        return Customer::create($attributes);
    }
    public function calculateTotalPrice($id)
    {
        $repair = RepairPart::where('booking_id', $id);

        $service = Booking::where('booking_id', $id)
            ->join('services', 'bookings.service_id', '=', 'services.service_id')
            ->select(
                'bookings.booking_id',
                'services.service_name',
                'services.price'
            );
        $totalPrice = $repair->part_cost * $repair->quantity + $service->price;
        return $totalPrice;
    }
    public function searchHistory(array $filters)
    {

        $bookings = Booking::with([
            'services' => function ($query) {
                $query->select('services.service_id', 'service_name', 'price');
            },
            'repairParts' => function ($query) {
                $query->select('booking_id', 'part_name', 'quantity', 'part_cost');
            },
            'rating' => function ($query) {
            $query->select('booking_id', 'rating_id', 'rating', 'comment');
        }
        ])


            ->join('vehicles', 'bookings.vehicle_id', '=', 'vehicles.vehicle_id')
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->select(
                'bookings.booking_id',
                'bookings.vehicle_id',
                'bookings.booking_date',
                'vehicles.lisense-plate',
                'bookings.technician_note',
                'bookings.total_cost',
                'vehicles.model',
                'bookings.guest_phone',
               
            );

        $bookings->where(function ($q) use ($filters) {
            $q->where('guest_phone', 'like', '%' . $filters['search'] . '%')
                ->orWhere('lisense-plate', 'like', '%' . $filters['search'] . '%');
        });


        return $bookings->orderBy('booking_date', 'desc')->orderBy('start_time', 'desc')->paginate(10);
    }
}
