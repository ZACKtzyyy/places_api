<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PassportAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Routes protected by auth:sanctum middleware
Route::middleware('jwt.auth')->group(function () {


    // Create
    Route::post("/places",[PlaceController::class,'store']);
    Route::post("places/{placeId}/reviews",[ReviewController::class,'store']);
    // Read
    Route::get("/places",[PlaceController::class,'index']);
    Route::get("/reviews",[ReviewController::class,'index']);
    // Update
    Route::put('/places/{placeid}',[PlaceController::class,'update']);
    Route::put("places/{placeId}/reviews/{reviewId}", [ReviewController::class, 'update']);
    // Delete
    Route::delete('/places/{placeid}',[PlaceController::class, 'delete']);
    Route::delete("places/{placeId}/reviews/{reviewId}", [ReviewController::class, 'delete']);
    });

  
    

Route::get('/hello', function(){
    return "Hello World";
});

Route::get('/goodbye/{name}', function($name){
    return "Goodbye ".$name;
});

Route::post('/info', function(Request $request){
    return "Hello ".$request["name"]."you are  ".$request["age"]. "years old";
});


Route::get("/places",[PlaceController::class,'index']);

Route::get("/places/{id}",[PlaceController::class,'show']);
 
Route::get("/reviews/{id}",[ReviewController::class,'show']);


Route::post('/register', [PassportAuthController::class, 'register']);

Route::post('/login', [PassportAuthController::class, 'login']);



