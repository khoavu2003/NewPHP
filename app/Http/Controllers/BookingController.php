<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\CreateBookingRequest;
use App\Http\Requests\Booking\SearchBookingRequest;
use App\Mail\BookingSuccessMail;
use App\Models\Booking;
use App\Models\Services;
use App\Models\WorkingHour;
use App\Service\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
    public function createBooking(CreateBookingRequest $request)
    {
        $data = $request->validated();
        if (session('customer_id') == null) {
            $data['customer_id'] = null;
        } else {
            $data['customer_id'] = session('customer_id');
            $data['guest_name'] = session('customer_name');
            $data['guest_email'] = session('customer_email');
        }

        try {
            $booking = $this->bookingService->createBooking($data);
            $service = Services::where('service_id', $booking->service_id)
                ->select('service_name')
                ->firstOrFail();

            $serviceName = $service->service_name;
            Mail::to($booking->guest_email)->send(new BookingSuccessMail([
                'guest_name'   => $booking->guest_name,
                'booking_date' => $booking->booking_date,
                'start_time'   => $booking->start_time,
                'end_time'     => $booking->end_time,
                'service_name' => $serviceName
            ]));
            return response()->json([
                'status' => 'success',
                'message' => 'Đặt lịch thành công',
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
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
            Log::info('Dữ liệu booking', ['bookings' => $bookings->toArray()]);
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
    public function searchBooking(SearchBookingRequest $request)
    {
        $bookings = $this->bookingService->searchBooking($request->validated());
        $services = Services::select('service_id', 'service_name', 'duration_minute')->get();
        return response()->json([
            'status' => 'success',
            'bookingList' => $bookings->items(),
            'services' => $services,
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ]
        ]);
    }
    public function cancelBooking($id)
    {
        $data = ['booking_id' => $id];
        $validator = Validator::make($data, [
            'booking_id' => ['required', 'integer'],
        ], [
            'booking_id.required' => 'Id không được để trống',
            'booking_id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $booking = Booking::where('is_delete', 0)->find($id);

        if (!$booking) {
            return response()->json(['status' => 'Error', 'message' => 'Không tìm thấy lịch'], 404);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Huỷ lịch thành công',
            'booking_status' => $booking->status
        ]);
    }
    public function confirmBooking($id)
    {
        $data = ['booking_id' => $id];
        $validator = Validator::make($data, [
            'booking_id' => ['required', 'integer'],
        ], [
            'booking_id.required' => 'Id không được để trống',
            'booking_id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $booking = Booking::where('is_delete', 0)->find($id);

        if (!$booking) {
            return response()->json(['status' => 'Error', 'message' => 'Không tìm thấy lịch'], 404);
        }

        $booking->status = 'confirmed';
        $booking->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Xác nhận lịch thành công',
            'booking_status' => $booking->status
        ]);
    }
    public function getBookingById($id)
    {

        $data = ['booking_id' => $id];
        $validator = Validator::make($data, [
            'booking_id' => ['required', 'integer'],
        ], [
            'booking_id.required' => 'Id không được để trống',
            'booking_id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $book = $this->bookingService->findBookById($id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lịch không tồn tại.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'status' => 'success',
            'book' => $book
        ]);
    }
}
