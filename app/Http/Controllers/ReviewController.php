<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request){
        $review = new Review();
        
        $review->rating = $request->rating;
        $review->comments = $request->comments;
        $review->place_id = $request->place_id;
        $review->user_id = $request->user_id;

        if ($review->save()){
            return response()->json([
                "success"=>true,
                "message"=>"Review succesfully added"
                
            ]);
        }
        else{
            return response()->json([
                "success"=>false,
                "message"=>"wrong"
            ]);
        }
    }
    //Read All
    public function index(){
        $reviews = Review::all();
            if ($reviews){
                return response()->json([
                    "success"=>true,
                    "data"=>$reviews
                ]);
            }
            else {
                return response()->json([
                    "success" => false,
                    "data" => "Something is wrong"
                ]);
            }
        
    }
    //Read by ID
    public function show($id){
        $review = Review::find($id);
        if ($review){
            return response() -> json([
                "success"=>true,
                "data" =>$review
            ]);
        }
        else{
            return response()->json([
                "success" =>false,
                "data" => "Something is wrong"
            ]);
        }

    }
    //Update
    public function update(Request $request, $id){
        $review = Review::find($id);
        if ($review){
        $review->rating = $request->rating;
        $review->comments = $request->comments;
        $review->place_id = $request->place_id;
        $review->user_id = $request->user_id; 
        if ($review->save()){
            return response()->json([
                "success"=>true,
                "message"=>"Review successfull updated",
                "data"=>$review
            ]);
        } 
        else{
            return response()->json([
                "success"=>false,
                "data" => "Something is wrong",
            ]);
        }
        }
        


    //Delete
    }
    public function delete($id){
        $review = Review::find($id);
        if ($review){
            if($review->delete()){
                return response()->json([
                    "success"=>true,
                    "message"=>"Successfully delete"
                ]);
            }
            else{
                return response()->json([
                    "success"=>false,
                    "message"=>"Something is wrong"
                ]);
            }
        }

    }
    
}
