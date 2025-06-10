<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'customer_id'=>1,
            'lisense-plate'=>'67L1-38474',
            'model'=>'Airblade',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        Vehicle::create([
            'customer_id'=>2,
            'lisense-plate'=>'67L2-38474',
            'model'=>'Airblade',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
    }
}
