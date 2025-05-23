<?php

namespace Database\Seeders;

use App\Models\WorkingHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workingHours = [
            ['day_of_week' => 0, 'is_closed' => true], // Sunday: Closed
            ['day_of_week' => 1, 'start_time' => '08:00:00', 'end_time' => '20:00:00', 'break_start' => '12:00:00', 'break_end' => '13:00:00', 'is_closed' => false], // Monday
            ['day_of_week' => 2, 'start_time' => '08:00:00', 'end_time' => '20:00:00', 'break_start' => '12:00:00', 'break_end' => '13:00:00', 'is_closed' => false], // Tuesday
            ['day_of_week' => 3, 'start_time' => '08:00:00', 'end_time' => '20:00:00', 'break_start' => '12:00:00', 'break_end' => '13:00:00', 'is_closed' => false], // Wednesday
            ['day_of_week' => 4, 'start_time' => '08:00:00', 'end_time' => '20:00:00', 'break_start' => '12:00:00', 'break_end' => '13:00:00', 'is_closed' => false], // Thursday
            ['day_of_week' => 5, 'start_time' => '08:00:00', 'end_time' => '20:00:00', 'break_start' => '12:00:00', 'break_end' => '13:00:00', 'is_closed' => false], // Friday
            ['day_of_week' => 6, 'start_time' => '08:00:00', 'end_time' => '12:00:00', 'break_start' => null, 'break_end' => null, 'is_closed' => false], // Saturday
        ];

        WorkingHour::create([
            'day_of_week'=>0,
            'is_closed'=>true,
        ]);
         WorkingHour::create([
            'day_of_week'=>1,
            'is_closed'=>false,
            'start_time'=>'08:00:00',
            'end_time'=>'20:00:00',
            'break_time_start'=>'12:00:00',
            'break_time_end'=>'13:00:00',
        ]);
        WorkingHour::create([
            'day_of_week'=>2,
            'is_closed'=>false,
            'start_time'=>'08:00:00',
            'end_time'=>'20:00:00',
            'break_time_start'=>'12:00:00',
            'break_time_end'=>'13:00:00',
        ]);
         WorkingHour::create([
            'day_of_week'=>3,
            'is_closed'=>false,
            'start_time'=>'08:00:00',
            'end_time'=>'20:00:00',
            'break_time_start'=>'12:00:00',
            'break_time_end'=>'13:00:00',
        ]);
         WorkingHour::create([
            'day_of_week'=>4,
            'is_closed'=>false,
            'start_time'=>'08:00:00',
            'end_time'=>'20:00:00',
            'break_time_start'=>'12:00:00',
            'break_time_end'=>'13:00:00',
        ]);
         WorkingHour::create([
            'day_of_week'=>5,
            'is_closed'=>false,
            'start_time'=>'08:00:00',
            'end_time'=>'20:00:00',
            'break_time_start'=>'12:00:00',
            'break_time_end'=>'13:00:00',
        ]);
         WorkingHour::create([
            'day_of_week'=>6,
            'is_closed'=>false,
            'start_time'=>'08:00:00',
            'end_time'=>'12:00:00',
            'break_time_start'=>null,
            'break_time_end'=>null,
        ]);
    }
}
