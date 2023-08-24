<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8|max:16',
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            //$token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['sucess' => true,"message" => "User succesfully registered"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed ' . $e], 500);
        }
    }
    public function login(Request $request)
{
$credentials = $request->only('email', 'password');

if (! $token = JWTAuth::attempt($credentials)) {
return response()->json(['error' => 'Unauthorized'], 401);
}
return response()->json(['token' => $token], 200);
}




    //
}

