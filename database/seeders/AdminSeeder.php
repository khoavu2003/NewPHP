<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        // // (Tùy chọn) Thêm một số người dùng cố định để test
         Admin::create([
             'name' => 'Admin User',
            'email' => 'admin@example.com',
             'password' => Hash::make('123456'),
             'remember_token' => null,
             'verify_email' => true,
            'is_active' => true,
             'is_delete' => false,
             'group_role' => 'admin',
             'last_login_at' => now(),
             'last_login_ip' => '192.168.1.1',
            'created_at' => now(),
             'updated_at' => now(),
      ]);
     
         Admin::create([
             'name' => 'Admin User 2',
            'email' => 'admin2@example.com',
             'password' => Hash::make('123456'),
             'remember_token' => null,
             'verify_email' => true,
            'is_active' => true,
             'is_delete' => false,
             'group_role' => 'admin',
             'last_login_at' => now(),
             'last_login_ip' => '192.168.1.1',
            'created_at' => now(),
             'updated_at' => now(),
      ]);

    }
}
