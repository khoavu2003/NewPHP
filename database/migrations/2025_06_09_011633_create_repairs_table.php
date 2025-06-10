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
        Schema::create('repairs', function (Blueprint $table) {
            $table->bigIncrements('repair_id')->primary();
            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->onDelete('cascade');
            $table->date('repair_date');
            $table->text('technician_note')->nullable();
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
