<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use App\Models\Templates;
use Illuminate\Http\Request;

class JenisCutiController extends Controller
{
    /**
     * Menampilkan daftar semua jenis cuti.
     */
    public function index()
    {
        // Mengambil semua jenis cuti, diurutkan dari yang terbaru.
        // Eager load relasi 'templates' untuk menghindari N+1 query problem.
        $jenisCuti = JenisCuti::with('templates')->latest()->get();

        // Mengambil semua template yang aktif untuk dropdown di modal.
        $templates = Templates::where('active', true)->get();

        return view('jeniscuti', compact('jenisCuti', 'templates'));
    }

    /**
     * Menyimpan jenis cuti baru ke dalam database.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang masuk dari form
            $validatedData = $request->validate([
                'nama' => 'required|string|max:100',
                'template_id' => 'nullable|exists:templates,id',
                'keterangan' => 'nullable|string'
            ]);

            // Membuat record baru di database
            JenisCuti::create([
                'nama' => $validatedData['nama'],
                'template_id' => $validatedData['template_id'],
                'keterangan' => $validatedData['keterangan'],
                // Cek apakah checkbox 'is_active' dicentang atau tidak
            ]);

            return redirect()->route('jeniscuti.index')
                ->with('success', 'Jenis cuti berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembali ke halaman sebelumnya dengan error dan input lama
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Jika terjadi error lain, kembali dengan pesan error umum
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jenis cuti: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Memperbarui data jenis cuti yang sudah ada.
     * PENTING: Nama variabel $jeniscuti (huruf kecil) harus cocok dengan
     * parameter route {jeniscuti} yang dibuat oleh Route::resource().
     */
    public function update(Request $request, JenisCuti $jeniscuti)
    {
        try {
            // Validasi data yang masuk
            $validatedData = $request->validate([
                'nama' => 'required|string|max:100',
                'template_id' => 'nullable|exists:templates,id',
                'keterangan' => 'nullable|string'
            ]);

            // Menyiapkan data untuk diupdate
            $data = [
                'nama' => $validatedData['nama'],
                'template_id' => $validatedData['template_id'],
                'keterangan' => $validatedData['keterangan']
            ];

            // Melakukan update pada data yang ditemukan oleh Route Model Binding
            $jeniscuti->update($data);

            return redirect()->route('jeniscuti.index')
                ->with('success', 'Jenis cuti berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui jenis cuti: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus jenis cuti dari database.
     * PENTING: Nama variabel $jeniscuti (huruf kecil) juga harus cocok di sini.
     */
    public function destroy(JenisCuti $jeniscuti)
    {
        try {
            // Melakukan delete (soft delete jika model menggunakan trait SoftDeletes)
            $jeniscuti->delete();

            return back()->with('success', 'Jenis cuti berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jenis cuti: ' . $e->getMessage());
        }
    }
}
