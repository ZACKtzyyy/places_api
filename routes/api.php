<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReviewController;


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

Route::get('/hello', function(){
    return "Hello World";
});

Route::get('/goodbye/{name}', function($name){
    return "Goodbye ".$name;
});

Route::post('/info', function(Request $request){
    return "Hello ".$request["name"]."you are  ".$request["age"]. "years old";
});

Route::post("/places",[PlaceController::class,'store']);

Route::get("/places",[PlaceController::class,'index']);

Route::get("/places/{id}",[PlaceController::class,'show']);

Route::put('/places/{id}',[PlaceController::class, 'update']);

Route::delete('/places/{id}',[PlaceController::class, 'delete']);


Route::post("/reviews",[ReviewController::class,'store']);

Route::get("/reviews",[ReviewController::class,'index']);
 
Route::get("/reviews/{id}",[ReviewController::class,'show']);

Route::put('/reviews/{id}',[ReviewController::class, 'update']);

Route::delete('/reviews/{id}',[ReviewController::class, 'delete']);



