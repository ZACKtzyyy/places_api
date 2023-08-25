<?php
namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Review;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    public function store(Request $request, $placeId)
    {
        try {
            $place = Place::find($placeId);
            $place->avg_rating = ($place->avg_rating * count($place->reviews) + $request->rating) / (count($place->reviews) + 1);

            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;

            Review::create([
                "rating" => $request->rating,
                "user_id" => $userId,
                "place_id" => $placeId,
                "comments" => $request->comments
            ]);

            $place->save();

            return response()->json(["success" => true, "message" => "Review successfully added"]);
        } catch (\Exception $err) {
            return response()->json(["success" => false, "message" => $err->getMessage()]);
        }
    }

    public function update(Request $request, $placeId, $reviewId)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;

        $review = Review::where('user_id', $userId)
            ->where('place_id', $placeId)
            ->where('id', $reviewId)
            ->first();

        if (!$review) {
            return response()->json(["success" => false, "message" => "Review not found or you are not authorized"]);
        }

        $review->rating = $request->rating;
        $review->comments = $request->comments;
        $review->save();

        return response()->json(["success" => true, "message" => "Review successfully updated"]);
    } catch (\Exception $err) {
        return response()->json(["success" => false, "message" => $err->getMessage()]);
    }
}


    public function delete($placeId, $reviewId)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;

            $review = Review::where('user_id', $userId)
                ->where('place_id', $placeId)
                ->where('id', $reviewId)
                ->first();

            if (!$review) {
                return response()->json(["success" => false, "message" => "Review not found or you are not authorized"]);
            }

            $review->delete();

            // Recalculate average rating for the place
            $place = Place::find($placeId);
            $reviews = $place->reviews;
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review->rating;
            }
            $place->avg_rating = count($reviews) > 0 ? $totalRating / count($reviews) : 0;
            $place->save();

            return response()->json(["success" => true, "message" => "Review successfully deleted"]);
        } catch (\Exception $err) {
            return response()->json(["success" => false, "message" => $err->getMessage()]);
        }
    }
}
