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
            $place->avg_rating = ($place->avg_rating * 
            count($place['reviews']) + $request->rating) / 
            (count($place['reviews']) + 1);
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

    public function index()
    {
        $reviews = Review::all();

        if ($reviews) {
            return response()->json([
                "success" => true,
                "data" => $reviews
            ]);
        } else {
            return response()->json([
                "success" => false,
                "data" => "Something is wrong"
            ]);
        }
    }

    public function show($id)
    {
        $review = Review::find($id);

        if ($review) {
            return response()->json([
                "success" => true,
                "data" => $review
            ]);
        } else {
            return response()->json([
                "success" => false,
                "data" => "Review not found"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        if ($review) {
            $review->rating = $request->rating;
            $review->comments = $request->comments;
            $review->place_id = $request->place_id;
            $review->user_id = $request->user_id;

            if ($review->save()) {
                return response()->json([
                    "success" => true,
                    "message" => "Review successfully updated",
                    "data" => $review
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Failed to update review"
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "Review not found"
            ]);
        }
    }

    public function delete($id)
    {
        $review = Review::find($id);

        if ($review) {
            if ($review->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "Review successfully deleted"
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Failed to delete review"
                ]);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "Review not found"
            ]);
        }
    }
}
