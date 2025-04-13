<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        \Log::info("user");
        \Log::info($user);

        $user->token = $user->createToken('api')->accessToken;

        return response()->json([
            'message' => 'login successfully',
            'user' => $user,
        ]);
    }
}