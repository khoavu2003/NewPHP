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
        Schema::create('booking_reviews', function (Blueprint $table) {
            $table->bigIncrements('rating_id');
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->tinyInteger('rating')->comment('1-5');
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_reviews');
    }
};
