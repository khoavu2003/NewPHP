<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\Employees;
use App\Models\Services;
use App\Models\WorkingHour;
use Illuminate\Support\Carbon;
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
    private function validateNotInPast(string $date,Carbon $startime){
        $today = Carbon::today();
        $bookingDate = Carbon::parse($date);
        $maxDate = $today->copy()->addDays(14);
        if($bookingDate<$today){
            throw new \Exception('Vui lòng không để ngày trong quá khứ');
        }
        if($bookingDate->isToday()&&$startime<Carbon::now()){
            throw new \Exception('Không chọn thời gian đã qua trong ngày');
        }
        if($bookingDate>$maxDate){
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
        $breakStart=Carbon::parse($date .''.$workingHour->break_time_start);
        $breakEnd=Carbon::parse($date. '' .$workingHour->break_time_end);
        if ($start_time < $workingStart) {
            throw new \Exception('Lịch hẹn nằm ngoài giờ làm việc vui lòng chọn khung giờ khác hoặc ngày khác');
        }
        if ($end_time > $workingEnd) {
            throw new \Exception('Lịch hẹn nằm ngoài giờ làm việc vui lòng chọn khung giờ khác hoặc ngày khác');
        }
        if ($breakStart && $breakEnd) {
            if (($start_time >= $breakStart && $start_time < $breakEnd) ||
                ($end_time > $breakStart && $end_time <= $breakEnd) ||
                ($start_time <$breakStart && $end_time > $breakEnd)
            ) {
                throw new \Exception('Đây là thời gian nghĩ trưa vui lòng chọn khung giờ khác');
            }
        }
    }
    private function validateTimeSlotsAvailability(string $date, Carbon $start, Carbon $end)
    {
        $bookingCount = DB::table('booking_employee')
            ->join('bookings', 'bookings.booking_id', '=', 'booking_employee.booking_id')
            ->where('bookings.booking_date', $date)
            ->where('bookings.start_time', '<', $end->format('H:i'))
            ->where('bookings.end_time', '>', $start->format('H:i'))
            ->count();

        $maxBookings = 2;
        if ($bookingCount >= $maxBookings) {
            throw new \Exception("Khung giờ từ {$start->format('H:i')} đến {$end->format('H:i')} đã đầy. Vui lòng chọn khung giờ khác.");
        }


        $availableEmployees = $this->getAvailableEmployees($date, $start, $end);
        if (count($availableEmployees) === 0) {
            throw new \Exception("Không có nhân viên nào rảnh trong khung giờ từ {$start->format('H:i')} đến {$end->format('H:i')}.");
        }
    }
    public function storeBooking(array $data, Carbon $start_time, Carbon $end_time)
    {
        return Booking::create([
            'booking_date' => $data['booking_date'],
            'customer_id' => $data['customer_id'],
            'service_id' => $data['service_id'],
            'start_time' => $start_time->format('H:i'),
            'end_time' => $end_time->format('H:i')
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
                ->where('employee_id', $employee->employee_id)
                ->where('bookings.booking_date', $date)
                ->where('bookings.start_time', '<', $end->format('H:i'))
                ->where('bookings.end_time', '>', $start->format('H:i'))
                ->count();
            return $count === 0;
        })->values()->all();
    }
}
