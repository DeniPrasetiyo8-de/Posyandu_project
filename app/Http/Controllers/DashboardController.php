<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Child;
use App\Models\HealthRecord;
use App\Models\Posyandu;
use App\Models\User;
use App\Models\Mother;

class DashboardController extends Controller
{
    public function index()
    {
        $jadwals = Schedule::with('posyandu')->latest()->take(10)->get();
        return view('dashboard.index', compact('jadwals'));
    }

// Gabungan Informasi Anak dan Ibu dalam satu halaman
    public function informasi()
    {
        $children = Auth::user()->children()->with('posyandu')->get();
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('dashboard.informasi', compact('children', 'mothers'));
    }

    // Halaman Informasi Anak
    public function informasiAnak()
    {
        $children = Auth::user()->children()->with('posyandu')->get();
        return view('dashboard.informasi-anak', compact('children'));
    }

    // Halaman Informasi Ibu
    public function informasiIbu()
    {
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('dashboard.informasi-ibu', compact('mothers'));
    }

    // Method untuk update status imunisasi
    public function updateImunisasi(Request $request)
    {
        $child = Child::where('id', $request->child_id)
            ->where('user_id', Auth::id())->first();
        
        if ($child) {
            $imunisasiStatus = json_decode($child->imunisasi_status, true) ?: [];
            $imunisasiStatus[$request->jenis_imunisasi] = $request->status;
            $child->imunisasi_status = json_encode($imunisasiStatus);
            $child->save();
            
            return response()->json(['success' => true, 'message' => 'Status imunisasi updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Child not found'], 404);
    }

    // Method untuk update status vitamin
    public function updateVitamin(Request $request)
    {
        $child = Child::where('id', $request->child_id)
            ->where('user_id', Auth::id())->first();
        
        if ($child) {
            $vitaminStatus = json_decode($child->vitamin_status, true) ?: [];
            $vitaminStatus[$request->jenis_vitamin] = $request->status;
            $child->vitamin_status = json_encode($vitaminStatus);
            $child->save();
            
            return response()->json(['success' => true, 'message' => 'Status vitamin updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Child not found'], 404);
    }

// Method untuk update status TT (Tetanus Toxoid)
    public function updateTT(Request $request)
    {
        $mother = Mother::where('id', $request->mother_id)
            ->where('user_id', Auth::id())->first();
        
        if ($mother) {
            $ttStatus = json_decode($mother->tt_status, true) ?: [];
            $ttStatus[$request->suntikan_tt] = $request->status;
            $mother->tt_status = json_encode($ttStatus);
            $mother->save();
            
            return response()->json(['success' => true, 'message' => 'Status TT updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Mother not found'], 404);
    }

    // Method untuk update status trimester
    public function updateTrimester(Request $request)
    {
        $mother = Mother::where('id', $request->mother_id)
            ->where('user_id', Auth::id())->first();
        
        if ($mother) {
            $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true) ?: [];
            $trimesterStatus[$request->trimester] = $request->status;
            $mother->trimester_status = json_encode($trimesterStatus);
            $mother->save();
            
            return response()->json(['success' => true, 'message' => 'Status trimester updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Mother not found'], 404);
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
            [
                'id' => 1,
                'judul' => 'Tahapan Gizi Bayi yang Tepat',
                'kategori' => 'Gizi Anak',
                'gambar' => 'G Tahapan gizi bayi.png',
                'isi' => 'Pemantauan pertumbuhan dan perkembangan bayi merupakan hal yang sangat penting dalam memastikan tumbuh kembang anak berjalan optimal. Gizi berperan krusial dalam mendukung pertumbuhan fisik dan perkembangan otak anak. Tahapan gizi bayi dimulai dari ASI eksklusif selama 6 bulan pertama, dilanjutkan dengan MPASI (Makanan Pendamping ASI) usia 6-12 bulan, hingga pola makan keluarga saat anak sudah berusia 1 tahun ke atas. Pastikan pemberian makanan sesuai dengan usia dan kebutuhan nutrisi anak.',
                'tanggal' => '15 Okt 2024'
            ],
            [
                'id' => 2,
                'judul' => 'Gizi Seimbang untuk Keluarga',
                'kategori' => 'Gizi Anak',
                'gambar' => 'G Gizi seimbang.jpg',
                'isi' => 'Gizi seimbang adalah susunan makanan harian yang mengandung nutrisi dalam jumlah dan proporsi yang tepat untuk menjaga kesehatan. Prinsip gizi seimbang mencakup variasi makanan dari berbagai kelompok seperti nasi dan umbi-umbian sebagai sumber energi, lauk-pauk sebagai sumber protein, sayuran dan buah-buahan sebagai sumber vitamin dan mineral, serta susu untuk calcium. Dengan menerapkan pola gizi seimbang dalam keluarga, kita dapat mencegah berbagai penyakit akibat malnutrition baik kekurangan maupun kelebihan nutrisi.',
                'tanggal' => '12 Okt 2024'
            ],
            [
                'id' => 3,
                'judul' => 'Jadwal Imunisasi Anak Lengkap',
                'kategori' => 'Imunisasi',
                'gambar' => 'G Imunisasi anak.png',
                'isi' => 'Imunisasi merupakan upaya perlindungan tubuh untuk kebal terhadap penyakit tertentu melalui pemberian vaksin. Jadwal imunisasi anak (0 Bulan), Hepatitis B (0, 1, 6 Bulan), Polio (0, 2, 4 Bulan), DPT-HB-Hib (2, 4, 6 Bulan), Campak (9 Bulan), dan imunisasi lanjutan sesuai ketentuan. Penting untuk mengikuti jadwal imunisasi agar anak terlindungi dari penyakit berbahaya seperti campak, polio, dan difteri. Jangan lupa untuk membawa buku KIA saat imunisasi.',
                'tanggal' => '10 Okt 2024'
            ],
        ];
        return view('dashboard.artikel', compact('artikels'));
    }
}

