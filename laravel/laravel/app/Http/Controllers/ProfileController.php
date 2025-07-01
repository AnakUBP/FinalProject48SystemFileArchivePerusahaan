<?php

namespace App\Http\Controllers;
use app\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil dari PENGGUNA YANG SEDANG LOGIN.
     * Tidak ada parameter, karena halaman ini selalu untuk "saya".
     */
    public function show()
    {
        // Langsung ambil data dari user yang terautentikasi melalui sesi.
        $user = Auth::user()->load('profile');

        // Kembalikan view dan kirim data user tersebut.
        return view('profiles', compact('user'));
    }
}
