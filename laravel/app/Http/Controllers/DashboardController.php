<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = Users::count();
        $karyawanCount = Users::where('role', 'karyawan')->count();
        $adminCount = Users::where('role', 'admin')->count();

        $recentUsers = Users::with('profile')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'userCount',
            'karyawanCount',
            'adminCount',
            'recentUsers'
        ));
    }
}
