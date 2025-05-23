<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Customer::factory()->count(3)->create();

        
         Customer::create([
             'customer_name' => 'Nguyễn Văn A',
             'email' => 'nguyen.a@example.com',
             'tel_num'=>'0332168695',
             'password' => Hash::make('123456'),
             'created_at' => now(),
             'updated_at' => now(),
         ]);
    }
}
