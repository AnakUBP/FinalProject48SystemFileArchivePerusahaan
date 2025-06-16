<?php

namespace App\Http\Controllers;

use App\Models\Templates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Templates::latest()->get();
        return view('template', compact('templates'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_template' => 'required|string|max:255',
                'template_file' => 'required|file|mimes:docx|max:2048',
                'versi' => 'required|string|max:20'
            ]);

            $filePath = $request->file('template_file')->store('public/templates');

            Templates::create([
                'nama_template' => $validatedData['nama_template'],
                'file_path' => str_replace('public/', '', $filePath),
                'versi' => $validatedData['versi'],
                'active' => $request->has('active')
            ]);

            return redirect()->route('templates.index')
                ->with('success', 'Template berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan template: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Templates $template)
    {
        try {
            $validatedData = $request->validate([
                'nama_template' => 'required|string|max:255',
                'template_file' => 'nullable|file|mimes:docx|max:2048',
                'versi' => 'required|string|max:20'
            ]);

            $data = [
                'nama_template' => $validatedData['nama_template'],
                'versi' => $validatedData['versi'],
                'active' => $request->has('active')
            ];

            if ($request->hasFile('template_file')) {
                Storage::delete('public/' . $template->file_path);
                $filePath = $request->file('template_file')->store('public/templates');
                $data['file_path'] = str_replace('public/', '', $filePath);
            }

            $template->update($data);

            return redirect()->route('templates.index')
                ->with('success', 'Template berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui template: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Templates $template)
    {
        try {
            // Hapus file dari storage
            if (Storage::exists('public/' . $template->file_path)) {
                Storage::delete('public/' . $template->file_path);
            }

            // Hapus record dari database
            $template->delete();

            return back()->with('success', 'Template berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus template: ' . $e->getMessage());
        }
    }
}
