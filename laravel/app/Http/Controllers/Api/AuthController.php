<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan menggunakan model User (singular)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Menangani permintaan login dari karyawan.
     */
    public function login(Request $request)
    {
        try {
            // 1. Validasi input
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // 2. Coba autentikasi pengguna
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau Password salah.'
                ], 401);
            }

            // 3. Jika autentikasi berhasil, dapatkan data user
            $user = Auth::user();

            // === PERUBAHAN DI SINI ===
            // 4. Periksa apakah role user adalah 'karyawan'
            if ($user->role !== 'karyawan') {
                // Jika bukan karyawan, langsung logout dan kirim error
                Auth::logout(); // Logout sesi yang baru saja dibuat
                $user->tokens()->delete(); // Hapus token yang mungkin terbuat
                
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda bukan karyawan.'
                ], 403); // 403 Forbidden adalah status yang tepat
            }

            // 5. Jika user adalah karyawan, lanjutkan proses pembuatan token
            $user->tokens()->delete();
            $token = $user->createToken('auth-token-karyawan')->plainTextToken;

            // 6. Kembalikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'Login karyawan berhasil',
                'data'    => [
                    'user'  => $user->load('profile'),
                    'token' => $token,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mendapatkan data user yang sedang login.
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user()->load('profile')
        ]);
    }
}

