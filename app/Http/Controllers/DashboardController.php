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
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        $jadwals = Schedule::with('posyandu')->latest()->take(10)->get();
        return view('dashboard.index', compact('jadwals'));
    }

    public function informasi()
    {
        $children = Auth::user()->children()->with('posyandu')->get();
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('dashboard.informasi', compact('children', 'mothers'));
    }

    public function informasiAnak()
    {
        $children = Auth::user()->children()->with('posyandu')->get();
        return view('dashboard.informasi-anak', compact('children'));
    }

    public function informasiIbu()
    {
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('dashboard.informasi-ibu', compact('mothers'));
    }

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
        $kaders = \App\Models\Kader::with('posyandu')->get();
        $posyandus = Posyandu::all();
        
        return view('dashboard.kader', compact('kaders', 'posyandus'));
    }

    public function artikel(Request $request)
    {
        $query = Article::query();
        
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        $articles = $query->latest()->get();
        
        $artikels = $articles->map(function($article) {
            return [
                'id' => $article->id,
                'judul' => $article->judul,
                'kategori' => $article->kategori_label,
                'gambar' => $article->gambar ? $article->gambar : 'G Tahapan gizi bayi.png',
                'isi' => $article->isi,
                'tanggal' => $article->created_at ? \Carbon\Carbon::parse($article->created_at)->format('d M Y') : date('d M Y'),
            ];
        })->toArray();
        
        if (empty($artikels)) {
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
                    'isi' => 'Gizi seimbang adalah susunan makanan harian yang mengandung nutrisi dalam jumlah dan proporsi yang tepat untuk menjaga kesehatan. Prinsip gizi seimbang mencakup variasi makanan dari berbagai kelompok seperti nasi dan umbi-umbian sebagai sumber energi, lauk-pauk sebagai sumber protein, sayuran dan buah-buahan sebagai sumber vitamin dan mineral, serta susu untuk calcium.',
                    'tanggal' => '12 Okt 2024'
                ],
                [
                    'id' => 3,
                    'judul' => 'Jadwal Imunisasi Anak Lengkap',
                    'kategori' => 'Imunisasi',
                    'gambar' => 'G Imunisasi anak.png',
                    'isi' => 'Imunisasi merupakan upaya perlindungan tubuh untuk kebal terhadap penyakit tertentu melalui pemberian vaksin. Jadwal imunisasi anak (0 Bulan), Hepatitis B (0, 1, 6 Bulan), Polio (0, 2, 4 Bulan), DPT-HB-Hib (2, 4, 6 Bulan), Campak (9 Bulan), dan imunisasi lanjutan sesuai ketentuan. Penting untuk mengikuti jadwal imunisasi agar anak terlindungi dari penyakit berbahaya.',
                    'tanggal' => '10 Okt 2024'
                ],
            ];
        }
        
        return view('dashboard.artikel', compact('artikels'));
    }
}
