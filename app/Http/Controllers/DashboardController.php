<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Child;
use App\Models\HealthRecord;
use App\Models\Posyandu;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $jadwals = Schedule::with('posyandu')->latest()->take(10)->get();
        return view('dashboard.index', compact('jadwals'));
    }

    public function informasiAnak()
    {
        $children = Auth::user()->children()->with('posyandu')->get();
        return view('dashboard.informasi-anak', compact('children'));
    }

public function informasiIbu()
    {
        // Info umum untuk ibu (static atau model nanti)
        $infoIbu = [
            ['judul' => 'MP-ASI', 'deskripsi' => 'Pengenalan makanan pendamping ASI mulai usia 6 bulan...'],
            ['judul' => 'Vitamin A', 'deskripsi' => 'Diberikan setiap 6 bulan untuk anak...'],
            ['judul' => 'Kesehatan Ibu Hamil', 'deskripsi' => 'Pemeriksaan rutin dan suplementasi...'],
        ];
        // Get mothers data for this user
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('dashboard.informasi-ibu', compact('infoIbu', 'mothers'));
    }

public function kms()
    {
        $children = Auth::user()->children()->with('healthRecords')->get();
        $mothers = Auth::user()->mothers()->get();
        $healthRecords = HealthRecord::whereHas('child', function($query) {
            $query->where('user_id', Auth::id());
        })->with('child')->latest()->get();

        return view('dashboard.kms', compact('children', 'mothers', 'healthRecords'));
    }

    public function kader()
    {
        $posyandus = Posyandu::all()->map(function($posyandu) {
            // Demo status: random hijau/merah
            $posyandu->is_present = rand(0,1) ? true : false;
            return $posyandu;
        });
        return view('dashboard.kader', compact('posyandus'));
    }

    public function artikel()
    {
        $artikels = [
            ['judul' => 'Pentingnya Imunisasi Anak', 'isi' => 'Imunisasi adalah...'],
            ['judul' => 'Cara Membaca KMS', 'isi' => 'Kartu Menuju Sehat (KMS)...'],
            ['judul' => 'Gizi Seimbang untuk Balita', 'isi' => 'Pola makan yang baik...'],
        ];
        return view('dashboard.artikel', compact('artikels'));
    }
}

