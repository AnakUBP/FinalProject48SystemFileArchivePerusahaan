<?php

namespace App\Http\Controllers;

use App\Models\ArsipCuti;
use Illuminate\Http\Request;

class ArsipCutiController extends Controller
{
    public function index()
    {
        return ArsipCuti::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cuti_id' => 'required|exists:cuti,id',
            'file_path' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        return ArsipCuti::create($data);
    }

    public function update(Request $request, ArsipCuti $arsipCuti)
    {
        $data = $request->validate([
            'file_path' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $arsipCuti->update($data);
        return $arsipCuti;
    }

    public function destroy(ArsipCuti $arsipCuti)
    {
        $arsipCuti->delete();
        return response()->noContent();
    }
}