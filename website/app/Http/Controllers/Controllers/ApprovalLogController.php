<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLog;
use Illuminate\Http\Request;

class ApprovalLogController extends Controller
{
    public function index()
    {
        return ApprovalLog::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cuti_id' => 'required|exists:cuti,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:disetujui,ditolak,menunggu',
            'catatan' => 'nullable|string',
            'urutan' => 'required|integer',
        ]);

        return ApprovalLog::create($data);
    }

    public function update(Request $request, ApprovalLog $approvalLog)
    {
        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak,menunggu',
            'catatan' => 'nullable|string',
            'urutan' => 'required|integer',
        ]);

        $approvalLog->update($data);
        return $approvalLog;
    }

    public function destroy(ApprovalLog $approvalLog)
    {
        $approvalLog->delete();
        return response()->noContent();
    }
}
