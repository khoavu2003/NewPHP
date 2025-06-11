<?php

namespace App\Console\Commands;

use App\Service\MaintenanceShedulesService;
use Illuminate\Console\Command;

class SendMaintenanceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi thông báo lịch bảo trì tự động cho khách hàng';

    /**
     * Execute the console command.
     */
    protected $notificationService;

    public function __construct(MaintenanceShedulesService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $result = $this->notificationService->sendMaintenanceNotifications();
        $this->info($result);
    }
}
