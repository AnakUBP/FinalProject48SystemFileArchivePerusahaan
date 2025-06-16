<?php

namespace App\Http\Controllers;

use App\Models\Users; // FIX: Diubah ke Users
use App\Models\Profile; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersProfileController extends Controller
{
    /**
     * Menampilkan halaman manajemen user dan profil.
     */
    public function index()
    {
        $users = Users::with('profile')->latest()->get(); // FIX: Diubah ke Users
        return view('UsersProfiles', compact('users'));
    }

    /**
     * Menyimpan user dan profil baru.
     */
    public function store(Request $request)
    {
        // Memulai blok 'try' untuk menangani potensi error selama eksekusi.
        // Jika ada error di dalam blok ini, eksekusi akan melompat ke blok 'catch'.
        try {
            // Langkah Validasi: Memastikan semua data yang dikirim dari form sesuai aturan.
            // Jika validasi gagal, Laravel akan otomatis melempar 'ValidationException'.
            $validatedData = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|string|email|max:255|unique:users', // Email harus unik di tabel users
                'password'      => 'required|string|min:8',
                'role'          => ['required', Rule::in(['admin', 'karyawan'])], // Role harus salah satu dari 'admin' atau 'karyawan'
                'nama_lengkap'  => 'nullable|string|max:255', // Boleh kosong
                'jabatan'       => 'nullable|string|max:100',
                'telepon'       => 'nullable|string|max:15',
                'jenis_kelamin' => ['required', Rule::in(['pria', 'wanita'])], // Wajib diisi
                'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Boleh kosong, harus gambar, max 2MB
            ]);

            // === Langkah 1: Buat dan Simpan Data User ===
            
            // Membuat instance/objek baru dari Model Users.
            $user = new Users(); // FIX: Diubah ke Users
            
            // Mengisi properti objek User dengan data yang sudah divalidasi.
            $user->name     = $validatedData['name'];
            $user->email    = $validatedData['email'];
            $user->password = $validatedData['password']; // Password akan otomatis di-hash oleh mutator di Model User
            $user->role     = $validatedData['role'];
            $user->aktif    = $request->has('aktif'); // Bernilai true jika checkbox 'aktif' dicentang
            
            // Menyimpan objek User ke dalam tabel 'users' di database.
            // Setelah ini, $user akan memiliki ID yang diberikan oleh database.
            $user->save(); 

            // === Langkah 2: Buat dan Simpan Data Profil ===

            // Membuat instance/objek baru dari Model Profile.
            $profile = new Profile();

            // Menghubungkan profil ini dengan user yang baru saja dibuat menggunakan ID-nya.
            $profile->users_id = $user->id; 

            // Mengisi properti objek Profile dengan data dari request.
            $profile->nama_lengkap  = $request->nama_lengkap;
            $profile->jabatan       = $request->jabatan;
            $profile->telepon       = $request->telepon;
            $profile->jenis_kelamin = $request->jenis_kelamin;

            // Memeriksa apakah ada file 'foto' yang di-upload bersama form.
            if ($request->hasFile('foto')) {
                // Menyimpan file ke 'storage/app/public/profiles' dan mendapatkan path-nya.
                $filePath = $request->file('foto')->store('public/profiles');
                // Menyimpan path bersih (tanpa 'public/') ke properti 'foto' di objek profile.
                $profile->foto = str_replace('public/', '', $filePath);
            }

            // Menyimpan objek Profile ke dalam tabel 'profiles' di database.
            $profile->save();

            // Jika semua langkah di atas berhasil, arahkan kembali ke halaman daftar user.
            // 'with()' akan mengirimkan pesan sukses (flash message) ke sesi.
            return redirect()->route('user-profiles.index')->with('success', 'User berhasil ditambahkan.');

        // Menangkap error yang spesifik terjadi karena validasi gagal.
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Arahkan pengguna kembali ke halaman form sebelumnya.
            // 'withErrors()' akan membawa semua pesan error validasi.
            // 'withInput()' akan mengisi kembali form dengan data yang sebelumnya diinput pengguna.
            return redirect()->back()->withErrors($e->validator)->withInput();
        
        // Menangkap semua jenis error lain yang mungkin terjadi (misal: error database).
        } catch (\Exception $e) {
            // Arahkan kembali ke halaman sebelumnya dengan pesan error yang lebih umum.
            // '$e->getMessage()' akan menampilkan pesan error asli untuk debugging.
            return redirect()->back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Memperbarui user dan profil yang ada.
     */
    public function update(Request $request, Users $userProfile) // FIX: Diubah ke Users
    {
        // ... (kode update)
    }

    /**
     * Menghapus user (soft delete).
     */
    public function destroy(Users $userProfile) // FIX: Diubah ke Users
    {
        // ... (kode destroy)
    }
}
