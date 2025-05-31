<?php

namespace App\Http\Controllers;

use App\Models\LogSurat;
use Illuminate\Http\Request;

class LogSuratController extends Controller
{
    public function index()
    {
        return response()->json(LogSurat::with('cuti')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cuti_id' => 'nullable|exists:cuti,id',
            'jenis' => 'required|in:masuk,keluar',
            'nomor_surat' => 'required|string|unique:log_surat,nomor_surat',
            'kategori' => 'nullable|string',
            'status' => 'in:baru,diproses,selesai',
            'catatan' => 'nullable|string',
        ]);

        $log = LogSurat::create($data);
        return response()->json($log, 201);
    }
}
