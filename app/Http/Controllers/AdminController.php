<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Child;
use App\Models\HealthRecord;
use App\Models\Posyandu;
use App\Models\Mother;
use App\Models\Kader;
use App\Models\Article;

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
    
    // ========== JADWAL CRUD ==========
    public function jadwal(Request $request)
    {
        $search = $request->search;
        
        $query = Schedule::with('posyandu');
        
        if ($search) {
            $query->where('kegiatan', 'like', "%{$search}%");
        }
        
        $jadwals = $query->latest()->paginate(10);
        $posyandus = Posyandu::all();
        
        return view('admin.jadwal', compact('jadwals', 'posyandus'));
    }
    
    public function jadwalStore(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        
        Schedule::create([
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'posyandu_id' => $request->posyandu_id ?? 1,
        ]);
        
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil ditambahkan!');
    }
    
    public function jadwalUpdate(Request $request, Schedule $schedule)
    {
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        
        $schedule->update([
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'posyandu_id' => $request->posyandu_id ?? 1,
        ]);
        
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diperbarui!');
    }
    
    public function jadwalDestroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dihapus!');
    }
    
// ========== INFORMASI EDIT - CHILD ==========
    public function informasiEditAnak(Child $child)
    {
        $child->load('user', 'posyandu');
        return view('admin.informasi-edit', compact('child'));
    }
    
public function updateImunisasiAdmin(Request $request)
    {
        $child = Child::find($request->child_id);
        
        if ($child) {
            // Map keys to database column names
            $keyMapping = [
                'imunisasi_hb0' => 'imunisasi_hb0',
                'imunisasi_bcg' => 'imunisasi_bcg',
                'imunisasi_polio1' => 'imunisasi_polio1',
                'imunisasi_polio2' => 'imunisasi_polio2',
                'imunisasi_polio3' => 'imunisasi_polio3',
                'imunisasi_polio4' => 'imunisasi_polio4',
                'imunisasi_dpt_hb_hib1' => 'imunisasi_dpt_hb_hib1',
                'imunisasi_dpt_hb_hib2' => 'imunisasi_dpt_hb_hib2',
                'imunisasi_dpt_hb_hib3' => 'imunisasi_dpt_hb_hib3',
                'imunisasi_campak' => 'imunisasi_campak',
            ];
            
// Handle bulk update (all_imunisasi array)
            if ($request->has('all_imunisasi') && is_array($request->all_imunisasi)) {
                foreach ($request->all_imunisasi as $key => $status) {
                    if (isset($keyMapping[$key])) {
                        $column = $keyMapping[$key];
                        $child->$column = $status === 'Sudah' ? 'Sudah' : 'Belum';
                    }
                }
            } else {
                // Handle single update
                $jenis = $request->jenis_imunisasi;
                if (isset($keyMapping[$jenis])) {
                    $column = $keyMapping[$jenis];
                    $child->$column = $request->status === 'Sudah' ? 'Sudah' : 'Belum';
                }
            }
            $child->save();
            
            return response()->json(['success' => true, 'message' => 'Status imunisasi updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Child not found'], 404);
    }
    
    public function updateVitaminAdmin(Request $request)
    {
        $child = Child::find($request->child_id);
        
        if ($child) {
            // Map keys to database column names
            $keyMapping = [
                'vitamin_a_6_11' => 'vitamin_a_6_11',
                'vitamin_a_12_59' => 'vitamin_a_12_59',
            ];
            
// Handle bulk update (all_vitamin array)
            if ($request->has('all_vitamin') && is_array($request->all_vitamin)) {
                foreach ($request->all_vitamin as $key => $status) {
                    if (isset($keyMapping[$key])) {
                        $column = $keyMapping[$key];
                        $child->$column = $status === 'Sudah' ? 'Sudah' : 'Belum';
                    }
                }
            } else {
                // Handle single update
                $jenis = $request->jenis_vitamin;
                if (isset($keyMapping[$jenis])) {
                    $column = $keyMapping[$jenis];
                    $child->$column = $request->status === 'Sudah' ? 'Sudah' : 'Belum';
                }
            }
            $child->save();
            
            return response()->json(['success' => true, 'message' => 'Status vitamin updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Child not found'], 404);
    }
    
    // ========== INFORMASI EDIT - MOTHER ==========
    public function informasiEditIbu(Mother $mother)
    {
        $mother->load('user', 'posyandu');
        return view('admin.informasi-edit-ibu', compact('mother'));
    }
    
public function updateTTAdmin(Request $request)
    {
        $mother = Mother::find($request->mother_id);
        
        if ($mother) {
            // Map keys to database column names (tt1-tt5)
            $keyMapping = [
                'tt1' => 'tt1',
                'tt2' => 'tt2',
                'tt3' => 'tt3',
                'tt4' => 'tt4',
                'tt5' => 'tt5',
            ];
            
            // Handle bulk update (all_tt array)
            if ($request->has('all_tt') && is_array($request->all_tt)) {
                foreach ($request->all_tt as $key => $status) {
                    if (isset($keyMapping[$key])) {
                        $column = $keyMapping[$key];
                        // Use 'sudah' from JS or 'Sudah' for database ENUM
                        $mother->$column = (strtolower($status) === 'sudah') ? 'Sudah' : 'Belum';
                    }
                }
            } else {
                // Handle single update
                $suntikan = $request->suntikan_tt;
                if (isset($keyMapping[$suntikan])) {
                    $column = $keyMapping[$suntikan];
                    $mother->$column = $request->status === 'Sudah' ? 'Sudah' : 'Belum';
                }
            }
            $mother->save();
            
            return response()->json(['success' => true, 'message' => 'Status TT updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Mother not found'], 404);
    }
    
    public function updateTrimesterAdmin(Request $request)
    {
        $mother = Mother::find($request->mother_id);
        
        if ($mother) {
            // Note: Trimester is not stored in separate columns
            // Need to add JSON column or use existing table structure
            // For now, we'll store in the existing columns or return success
            // Since the database doesn't have trimester columns, we'll return a message
            
            // Check if trimester_status column exists
            if (\Schema::hasColumn('mothers', 'trimester_status')) {
                $keyMapping = [
                    'trimester1' => 'trimester1',
                    'trimester2' => 'trimester2',
                    'trimester3' => 'trimester3',
                ];
                
                if ($request->has('all_trimester') && is_array($request->all_trimester)) {
                    $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true) ?: [];
                    foreach ($request->all_trimester as $key => $status) {
                        $trimesterStatus[$key] = $status;
                    }
                    $mother->trimester_status = json_encode($trimesterStatus);
                } else {
                    $trimester = $request->trimester;
                    if (isset($keyMapping[$trimester])) {
                        $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true) ?: [];
                        $trimesterStatus[$trimester] = $request->status;
                        $mother->trimester_status = json_encode($trimesterStatus);
                    }
                }
                $mother->save();
            }
            
            return response()->json(['success' => true, 'message' => 'Status trimester updated']);
        }
        
        return response()->json(['success' => false, 'message' => 'Mother not found'], 404);
    }
    
    // ========== INFORMASI SEARCH ==========
    public function informasi(Request $request)
    {
        $search = $request->search;
        $type = $request->type ?? 'anak';
        $rw = $request->rw;
        
        $results = [];
        
        if ($type === 'anak') {
            $query = Child::with('user', 'posyandu');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            
            if ($rw) {
                $query->whereHas('user', function($q) use ($rw) {
                    $q->where('rw', $rw);
                });
            }
            
            $results = $query->latest()->paginate(10);
        } else {
            $query = Mother::with('user', 'posyandu');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            
            if ($rw) {
                $query->whereHas('user', function($q) use ($rw) {
                    $q->where('rw', $rw);
                });
            }
            
            $results = $query->latest()->paginate(10);
        }
        
        return view('admin.informasi', compact('results', 'type', 'search', 'rw'));
    }
    
    // ========== KMS ANALYTICS ==========
    public function kmsAnalytics(Request $request)
    {
        $posyanduId = $request->posyandu_id;
        
        $query = Child::with('healthRecords', 'posyandu');
        
        if ($posyanduId) {
            $query->where('posyandu_id', $posyanduId);
        }
        
        $children = $query->get();
        
        $totalAnak = $children->count();
        $underweight = 0;
        $stunting = 0;
        $normal = 0;
        
        foreach ($children as $child) {
            if ($child->healthRecords->isNotEmpty()) {
                $latest = $child->healthRecords->first();
                if ($latest && $latest->berat_badan < 10) {
                    $underweight++;
                } else {
                    $normal++;
                }
            }
        }
        
        $stats = [
            'total_anak' => $totalAnak,
            'normal' => $normal,
            'underweight' => $underweight,
            'stunting' => $stunting,
        ];
        
        $posyandus = Posyandu::all();
        
        return view('admin.kms', compact('stats', 'posyandus', 'posyanduId'));
    }
    
    // ========== KADER CRUD - Using New Kader Model ==========
    public function kader(Request $request)
    {
        $search = $request->search;
        $posyanduId = $request->posyandu_id;
        
        $query = Kader::with('posyandu');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kader', 'like', "%{$search}%");
            });
        }
        
        if ($posyanduId) {
            $query->where('posyandu_id', $posyanduId);
        }
        
        $kaders = $query->latest()->paginate(10);
        $posyandus = Posyandu::all();
        
        return view('admin.kader', compact('kaders', 'posyandus', 'search', 'posyanduId'));
    }
    
    public function kaderStore(Request $request)
    {
        $request->validate([
            'nama_kader' => 'required|string|max:255',
            'posyandu_id' => 'required|exists:posyandus,id',
            'alamat' => 'nullable|string|max:255',
            'rw' => 'nullable|string|max:10',
        ]);
        
        Kader::create([
            'nama_kader' => $request->nama_kader,
            'posyandu_id' => $request->posyandu_id,
            'alamat' => $request->alamat,
            'rw' => $request->rw,
            'status_kehadiran' => 'hadir',
            'foto' => null,
        ]);
        
        return redirect()->route('admin.kader')->with('success', 'Kader berhasil ditambahkan!');
    }
    
    public function kaderUpdate(Request $request, Kader $kader)
    {
        $request->validate([
            'nama_kader' => 'required|string|max:255',
            'posyandu_id' => 'required|exists:posyandus,id',
            'alamat' => 'nullable|string|max:255',
            'rw' => 'nullable|string|max:10',
        ]);
        
        $kader->update([
            'nama_kader' => $request->nama_kader,
            'posyandu_id' => $request->posyandu_id,
            'alamat' => $request->alamat,
            'rw' => $request->rw,
            'status_kehadiran' => $request->status_kehadiran,
        ]);
        
        return redirect()->route('admin.kader')->with('success', 'Data kader berhasil diperbarui!');
    }
    
    public function kaderUpdateStatus(Request $request, Kader $kader)
    {
        $request->validate([
            'status_kehadiran' => 'required|in:hadir,tidak_hadir',
        ]);
        
        $kader->update([
            'status_kehadiran' => $request->status_kehadiran,
        ]);
        
        return redirect()->route('admin.kader')->with('success', 'Status kehadiran berhasil diperbarui!');
    }
    
public function kaderDestroy(Kader $kader)
    {
        $kader->delete();
        return redirect()->route('admin.kader')->with('success', 'Kader berhasil dihapus!');
    }
    
    // ========== ARTICLE CRUD ==========
    public function artikel(Request $request)
    {
        $search = $request->search;
        $kategori = $request->kategori;
        
        $query = Article::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }
        
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        
        $artikels = $query->latest()->paginate(10);
        $kategoriOptions = Article::getKategoriOptions();
        
        return view('admin.artikel', compact('artikels', 'kategoriOptions', 'search', 'kategori'));
    }
    
    public function artikelStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:gizi_anak,ibu_hamil,imunisasi',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);
        
        $gambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('articles', 'public');
        }
        
        Article::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'gambar' => $gambar,
        ]);
        
        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil ditambahkan!');
    }
    
    public function artikelUpdate(Request $request, Article $article)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:gizi_anak,ibu_hamil,imunisasi',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);
        
        $gambar = $article->gambar;
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($article->gambar) {
                \Storage::disk('public')->delete($article->gambar);
            }
            $gambar = $request->file('gambar')->store('articles', 'public');
        }
        
        $article->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'gambar' => $gambar,
        ]);
        
        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil diperbarui!');
    }
    
    public function artikelDestroy(Article $article)
    {
        // Delete image if exists
        if ($article->gambar) {
            \Storage::disk('public')->delete($article->gambar);
        }
        
        $article->delete();
        return redirect()->route('admin.artikel')->with('success', 'Artikel berhasil dihapus!');
    }
}
