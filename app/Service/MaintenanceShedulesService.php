<?php

namespace App\Service;

use App\Models\MaintenanceShedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MaintenanceNotification;
use App\Models\Booking;

class MaintenanceShedulesService
{
    public function sendMaintenanceNotifications()
    {
        $schedules = MaintenanceShedule::where('notified', false)
            ->whereBetween('next_maintenance_date', [
                Carbon::today(),           
                Carbon::today()->addDays(7) 
            ])
            ->with(['vehicle'])
            ->get();

        foreach ($schedules as $schedule) {
            $vehicle = $schedule->vehicle;
            $latestBooking = Booking::where('vehicle_id', $vehicle->vehicle_id)
                ->where('is_delete', false)
                ->orderBy('booking_date', 'desc')
                ->first();

            if (!$latestBooking || !$latestBooking->guest_email) {
                Log::warning("Không thể gửi thông báo cho lịch bảo trì ID {$schedule->maintenance_id}: Khách hàng hoặc email không tồn tại. {$schedule->vehicle}");
                continue;
            }

            try {
                Mail::to($latestBooking->guest_email)->send(new MaintenanceNotification($schedule, $vehicle));
                $plate=$vehicle->{"lisense-plate"};
                $schedule->update(['notified' => true]);
                Log::info("Đã gửi thông báo bảo trì cho khách hàng {$latestBooking->guest_email}, xe {$plate}");
            } catch (\Exception $e) {
                Log::error("Lỗi khi gửi thông báo bảo trì cho {$latestBooking->guest_email}: {$e->getMessage()}");
            }
        }

        return count($schedules) . ' thông báo bảo trì đã được xử lý.';
    }
}
