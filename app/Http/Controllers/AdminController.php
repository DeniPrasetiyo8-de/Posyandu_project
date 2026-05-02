<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Child;
use App\Models\HealthRecord;
use App\Models\Posyandu;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_anak' => Child::count(),
            'total_jadwal' => Schedule::count(),
            'total_posyandu' => Posyandu::count(),
            'total_kms' => HealthRecord::count(),
        ];

        $recentJadwals = Schedule::with('posyandu')->latest()->take(5)->get();
        $recentChildren = Child::with('user', 'posyandu')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentJadwals', 'recentChildren'));
    }
    
    // Placeholder for CRUD methods - to be implemented
    public function jadwal() { /* CRUD Jadwal */ }
    public function informasi() { /* Search Anak/Ibu */ }
    public function kmsAnalytics() { /* Stats + saran */ }
    public function kader() { /* CRUD Kader */ }
}

