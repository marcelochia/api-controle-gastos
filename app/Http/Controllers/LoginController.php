<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credential = $request->only('email', 'password');
        if (!Auth::attempt($credential)) {
            return response()->json('Unauthorized', 401);
        }
    
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('token');
    
        return response()->json($token->plainTextToken);
    }
}
