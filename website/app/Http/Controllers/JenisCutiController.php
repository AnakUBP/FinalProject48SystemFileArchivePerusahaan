<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use Illuminate\Http\Request;

class JenisCutiController extends Controller
{
    public function index()
    {
        return JenisCuti::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer',
        ]);

        return JenisCuti::create($data);
    }

    public function update(Request $request, JenisCuti $jenisCuti)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer',
        ]);

        $jenisCuti->update($data);
        return $jenisCuti;
    }

    public function destroy(JenisCuti $jenisCuti)
    {
        $jenisCuti->delete();
        return response()->noContent();
    }
}