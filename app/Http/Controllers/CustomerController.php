<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchHistoryRequest;
use App\Service\CustomerService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    protected $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    public function showCustomerBooking()
    {
        return view('Booking.customer');
    }
    public function customerBooking()
    {
        $customerId = Auth::id();

        try {
            $bookings = $this->customerService->getCustomerBooking();
            return response()->json([
                'status' => 'success',
                'message' => 'Tải dữ liệu dịch vụ thành công',
                'customer_id' => $customerId,
                'bookings' => $bookings->map(function ($booking) {
                    return [
                        'booking_id' => $booking->booking_id,
                        'booking_date' => $booking->booking_date,
                        'license_plate' => $booking->{"lisense-plate"},
                        'model' => $booking->{"model"},
                        'technician_note' => $booking->technician_note,
                        'total_cost' => $booking->total_cost,
                        'guest_phone'=>$booking->guest_phone,
                        'services' => $booking->services,
                        'repair_parts' => $booking->repairParts,
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function searchHistory(SearchHistoryRequest $request){
        try {
            $bookings = $this->customerService->searchHistory($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Tải dữ liệu dịch vụ thành công',
                'bookings' => $bookings->map(function ($booking) {
                    return [
                        'booking_id' => $booking->booking_id,
                        'booking_date' => $booking->booking_date,
                        'license_plate' => $booking->{"lisense-plate"},
                        'model' => $booking->{"model"},
                        'technician_note' => $booking->technician_note,
                        'total_cost' => $booking->total_cost,
                        'guest_phone'=>$booking->guest_phone,
                        'services' => $booking->services,
                        'repair_parts' => $booking->repairParts,
                        'reviews'=>$booking->rating
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function cancelBooking(Request $request)
    {

        try {
            $data = $request->validate([
                'booking_id' => 'required|integer|exists:bookings,booking_id',
            ]);
            $result = $this->customerService->cancelBooking($data['booking_id']);
            if (session('customer_id') != $result['customer_id']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền huỷ lịch'
                ]);
            }
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function showHistoryBooking()
    {
        return view('Booking.history');
    }
}
