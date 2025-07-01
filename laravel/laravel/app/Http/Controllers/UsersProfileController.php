<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
function logActivity($action, $description, $model = null)
{
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => $action,
        'description' => $description,
        'loggable_id' => $model?->id,
        'loggable_type' => $model ? get_class($model) : null,
    ]);
}

class UsersProfileController extends Controller
{
    /**
     * Menampilkan halaman utama untuk manajemen user dan profil.
     */
    public function index(Request $request)
    {
        $query = Users::with('profile');

        // 1. Logika PENCARIAN
        // Jika ada input 'search', cari di beberapa kolom
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhereHas('profile', function ($profileQuery) use ($searchTerm) {
                      $profileQuery->where('nama_lengkap', 'like', $searchTerm);
                  });
            });
        }

        // 2. Logika FILTER (SELEKSI) ROLE
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 3. Logika FILTER (SELEKSI) STATUS
        if ($request->filled('online_status')) {
            switch ($request->online_status) {
                case 'online':
                    // User dianggap online jika aktif dan aktivitas terakhir < 5 menit
                    $query->where('active', true)->where('last_seen', '>=', now()->subMinutes(5));
                    break;
                case 'offline':
                    // User dianggap offline jika aktif TAPI aktivitas terakhir > 5 menit ATAU belum pernah login (last_seen is null)
                    $query->where('active', true)->where(function ($q) {
                        $q->where('last_seen', '<', now()->subMinutes(5))
                          ->orWhereNull('last_seen');
                    });
                    break;
                case 'nonaktif':
                    // User yang akunnya dinonaktifkan oleh admin
                    $query->where('active', false);
                    break;
            }
        }

        // 4. Logika PENGURUTAN (SORT)
        $sortBy = $request->input('sort', 'terbaru');
        switch ($sortBy) {
            case 'nama_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Eksekusi query
        $users = $query->get();

        // Kirim data ke view
        return view('UsersProfiles', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status', 'sort'])
        ]);
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
                'alamat'        => 'nullable|string', // FIX: Menambahkan validasi untuk alamat
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
                    'active'     => $request->has('active'),
                ]);

                // Tahap 2: Siapkan dan simpan data ke tabel 'profiles'.
                $profileData = [
                    'users_id'      => $user->id, // Hubungkan dengan user yang baru dibuat.
                    'nama_lengkap'  => $validatedData['nama_lengkap'],
                    'jabatan'       => $validatedData['jabatan'],
                    'telepon'       => $validatedData['telepon'],
                    'alamat'        => $validatedData['alamat'], // FIX: Menambahkan alamat ke data profil
                    'jenis_kelamin' => $validatedData['jenis_kelamin'],
                ];

                // Proses upload foto jika ada.
                if ($request->hasFile('foto')) {
                    $filePath = $request->file('foto')->store('profiles', 'public');
                    $profileData['foto'] = $filePath;
                }

                Profile::create($profileData);
                logActivity('tambah_user', 'Menambahkan user: ' . $validatedData['name'], $user);

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
                'alamat'        => 'nullable|string', // FIX: Menambahkan validasi untuk alamat
                'jenis_kelamin' => ['required', Rule::in(['pria', 'wanita'])],
                'foto'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'kuota_tambahan'  => 'nullable|integer',
            ]);

            DB::transaction(function () use ($request, $userProfile, $validatedData) {
                // Update data di tabel 'users'.
                $userData = [
                    'name'      => $validatedData['name'],
                    'email'     => $validatedData['email'],
                    'role'      => $validatedData['role'],
                    'active'     => $request->has('active'),
                ];
                // Hanya update password jika diisi.
                if (!empty($validatedData['password'])) {
                    $userData['password'] = $validatedData['password'];
                }
                $userProfile->update($userData);


                // Update atau buat data di tabel 'profiles'.
                $profileData = $request->only(['nama_lengkap', 'jabatan', 'telepon', 'alamat', 'jenis_kelamin']); // FIX: Menambahkan alamat

                if ($request->hasFile('foto')) {
                    // Hapus foto lama sebelum upload yang baru.
                    if ($userProfile->profile?->foto) {
                        Storage::disk('public')->delete($userProfile->profile->foto);
                    }
                    $profileData['foto'] = $request->file('foto')->store('profiles', 'public');
                }

                $userProfile->profile()->updateOrCreate(
                    ['users_id' => $userProfile->id], // Kunci untuk mencari
                    $profileData                      // Data untuk diupdate atau dibuat
                );

                // 4. Setelah profil dijamin ada, baru tambahkan kuota.
                if ($request->filled('kuota_tambahan')) {
                    // FIX: Ambil ulang objek profil yang valid dari user untuk memastikan itu adalah model.
                    $profileToUpdate = $userProfile->profile;
                    if ($profileToUpdate) {
                        $profileToUpdate->increment('sisa_kuota_cuti', (int) $request->input('kuota_tambahan'));
                    }
                }
                // updateOrCreate: update jika profil ada, buat jika tidak ada.
                $userProfile->profile()->updateOrCreate(['users_id' => $userProfile->id], $profileData);
                logActivity('ubah_user', 'Memperbarui user: ' . $userProfile->name, $userProfile);
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
            logActivity('hapus_user', 'Menghapus user: ' . $userProfile->name, $userProfile);
            return redirect()->route('user-profiles.index')->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}