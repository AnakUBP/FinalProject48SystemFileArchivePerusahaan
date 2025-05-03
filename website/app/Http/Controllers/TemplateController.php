<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    // Menampilkan semua template surat dengan pagination
    public function index()
    {
        $templates = Template::paginate(10); // Pagination untuk menghindari load data yang besar
        return response()->json($templates);
    }

    // Menyimpan template surat baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx',
        ]);

        // Menyimpan file ke storage
        $filePath = $request->file('file')->store('templates');
        if (!$filePath) {
            return response()->json(['message' => 'File gagal diupload'], 500);
        }

        $template = Template::create([
            'name' => $request->name,
            'category' => $request->category,
            'file_path' => $filePath,
        ]);

        return response()->json($template, 201);
    }

    // Mengunduh template berdasarkan ID
    public function download($id)
    {
        $template = Template::find($id);

        if (!$template) {
            return response()->json(['message' => 'Template tidak ditemukan.'], 404);
        }

        return response()->download(storage_path("app/{$template->file_path}"));
    }

    // Menghapus template berdasarkan ID
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        $filePath = storage_path("app/{$template->file_path}");

        // Hapus file jika ada di storage
        if (Storage::exists($filePath)) {
            Storage::delete($template->file_path);
        }

        $template->delete();
        return response()->json(null, 204);
    }
}
