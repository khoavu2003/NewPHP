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
        Schema::create('repair_parts', function (Blueprint $table) {
            $table->bigIncrements('repair_parts_id')->primary();
           $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->unsignedBigInteger('employee_id');
            $table->string('part_name');
            $table->integer('quantity')->default(1);
            $table->decimal('part_cost',12,2)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_parts');
    }
};
