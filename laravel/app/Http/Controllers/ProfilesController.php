<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto_profil' => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $validated['foto_profil'] = $path;
        }

        $user->update($validated);
        $user->profile()->updateOrCreate([], $validated);

        return back()->with('success', 'Profile updated successfully');
    }
}
