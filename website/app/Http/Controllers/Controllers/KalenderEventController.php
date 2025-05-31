<?php

namespace App\Http\Controllers;

use App\Models\KalenderEvent;
use Illuminate\Http\Request;

class KalenderEventController extends Controller
{
    public function index()
    {
        return KalenderEvent::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cuti_id' => 'nullable|exists:cuti,id',
        ]);

        return KalenderEvent::create($data);
    }

    public function update(Request $request, KalenderEvent $kalenderEvent)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cuti_id' => 'nullable|exists:cuti,id',
        ]);

        $kalenderEvent->update($data);
        return $kalenderEvent;
    }

    public function destroy(KalenderEvent $kalenderEvent)
    {
        $kalenderEvent->delete();
        return response()->noContent();
    }
}
