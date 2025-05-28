<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\Employees;
use App\Models\Services;
use App\Models\WorkingHour;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    private function getStartTime(array $data)
    {
        return Carbon::parse($data['booking_date'] . '' . $data['start_time']);
    }
    private function getServiceById(int $serviceID)
    {
        return Services::where('service_id', $serviceID)->firstOrFail();
    }
    private function getEndTime(Carbon $start, int $duration)
    {

        return $start->copy()->addMinute($duration);
    }
    public function createBooking(array $data)
    {
        $start = $this->getStartTime($data);
        $service = $this->getServiceById($data['service_id']);
        $end = $this->getEndTime($start, $service->duration_minute);
        $this->validateWorkingHour($start, $end, $data['booking_date']);
        $slots = $this->getTimeSlots($start->copy(), $end->copy());
        $this->validateNotInPast($data['booking_date'], $start);
        $this->validateTimeSlotsAvailability($data['booking_date'], $start, $end);

        $booking = $this->storeBooking($data, $start, $end);

        $this->assignEmployee($booking, $data['booking_date'], $start, $end);

        return $booking;
    }
    private function validateNotInPast(string $date, Carbon $startime)
    {
        $today = Carbon::today();
        $bookingDate = Carbon::parse($date);
        $maxDate = $today->copy()->addDays(14);
        if ($bookingDate < $today) {
            throw new \Exception('Vui lòng không để ngày trong quá khứ');
        }
        if ($bookingDate->isToday() && $startime < Carbon::now()) {
            throw new \Exception('Không chọn thời gian đã qua trong ngày');
        }
        if ($bookingDate > $maxDate) {
            throw new \Exception('Không thể đặt lịch quá 2 tuần');
        }
    }
    private function getTimeSlots(Carbon $start_time, Carbon $end_time)
    {
        $slots = [];
        while ($start_time < $end_time) {
            $slots[] = $start_time->format('H:i');
            $start_time->addMinute(20);
        }
        return $slots;
    }
    private function validateWorkingHour(Carbon $start_time, Carbon $end_time, string $date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek();
        $workingHour = WorkingHour::where('day_of_week', $dayOfWeek)->first();

        if ($workingHour->is_closed == true) {
            throw new \Exception('Chủ nhật cửa hàng không làm việc');
        }
        $workingStart = Carbon::parse($date . ' ' . $workingHour->start_time);
        $workingEnd = Carbon::parse($date . ' ' . $workingHour->end_time);
        $breakStart = Carbon::parse($date . '' . $workingHour->break_time_start);
        $breakEnd = Carbon::parse($date . '' . $workingHour->break_time_end);
        if ($start_time < $workingStart) {
            throw new \Exception('Lịch hẹn nằm ngoài giờ làm việc vui lòng chọn khung giờ khác hoặc ngày khác');
        }
        if ($end_time > $workingEnd) {
            throw new \Exception('Lịch hẹn nằm ngoài giờ làm việc vui lòng chọn khung giờ khác hoặc ngày khác');
        }
        if ($breakStart && $breakEnd) {
            if (($start_time >= $breakStart && $start_time < $breakEnd) ||
                ($end_time > $breakStart && $end_time <= $breakEnd) ||
                ($start_time < $breakStart && $end_time > $breakEnd)
            ) {
                throw new \Exception('Đây là thời gian nghĩ trưa vui lòng chọn khung giờ khác');
            }
        }
    }
    private function validateTimeSlotsAvailability(string $date, Carbon $start, Carbon $end)
    {
        $maxBookings = 2;
        $slots = $this->getTimeSlots($start->copy(), $end->copy()->addMinutes(20)); // Include end slot
        foreach ($slots as $slotStart) {
            $slotBegin = Carbon::parse($date . ' ' . $slotStart);
            $slotEnd = $slotBegin->copy()->addMinutes(20);

            $bookingCount = DB::table('bookings')
                ->where('booking_date', $date)
                ->where('start_time', '<', $slotEnd->format('H:i'))
                ->where('end_time', '>', $slotBegin->format('H:i'))
                ->where('status', '!=', 'cancelled')
                ->distinct('booking_id')
                ->count('booking_id');

            if ($bookingCount >= $maxBookings) {
                throw new \Exception("Khung giờ từ {$slotBegin->format('H:i')} đến {$slotEnd->format('H:i')} đã đầy. Vui lòng chọn khung giờ khác.");
            }

            $availableEmployees = $this->getAvailableEmployees($date, $slotBegin, $slotEnd);
            if (count($availableEmployees) === 0) {
                throw new \Exception("Không có nhân viên nào rảnh trong khung giờ từ {$slotBegin->format('H:i')} đến {$slotEnd->format('H:i')}.");
            }
        }
    }
    public function storeBooking(array $data, Carbon $start_time, Carbon $end_time)
    {
        return Booking::create([
            'booking_date' => $data['booking_date'],
            'customer_id' => $data['customer_id'] ?? null,
            'service_id' => $data['service_id'],
            'start_time' => $start_time->format('H:i'),
            'end_time' => $end_time->format('H:i'),
            'guest_name' => $data['guest_name'] ?? null,
            'guest_email' => $data['guest_email'] ?? null,
            'guest_phone' => $data['guest_phone'] ?? null,
            'status' => 'pending'
        ]);
    }

    public function assignEmployee(Booking $booking, string $date, Carbon $start, Carbon $end)
    {
        $availableEmployees = $this->getAvailableEmployees($date, $start, $end);
        $assigned = array_slice($availableEmployees, 0, 1);

        foreach ($assigned as $employee) {
            DB::table('booking_employee')->insert([
                'booking_id' => $booking->booking_id,
                'employee_id' => $employee->employee_id,
            ]);
        }
    }
    public function getAvailableEmployees(string $date, Carbon $start, Carbon $end)
    {
        $AllEmployee = Employees::all();
        return $AllEmployee->filter(function ($employee) use ($date, $start, $end) {
            $count = DB::table('booking_employee')
                ->join('bookings', 'bookings.booking_id', '=', 'booking_employee.booking_id')
                ->join('employees','employees.employee_id','=','booking_employee.employee_id')
                ->where('booking_employee.employee_id', $employee->employee_id)
                ->where('employees.is_active',1)
                ->where('employees.is_delete',0)
                ->where('bookings.booking_date', $date)
                ->where('bookings.start_time', '<', $end->format('H:i'))
                ->where('bookings.end_time', '>', $start->format('H:i'))
                ->where('bookings.status', '!=', 'cancelled')
                ->count();
            return $count === 0;
        })->values()->all();
    }
    public function getBooking()
    {
        Log::info('Fetching all bookings');

        try {
            $bookings = Booking::leftJoin('customers', 'bookings.customer_id', '=', 'customers.customer_id')
                ->join('services', 'bookings.service_id', '=', 'services.service_id')
                ->select(
                    'bookings.booking_id as booking_id',
                    DB::raw('COALESCE(customers.customer_name, bookings.guest_name) as customer_name'),
                    DB::raw('COALESCE(customers.email, bookings.guest_email) as customer_email'),
                    'bookings.booking_date',
                    'bookings.start_time',
                    'bookings.end_time',
                    'bookings.status'
                )
                ->orderBy('bookings.booking_date', 'desc')
                ->orderBy('bookings.start_time', 'desc')
                ->paginate(10);

            Log::info('Retrieved bookings', ['bookings' => $bookings->toArray()]);

            return $bookings;
        } catch (\Exception $e) {
            Log::error('Failed to fetch bookings', ['error' => $e->getMessage()]);
            throw $e; // Re-throw to be caught by controller
        }
    }
    public function searchBooking(array $filters)
    {
        $query = Booking::leftJoin('customers', 'bookings.customer_id', '=', 'customers.customer_id')
            ->join('services', 'bookings.service_id', '=', 'services.service_id')
            ->where('bookings.is_delete', 0)
            ->select(
                'bookings.booking_id as booking_id',
                DB::raw('COALESCE(customers.customer_name, bookings.guest_name) as customer_name'),
                DB::raw('COALESCE(customers.email, bookings.guest_email) as customer_email'),
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.status',
                'services.service_name'
            );

        if (!empty($filters['customer_name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('customers.customer_name', 'like', '%' . $filters['customer_name'] . '%')
                    ->orWhere('bookings.guest_name', 'like', '%' . $filters['customer_name'] . '%');
            });
        }

        if (!empty($filters['customer_email'])) {
           $query->where(function($q) use ($filters) {
            $q->where('customers.email', 'like', '%' . $filters['customer_email'] . '%')
              ->orWhere('bookings.guest_email', 'like', '%' . $filters['customer_email'] . '%');
        });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['booking_date'])) {
            $query->where('booking_date', $filters['booking_date']);
        }
        return $query->orderBy('booking_date', 'desc')->orderBy('start_time', 'desc')->paginate(10);
    }
    public function findBookById($id)
    {
        $query = Booking::leftJoin('customers', 'bookings.customer_id', '=', 'customers.customer_id')
            ->join('services', 'bookings.service_id', '=', 'services.service_id')
            ->where('bookings.is_delete', 0)
            ->where('bookings.booking_id', $id)
            ->select(
                'bookings.booking_id as booking_id',
                DB::raw('COALESCE(customers.customer_name, bookings.guest_name) as customer_name'),
                DB::raw('COALESCE(customers.email, bookings.guest_email) as customer_email'),
                'bookings.booking_date',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.status',
                'services.service_name',
                'bookings.service_id'
            );
        return $query->firstOrFail();
    }
}
