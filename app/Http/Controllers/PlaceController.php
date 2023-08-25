<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    //  
    //Create
    public function store(Request $request){
        $place = new Place();
        $place->address = $request->address;
        $place->email = $request->email;
        $place->phone_number = $request->phone_number;
        $place->image_url = $request->image_url;
        $place->description = $request->description;
        $place->name = $request->name;

        if ($place->save()){
            return response()->json([
                "success"=>true,
                "message"=>"Place succesfully added"
                
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
        // $places = Place::all();
        $places = Place::select("name","address","image_url","id","description","phone_number","avg_rating")->get();
            if ($places){
                return response()->json([
                    "success"=>true,
                    "data"=>$places
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
        $place = Place::with('reviews.user')->find($id);
        if ($place){
            return response() -> json([
                "success"=>true,
                "data" =>$place
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
        $place = Place::find($id);
        if ($place){
        $place->address = $request->address;
        $place->email = $request->email;
        $place->phone_number = $request->phone_number;
        $place->image_url = $request->image_url;
        $place->description = $request->description;
        $place->name = $request->name;   
        if ($place->save()){
            return response()->json([
                "success"=>true,
                "message"=>"Place successfull updated",
                "data"=>$place
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
        $place = Place::find($id);
        if ($place){
            if($place->delete()){
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
