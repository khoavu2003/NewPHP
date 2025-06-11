<?php

namespace App\Service;

use App\Models\BookingReview;
use Illuminate\Support\Facades\Auth;

class RatingService{

    public function create($attributes = [])
    {
        if(Auth::id()==null){
            throw new \Exception('Vui lòng đăng nhập để đánh giá');
        }
        $existingReview = BookingReview::where('booking_id', $attributes['booking_id'])
            ->where('customer_id', Auth::id())
            ->first();

        if ($existingReview) {
            throw new \Exception('Booking này đã được đánh giá.');
        }

        return BookingReview::create([
            'booking_id' => $attributes['booking_id'],
            'customer_id' => Auth::id(),
            'rating' => $attributes['rating'],
            'comment' => $attributes['comment'] ?? null,
        ]);
    }


}