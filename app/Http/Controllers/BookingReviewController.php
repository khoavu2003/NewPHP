<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRatingRequest;
use App\Service\RatingService;
use Illuminate\Http\Request;

class BookingReviewController extends Controller
{
    protected $ratingService;
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }
    public function createReview(AddRatingRequest $request)
    {
        try {
            $data = $request->validated();

            $rating = $this->ratingService->create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Đánh giá đã được gửi thành công.',
                'review' => $rating
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
