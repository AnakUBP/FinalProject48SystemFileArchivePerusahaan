<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = Users::with('profile')->latest()->paginate(10);
        return view('users.index]', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,karyawan',
        ]);

        $validated['password'] = bcrypt($request->password);

        $user = Users::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Tambahkan method edit, update, destroy sesuai kebutuhan
}
