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
    
          
            $ratingDifference = $request->rating - $review->rating;
    
            $review->rating = $request->rating;
            $review->comments = $request->comments;
            $review->save();
    
            $place = Place::find($placeId);
            $place->avg_rating = ($place->avg_rating * count($place->reviews) + $ratingDifference) / count($place->reviews);
            $place->save();
    
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
    
            $ratingDifference = -$review->rating;
    
            $review->delete();
    
            $place = Place::find($placeId);
            $place->avg_rating = ($place->avg_rating * count($place->reviews) + $ratingDifference) / count($place->reviews);
            $place->save();
    
            return response()->json(["success" => true, "message" => "Review successfully deleted"]);
        } catch (\Exception $err) {
            return response()->json(["success" => false, "message" => $err->getMessage()]);
        }
    }
}    