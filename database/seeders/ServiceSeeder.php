<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Services::create([
             'service_name' => 'Thay nhớt',
             'duration_minute'=>20,
             'created_at'=>now(),
             'updated_at'=>now(),
            
         ]);
         Services::create([
             'service_name' => 'Thay bugi',
             'duration_minute'=>20,
             'created_at'=>now(),
             'updated_at'=>now(),
            
         ]);
         Services::create([
             'service_name' => 'Sửa phanh',
             'duration_minute'=>20,
             'created_at'=>now(),
             'updated_at'=>now(),
            
         ]);
         Services::create([
             'service_name' => 'Bảo dưỡng',
             'duration_minute'=>20,
             'created_at'=>now(),
             'updated_at'=>now(),
            
         ]);
         Services::create([
             'service_name' => 'Rửa xe',
             'duration_minute'=>20,
             'created_at'=>now(),
             'updated_at'=>now(),
            
         ]);
    }
}
