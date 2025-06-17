<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersProfileController extends Controller
{
    /**
     * Menampilkan halaman utama untuk manajemen user dan profil.
     */
    public function index()
    {
        // Ambil semua user beserta relasi profilnya, urutkan dari yang terbaru.
        $users = Users::with('profile')->latest()->get();
        // Kembalikan view dan kirim data users.
        return view('UsersProfiles', compact('users'));
    }

    /**
     * Menyimpan user baru beserta profilnya.
     */
    public function store(Request $request)
    {
        try {
            // 1. Validasi semua input dari form.
            $validatedData = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|string|email|max:255|unique:users',
                'password'      => 'required|string|min:8',
                'role'          => ['required', Rule::in(['admin', 'karyawan'])],
                'nama_lengkap'  => 'nullable|string|max:255',
                'jabatan'       => 'nullable|string|max:100',
                'telepon'       => 'nullable|string|max:15',
                'jenis_kelamin' => ['required', Rule::in(['pria', 'wanita'])],
                'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // 2. Gunakan transaksi database untuk keamanan data.
            // Jika ada yang gagal, semua proses akan dibatalkan.
            DB::transaction(function () use ($request, $validatedData) {
                // Tahap 1: Buat dan simpan data ke tabel 'users'.
                $user = Users::create([
                    'name'      => $validatedData['name'],
                    'email'     => $validatedData['email'],
                    'password'  => $validatedData['password'], // Di-hash otomatis oleh mutator di model.
                    'role'      => $validatedData['role'],
                    'aktif'     => $request->has('aktif'),
                ]);

                // Tahap 2: Siapkan dan simpan data ke tabel 'profiles'.
                $profileData = [
                    'users_id'      => $user->id, // Hubungkan dengan user yang baru dibuat.
                    'nama_lengkap'  => $validatedData['nama_lengkap'],
                    'jabatan'       => $validatedData['jabatan'],
                    'telepon'       => $validatedData['telepon'],
                    'jenis_kelamin' => $validatedData['jenis_kelamin'],
                ];

                // Proses upload foto jika ada.
                if ($request->hasFile('foto')) {
                    $filePath = $request->file('foto')->store('profiles', 'public');
                    $profileData['foto'] = $filePath;
                }

                Profile::create($profileData);
            });

            // 3. Kembalikan ke halaman daftar dengan pesan sukses.
            return redirect()->route('user-profiles.index')->with('success', 'User baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Jika terjadi error, kembali dengan pesan error yang jelas.
            return back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengupdate data user dan profil yang sudah ada.
     */
    public function update(Request $request, Users $userProfile)
    {
        try {
            // Validasi data, email harus unik kecuali untuk user ini sendiri.
            $validatedData = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userProfile->id)],
                'password'      => 'nullable|string|min:8', // Tidak wajib diisi saat update.
                'role'          => ['required', Rule::in(['admin', 'karyawan'])],
                'nama_lengkap'  => 'nullable|string|max:255',
                'jabatan'       => 'nullable|string|max:100',
                'telepon'       => 'nullable|string|max:15',
                'jenis_kelamin' => ['required', Rule::in(['pria', 'wanita'])],
                'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            DB::transaction(function () use ($request, $userProfile, $validatedData) {
                // Update data di tabel 'users'.
                $userData = [
                    'name'      => $validatedData['name'],
                    'email'     => $validatedData['email'],
                    'role'      => $validatedData['role'],
                    'aktif'     => $request->has('aktif'),
                ];
                // Hanya update password jika diisi.
                if (!empty($validatedData['password'])) {
                    $userData['password'] = $validatedData['password'];
                }
                $userProfile->update($userData);

                // Update atau buat data di tabel 'profiles'.
                $profileData = $request->only(['nama_lengkap', 'jabatan', 'telepon', 'jenis_kelamin']);

                if ($request->hasFile('foto')) {
                    // Hapus foto lama sebelum upload yang baru.
                    if ($userProfile->profile?->foto) {
                        Storage::disk('public')->delete($userProfile->profile->foto);
                    }
                    $profileData['foto'] = $request->file('foto')->store('profiles', 'public');
                }

                // updateOrCreate: update jika profil ada, buat jika tidak ada.
                $userProfile->profile()->updateOrCreate(['users_id' => $userProfile->id], $profileData);
            });

            return redirect()->route('user-profiles.index')->with('success', 'Data user berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(Users $userProfile)
    {
        try {
            // Mencegah admin menghapus akunnya sendiri.
            if ($userProfile->id === auth()->id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            // Hapus foto profil dari storage jika ada.
            if ($userProfile->profile?->foto) {
                Storage::disk('public')->delete($userProfile->profile->foto);
            }

            // Hapus record user. Profil akan terhapus otomatis karena 'onDelete('cascade')'.
            $userProfile->delete();

            return redirect()->route('user-profiles.index')->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}