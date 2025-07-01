<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Templates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Pastikan ini di-import

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
class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Templates::query();

        // Logika Pencarian
        if ($request->filled('search')) {
            $query->where('nama_template', 'like', '%' . $request->search . '%');
        }

        // Logika Filter Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('active', $request->status);
        }
        
        // FIX: Logika Filter berdasarkan Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Logika Sorting
        $sortBy = $request->input('sort', 'terbaru');
        switch ($sortBy) {
            case 'nama_asc': $query->orderBy('nama_template', 'asc'); break;
            case 'nama_desc': $query->orderBy('nama_template', 'desc'); break;
            case 'terlama': $query->orderBy('created_at', 'asc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        $templates = $query->get();
        $template = Templates::latest()->first(); // ambil data terakhir

        // FIX: Ambil daftar kategori unik untuk dropdown filter
        $kategoriList = Templates::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');

        return view('template', [
            'templates' => $templates,
            'kategoriList' => $kategoriList, // Kirim daftar kategori ke view
            'filters' => $request->only(['search', 'status', 'sort', 'kategori'])
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_template' => 'required|string|max:255',
                'kategori'      => 'nullable|string|max:100', // FIX: Tambah validasi kategori
                'template_file' => 'required|file|mimes:docx|max:2048',
                'versi'         => 'required|string|max:20'
            ]);
    
            $filePath = $request->file('template_file')->store('public/templates');
    
            Templates::create([
                'nama_template' => $validatedData['nama_template'],
                'kategori'      => $validatedData['kategori'], // FIX: Simpan kategori
                'file_path'     => str_replace('public/', '', $filePath),
                'versi'         => $validatedData['versi'],
                'active'        => $request->has('active')
            ]);
            $template = Templates::latest()->first(); // Ambil data terakhir
            logActivity('tambah_template', 'Menambahkan template: ' . $template->nama_template, $template);
    
            return redirect()->route('templates.index')->with('success', 'Template berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan template: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Templates $template)
    {
        try {
            $validatedData = $request->validate([
                'nama_template' => 'required|string|max:255',
                'kategori'      => 'nullable|string|max:100', // FIX: Tambah validasi kategori
                'template_file' => 'nullable|file|mimes:docx|max:2048',
                'versi'         => 'required|string|max:20'
            ]);
            
            $data = [
                'nama_template' => $validatedData['nama_template'],
                'kategori'      => $validatedData['kategori'], // FIX: Simpan kategori
                'versi'         => $validatedData['versi'],
                'active'        => $request->has('active')
            ];
    
            if ($request->hasFile('template_file')) {
                Storage::delete('public/' . $template->file_path);
                $filePath = $request->file('template_file')->store('public/templates');
                $data['file_path'] = str_replace('public/', '', $filePath);
            }
    
            $template->update($data);
            logActivity('ubah_template', 'Memperbarui template: ' . $template->nama_template, $template);

    
            return redirect()->route('templates.index')->with('success', 'Template berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui template: ' . $e->getMessage());
        }
    }
    
    public function destroy(Templates $template)
    {
        // ... method destroy tidak perlu diubah ...
        try {
            if (Storage::exists('public/' . $template->file_path)) {
                Storage::delete('public/' . $template->file_path);
            }
            $template->delete();
            logActivity('hapus_template', 'Menghapus template: ' . $template->nama_template, $template);

            return back()->with('success', 'Template berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }
}