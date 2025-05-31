<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
 
    protected function simpanFile(Request $request, string $namaField, string $folder = 'uploads', string $disk = 'public'): ?string
    {
        if ($request->hasFile($namaField) && $request->file($namaField)->isValid()) {
            return $request->file($namaField)->store($folder, $disk);
        }
        return null;
    }


    protected function hapusFile(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    protected function updateFile(Request $request, string $namaField, ?string $oldPath = null, string $folder = 'uploads', string $disk = 'public'): ?string
    {
        if ($request->hasFile($namaField) && $request->file($namaField)->isValid()) {
            $this->hapusFile($oldPath, $disk);
            return $this->simpanFile($request, $namaField, $folder, $disk);
        }
        return $oldPath;
    }
}
