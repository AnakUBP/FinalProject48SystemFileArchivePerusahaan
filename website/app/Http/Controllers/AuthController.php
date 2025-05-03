<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email tidak terdaftar.'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
            'token' => $token
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Mengecek jika ada token yang valid untuk dihapus
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout berhasil.']);
        }

        return response()->json(['message' => 'Tidak ada token untuk dihapus.'], 400);
    }
}
