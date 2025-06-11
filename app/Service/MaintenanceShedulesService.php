<?php

namespace App\Service;

use App\Models\MaintenanceShedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\MaintenanceNotification;
class MaintenanceShedulesService
{

    public function sendMaintenanceNotifications()
    {
        $schedules = MaintenanceShedule::where('notified', false)
            ->whereBetween('next_maintenance_date', [
                Carbon::today()->addMonths(3),
                Carbon::today()->addMonths(6)
            ])
            ->with(['vehicle.customer'])
            ->get();

        foreach ($schedules as $schedule) {
            $vehicle = $schedule->vehicle;
            $customer = $vehicle->customer;

            if (!$customer || !$customer->email) {
                Log::warning("Không thể gửi thông báo cho lịch bảo trì ID {$schedule->maintenance_id}: Khách hàng hoặc email không tồn tại.");
                continue;
            }

            try {
                Mail::to($customer->email)->send(new MaintenanceNotification($schedule, $vehicle));

                $schedule->update(['notified' => true]);
                Log::info("Đã gửi thông báo bảo trì cho khách hàng {$customer->email}, xe {$vehicle->license_plate}");
            } catch (\Exception $e) {
                Log::error("Lỗi khi gửi thông báo bảo trì cho {$customer->email}: {$e->getMessage()}");
            }
        }

        return count($schedules) . ' thông báo bảo trì đã được xử lý.';
    }
}
