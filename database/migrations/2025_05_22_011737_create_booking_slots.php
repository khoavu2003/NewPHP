<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_slots', function (Blueprint $table) {
            $table->id('slot_id'); // Khóa chính tự tăng
           $table->unsignedBigInteger('booking_id');
           $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
           $table->dateTime('slot_time');
           $table->unique(['booking_id','slot_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_slots');
    }
};
