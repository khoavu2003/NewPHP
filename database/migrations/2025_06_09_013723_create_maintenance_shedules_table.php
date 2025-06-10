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
        Schema::create('maintenance_shedules', function (Blueprint $table) {
            $table->bigIncrements('maintenance_id')->primary();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->onDelete('cascade');
            $table->date('last_maintenance_date');
            $table->date('next_maintenance_date');
            $table->boolean('notified')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_shedules');
    }
};
