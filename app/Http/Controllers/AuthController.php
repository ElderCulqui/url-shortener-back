<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('app.name');
            $user->setAttribute('token', $token->plainTextToken);

            session()->regenerate();

            return response()->json($user, 200);
        }

        return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
