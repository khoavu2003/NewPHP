<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\BookingService;
class DeleteOldBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    protected $signature = 'bookings:delete-old';
    protected $description = 'Tự động xoá tất cả các bookings cũ hơn 2 tuần';


    /**
     * Execute the console command.
     */
    public function handle(BookingService $bookingService)
    {
        $deleted = $bookingService->deleteOldBooking();

        $this->info("Đã xoá {$deleted} bookings cũ hơn 2 tuần.");
    }
}
