<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\SearchBookingRequest;
use App\Models\Booking;
use App\Models\Services;
use App\Models\WorkingHour;
use App\Service\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;


class BookingController extends Controller
{
    protected $bookingService;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function showBooking(Request $request)
    {
        $services = Services::select('service_id', 'service_name', 'duration_minute')->get();
        $selectedServiceId = $request->query('service_id');
        return view('Booking.booking', compact('services', 'selectedServiceId'));
    }
    public function createBooking(Request $request)
    {
        $data = $request->validate([
            'booking_date' => 'required',
            'service_id' => 'required',
            'start_time' => 'required',
            'guest_name' => ['nullable', 'string', 'max:255',],
            'guest_email' => ['nullable', 'email', 'max:255',],
            'guest_phone' => ['nullable', 'string', 'max:15',],
        ]);
        if (session('customer_id') == null) {
            $data['customer_id'] = null;
        } else {
            $data['customer_id'] = session('customer_id');
        }

        try {
            $booking = $this->bookingService->createBooking($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Đặt lịch thành công',
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function showBookingManager(Request $request)
    {
        return view('Booking.booking-manager');
    }
    public function getAllBooking()
    {
        try {
            $bookings = $this->bookingService->getBooking();
            Log::info('Dữ liệu booking',['bookings' => $bookings->toArray()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Tải dữ liệu dịch vụ thành công',
                'bookings' => $bookings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'không thể tải dữ liệu dịch vụ',
            ]);
        }
    }
    public function searchBooking(SearchBookingRequest $request){
        $bookings = $this->bookingService->searchBooking($request->validated());
        $services = Services::select('service_id', 'service_name', 'duration_minute')->get();
         return response()->json([
             'status' => 'success',
             'bookingList' => $bookings->items(),
             'services'=>$services,
             'pagination' => [
                 'current_page' => $bookings->currentPage(),
                 'last_page' => $bookings->lastPage(),
                 'per_page' => $bookings->perPage(),
                 'total' => $bookings->total(),
             ]
         ]);
    }
    public function blockUser($id)
    {
        $data = ['booking_id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $user = Booking::where('is_delete', 0)->find($id);

        if (!$user) {
            return response()->json(['status' => 'Error', 'message' => 'Không tìm thấy người dùng'], 404);
        }

        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Trạng thái người dùng đã được cập nhật.',
            'is_active' => $user->is_active
        ]);
    }

}
