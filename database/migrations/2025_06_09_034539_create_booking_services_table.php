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
        Schema::create('booking_services', function (Blueprint $table) {
            $table->bigIncrements('booking_services_id')->primary();
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
