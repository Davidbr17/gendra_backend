<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'accessToken' => User::where('email',$request->email)->first()->createToken($request->email)->plainTextToken,
        ]);
    }

    function logout(Request $request){
        
        $request->user()->currentAccessToken()->delete();

        return ['success' => true];
    }

}
