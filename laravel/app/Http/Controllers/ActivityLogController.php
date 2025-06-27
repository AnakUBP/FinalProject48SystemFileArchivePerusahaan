<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = ActivityLog::with('user')->latest()->take(10)->get();

        $actionList = ActivityLog::select('action')->distinct()->pluck('action');

            // Ambil 10 pertama
    


        return view('logs.index', compact('logs', 'actionList'));

    }

    public function loadMore(Request $request){
    $offset = $request->input('offset', 0);
    $logs = ActivityLog::with('user')
        ->latest()
        ->skip($offset)
        ->take(10)
        ->get();

    $html = '';
    foreach ($logs as $log) {
        $html .= view('logs._log_item', compact('log'))->render();
    }

    return response()->json(['html' => $html]);
}
}
