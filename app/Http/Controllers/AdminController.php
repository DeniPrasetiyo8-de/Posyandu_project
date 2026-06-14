<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Schedule;
use App\Models\Child;
use App\Models\HealthRecord;
use App\Models\Posyandu;
use App\Models\Mother;
use App\Models\Kader;
use App\Models\Article;
use App\Models\MotherHealthRecord;
use App\Models\Laporan;
use App\Models\ArsipData;
use App\Models\RwAccess;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
/**
     * Validasi Kode Akses RW
     * return: array ['valid' => true/false, 'rw' => '01'-'06' atau null, 'message' => string]
     */
    private function validasiKodeAkses($kode)
    {
        // Jika tidak ada kode, cek session
        if (!$kode) {
            $kode = Session::get('kode_akses');
            if (!$kode) {
                return ['valid' => false, 'rw' => null, 'message' => 'Silakan masukkan kode akses terlebih dahulu!'];
            }
        }
        
        // Normalisasi kode (uppercase, trimming)
        $kode = strtoupper(trim($kode));
        
        // Cari kode akses di database rw_accesses
        $rwAccess = RwAccess::where('kode_akses', $kode)->where('status', true)->first();
        
        // Jika tidak ditemukan atau status tidak aktif
        if (!$rwAccess) {
            return ['valid' => false, 'rw' => null, 'message' => 'Kode akses tidak valid atau sudah dinonaktifkan!'];
        }
        
        // Jika rw null, berarti kode superadmin (SIPANDU) bisa akses semua RW
        $rw = $rwAccess->rw;
        
        if ($rw === null) {
            return ['valid' => true, 'rw' => null, 'message' => 'Akses semua RW diberikan'];
        }
        
        return ['valid' => true, 'rw' => $rw, 'message' => 'Akses RW ' . $rw . ' diberikan'];
    }
    
    /**
     * Cek apakah kode sudah diverifikasi di session saat ini
     */
    private function isKodeVerified()
    {
        return Session::has('kode_akses') && Session::has('kode_verified_at');
    }
    
/**
     * Simpan kode ke session
     */
    private function simpanKodeSession($kode, $rw)
    {
        Session::put('kode_akses', $kode);
        Session::put('kode_rw', $rw);
        Session::put('kode_verified_at', now()->toDateTimeString());
    }
    
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
    
    /**
     * Update status AKTIF/NONAKTIF untuk anak atau ibu ( универсальный)
     */
    public function updateStatusAdmin(Request $request)
    {
        // Cek apakah ini untuk child atau mother
        if ($request->has('child_id')) {
            $child = Child::find($request->child_id);
            
            if ($child) {
                $validStatus = ['AKTIF', 'NONAKTIF'];
                $status = $request->status;
                
                if (!in_array($status, $validStatus)) {
                    return response()->json(['success' => false, 'message' => 'Status tidak valid'], 422);
                }
                
                $child->status = $status;
                $child->save();
                
                return response()->json(['success' => true, 'message' => 'Status anak berhasil diperbarui']);
            }
            
            return response()->json(['success' => false, 'message' => 'Anak tidak ditemukan'], 404);
        } elseif ($request->has('mother_id')) {
            $mother = Mother::find($request->mother_id);
            
            if ($mother) {
                $validStatus = ['AKTIF', 'NONAKTIF'];
                $status = $request->status;
                
                if (!in_array($status, $validStatus)) {
                    return response()->json(['success' => false, 'message' => 'Status tidak valid'], 422);
                }
                
                $mother->status = $status;
                $mother->save();
                
                return response()->json(['success' => true, 'message' => 'Status ibu berhasil diperbarui']);
            }
            
            return response()->json(['success' => false, 'message' => 'Ibu tidak ditemukan'], 404);
        }
        
        return response()->json(['success' => false, 'message' => 'ID tidak valid'], 400);
    }
    
    /**
     * Update status AKTIF/NONAKTIF untuk anak
     */
    public function updateStatusChild(Request $request)
    {
        $child = Child::find($request->child_id);
        
        if ($child) {
            $validStatus = ['AKTIF', 'NONAKTIF'];
            $status = $request->status;
            
            if (!in_array($status, $validStatus)) {
                return response()->json(['success' => false, 'message' => 'Status tidak valid'], 422);
            }
            
            $child->status = $status;
            $child->save();
            
            return response()->json(['success' => true, 'message' => 'Status anak berhasil diperbarui']);
        }
        
        return response()->json(['success' => false, 'message' => 'Anak tidak ditemukan'], 404);
    }
    
    /**
     * Update status AKTIF/NONAKTIF untuk ibu hamil
     */
    public function updateStatusMother(Request $request)
    {
        $mother = Mother::find($request->mother_id);
        
        if ($mother) {
            $validStatus = ['AKTIF', 'NONAKTIF'];
            $status = $request->status;
            
            if (!in_array($status, $validStatus)) {
                return response()->json(['success' => false, 'message' => 'Status tidak valid'], 422);
            }
            
            $mother->status = $status;
            $mother->save();
            
            return response()->json(['success' => true, 'message' => 'Status ibu berhasil diperbarui']);
        }
        
        return response()->json(['success' => false, 'message' => 'Ibu tidak ditemukan'], 404);
    }
    
// ========== INFORMASI SEARCH ==========
    public function informasi(Request $request)
    {
        $search = $request->search;
        $type = $request->type ?? 'anak';
        $rw = $request->rw;
        $kodeAkses = $request->kode_akses;
        
        // Inisialisasi results sebagai empty collection
        $results = new LengthAwarePaginator([], 0, 10);
        
        // Validasi Kode Akses
        $validasi = $this->validasiKodeAkses($kodeAkses);
        
        if (!$validasi['valid']) {
            // Kode tidak valid, tampilkan error dan form kosong
            return view('admin.informasi', compact('results', 'type', 'search', 'rw'))
                ->with('error_kode', $validasi['message']);
        }
        
        // Simpan kode ke session jika baru diverifikasi
        if ($kodeAkses && !Session::has('kode_akses')) {
            $this->simpanKodeSession(strtoupper(trim($kodeAkses)), $validasi['rw']);
        }
        
        // Filter RW berdasarkan kode yang diperbolehkan
        $rwFilter = $validasi['rw']; // null jika sipandu (semua RW)
        
        if ($type === 'anak') {
            $query = Child::with('user', 'posyandu');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            
            // Prioritaskan filter RW dari kode akses
            $effectiveRw = $rwFilter ?: $rw;
            if ($effectiveRw) {
                $query->whereHas('user', function($q) use ($effectiveRw) {
                    $q->where('rw', $effectiveRw);
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
            
            // Prioritaskan filter RW dari kode akses
            $effectiveRw = $rwFilter ?: $rw;
            if ($effectiveRw) {
                $query->whereHas('user', function($q) use ($effectiveRw) {
                    $q->where('rw', $effectiveRw);
                });
            }
            
            $results = $query->latest()->paginate(10);
        }
        
        return view('admin.informasi', compact('results', 'type', 'search', 'rw'))
            ->with('success_kode', $validasi['message']);
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
    
// ========== KMS IBU - ADMIN ==========
    /**
     * Halaman KMS Ibu - Daftar ibu dengan search dan tombol edit
     */
    public function kmsIbu(Request $request)
    {
        $search = $request->search;
        $posyanduId = $request->posyandu_id;
        $kodeAkses = $request->kode_akses;
        $rw = $request->rw;
        
        // Validasi Kode Akses
        $validasi = $this->validasiKodeAkses($kodeAkses);
        
        if (!$validasi['valid']) {
            // Kode tidak valid, tampilkan form kosong
           $mothers = new LengthAwarePaginator([], 0, 15);
            $posyandus = Posyandu::all();
            return view('admin.kms-ibu', compact('mothers', 'posyandus', 'search', 'posyanduId', 'rw'))
                ->with('error_kode', $validasi['message']);
        }
        
        // Simpan kode ke session jika baru diverifikasi
        if ($kodeAkses && !Session::has('kode_akses')) {
            $this->simpanKodeSession(strtoupper(trim($kodeAkses)), $validasi['rw']);
        }
        
        // Filter RW berdasarkan kode yang diperbolehkan
        $rwFilter = $validasi['rw']; // null jika sipandu (semua RW)
        
        $query = Mother::with('posyandu', 'user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }
        
        // Prioritaskan filter RW dari kode akses
        $effectiveRw = $rwFilter ?: $rw;
        if ($effectiveRw) {
            $query->whereHas('user', function($q) use ($effectiveRw) {
                $q->where('rw', $effectiveRw);
            });
        }
        
        if ($posyanduId) {
            $query->where('posyandu_id', $posyanduId);
        }
        
        $mothers = $query->latest()->paginate(15);
        $posyandus = Posyandu::all();
        
        return view('admin.kms-ibu', compact('mothers', 'posyandus', 'search', 'posyanduId', 'rw'))
            ->with('success_kode', $validasi['message']);
    }
    
    /**
     * Get data KMS ibu untuk modal edit (via AJAX)
     */
    public function kmsIbuGetData(Request $request)
    {
        $mother = Mother::with('posyandu')->find($request->mother_id);
        
        if (!$mother) {
            return response()->json(['success' => false, 'message' => 'Ibu tidak ditemukan'], 404);
        }
        
        // Get all health records for this mother
        $healthRecords = MotherHealthRecord::where('mother_id', $mother->id)
            ->orderBy('bulan_ke', 'asc')
            ->get();
        
        // Calculate current month of pregnancy
        $bulanKe = 1;
        if ($mother->tanggal_kehamiltonan) {
            $bulanKe = \Carbon\Carbon::parse($mother->tanggal_kehamiltonan)->diffInMonths(now()) + 1;
            $bulanKe = max(1, min(9, $bulanKe)); // clamp 1-9
        }
        
        // Check if already has record for current month
        $currentMonthRecord = $healthRecords->where('bulan_ke', $bulanKe)->first();
        
        return response()->json([
            'success' => true,
            'mother' => $mother,
            'health_records' => $healthRecords,
            'bulan_ke' => $bulanKe,
            'current_month_record' => $currentMonthRecord,
            'can_edit' => !$currentMonthRecord, // True if no record for this month yet
        ]);
    }
    
    /**
     * Simpan/Update data KMS ibu
     */
    public function kmsIbuStore(Request $request)
    {
        $request->validate([
            'mother_id' => 'required|exists:mothers,id',
            'bulan_ke' => 'required|integer|min:1|max:9',
            'berat_badan' => 'nullable|numeric|min:30|max:200',
            'lila' => 'nullable|numeric|min:15|max:40',
            'tekanan_darah' => 'nullable|string|max:10',
            'catatan' => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ]);
        
        $mother = Mother::find($request->mother_id);
        
        if (!$mother) {
            return response()->json(['success' => false, 'message' => 'Ibu tidak ditemukan'], 404);
        }
        
        $bulanKe = $request->bulan_ke;
        
        // Check if record already exists for this month (BLOCK if exists)
        $existingRecord = MotherHealthRecord::where('mother_id', $mother->id)
            ->where('bulan_ke', $bulanKe)
            ->first();
        
        if ($existingRecord) {
            return response()->json([
                'success' => false, 
                'message' => 'Data untuk bulan ke-' . $bulanKe . ' sudah ada. Tidak dapat menginput dua kali dalam bulan yang sama.'
            ], 422);
        }
        
        // Validate tekanan darah format (sistol/diastol)
        $tekananDarah = null;
        if ($request->tekanan_darah) {
            // Accept format like "120/80" or "120/80 mmHg"
            $tekananDarah = preg_replace('/[^0-9\/]/', '', $request->tekanan_darah);
        }
        
        // Create new record
        $record = MotherHealthRecord::create([
            'mother_id' => $mother->id,
            'bulan_ke' => $bulanKe,
            'berat_badan' => $request->berat_badan,
            'lila' => $request->lila,
            'tekanan_darah' => $tekananDarah,
            'catatan' => $request->catatan,
            'recorded_by' => Auth::id(),
            'recorded_at' => $request->recorded_at ?? now()->toDateString(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Data KMS ibu berhasil disimpan!',
            'record' => $record
        ]);
    }
    
/**
     * Get riwayat KMS ibu untuk ditampilkan di user dashboard
     */
    public function kmsIbuGetRiwayat(Request $request)
    {
        $mother = Mother::find($request->mother_id);
        
        if (!$mother) {
            return response()->json(['success' => false, 'message' => 'Ibu tidak ditemukan'], 404);
        }
        
        $records = MotherHealthRecord::where('mother_id', $mother->id)
            ->orderBy('bulan_ke', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }
    
// ========== KMS ANAK - ADMIN ==========
    /**
     * Halaman KMS Anak - Daftar anak dengan search dan filter RW/Posyandu
     */
    public function kmsAnak(Request $request)
    {
        $search = $request->search;
        $rw = $request->rw;
        $posyanduId = $request->posyandu_id;
        $kodeAkses = $request->kode_akses;
        
        // Validasi Kode Akses
        $validasi = $this->validasiKodeAkses($kodeAkses);
        
        if (!$validasi['valid']) {
            // Kode tidak valid, tampilkan form kosong
           $children = new LengthAwarePaginator([], 0, 15);
            $rwList = \DB::table('users')->whereNotNull('rw')->where('rw', '!=', '')->distinct()->pluck('rw');
            $posyandus = Posyandu::all();
            return view('admin.kms-anak', compact('children', 'rwList', 'posyandus', 'search', 'rw', 'posyanduId'))
                ->with('error_kode', $validasi['message']);
        }
        
        // Simpan kode ke session jika baru diverifikasi
        if ($kodeAkses && !Session::has('kode_akses')) {
            $this->simpanKodeSession(strtoupper(trim($kodeAkses)), $validasi['rw']);
        }
        
        // Filter RW berdasarkan kode yang diperbolehkan
        $rwFilter = $validasi['rw']; // null jika sipandu (semua RW)
        
        $query = Child::with('healthRecords', 'posyandu', 'user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }
        
        // Prioritaskan filter RW dari kode akses
        $effectiveRw = $rwFilter ?: $rw;
        if ($effectiveRw) {
            $query->whereHas('user', function($q) use ($effectiveRw) {
                $q->where('rw', $effectiveRw);
            });
        }
        
        if ($posyanduId) {
            $query->where('posyandu_id', $posyanduId);
        }
        
        $children = $query->latest()->paginate(15);
        
        // Ambil RW unik dari user
        $rwList = \DB::table('users')->whereNotNull('rw')->where('rw', '!=', '')->distinct()->pluck('rw');
        
        $posyandus = Posyandu::all();
        
        return view('admin.kms-anak', compact('children', 'rwList', 'posyandus', 'search', 'rw', 'posyanduId'))
            ->with('success_kode', $validasi['message']);
    }
    
    /**
     * Get data KMS anak untuk modal edit (via AJAX)
     */
    public function kmsAnakGetData(Request $request)
    {
        $child = Child::with('posyandu', 'user')->find($request->child_id);
        
        if (!$child) {
            return response()->json(['success' => false, 'message' => 'Anak tidak ditemukan'], 404);
        }
        
        // Hitung umur bulan
        $umurBulan = $child->tanggal_lahir ? \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths(now()) : 0;
        
        // Get all health records for this child
        $healthRecords = HealthRecord::where('child_id', $child->id)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Get latest record for current status
        $latestRecord = $healthRecords->first();
        
        return response()->json([
            'success' => true,
            'child' => $child,
            'umur_bulan' => $umurBulan,
            'health_records' => $healthRecords,
            'latest_record' => $latestRecord,
        ]);
    }
    
    /**
     * Simpan data KMS anak baru
     */
    public function kmsAnakStore(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:children,id',
            'berat' => 'required|numeric|min:0.5|max:100',
            'tinggi' => 'required|numeric|min:10|max:150',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        
        $child = Child::find($request->child_id);
        
        if (!$child) {
            return response()->json(['success' => false, 'message' => 'Anak tidak ditemukan'], 404);
        }
        
        // Hitung umur bulan pada saat pemeriksaan
        $umurBulan = \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths(\Carbon\Carbon::parse($request->tanggal));
        
        // Hitung status otomatis
        $record = new HealthRecord();
        $statusGizi = $record->calculateStatusGizi($request->berat, $umurBulan);
        $statusStunting = $record->calculateStatusStunting($request->tinggi, $umurBulan);
        
        $healthRecord = HealthRecord::create([
            'child_id' => $request->child_id,
            'berat' => $request->berat,
            'tinggi' => $request->tinggi,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'status_gizi' => $statusGizi,
            'status_stunting' => $statusStunting,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Data pemeriksaan berhasil disimpan!',
            'record' => $healthRecord
        ]);
    }
    
    /**
     * Update data KMS anak
     */
    public function kmsAnakUpdate(Request $request, HealthRecord $record)
    {
        $request->validate([
            'berat' => 'required|numeric|min:0.5|max:100',
            'tinggi' => 'required|numeric|min:20|max:150',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        
        $child = $record->child;
        
        // Hitung ulang umur bulan
        $umurBulan = \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths(\Carbon\Carbon::parse($request->tanggal));
        
        // Hitung status ulang
        $recordModel = new HealthRecord();
        $statusGizi = $recordModel->calculateStatusGizi($request->berat, $umurBulan);
        $statusStunting = $recordModel->calculateStatusStunting($request->tinggi, $umurBulan);
        
        $record->update([
            'berat' => $request->berat,
            'tinggi' => $request->tinggi,
            'tanggal' => $request->tanggal,
            'catatan' => $request->catatan,
            'status_gizi' => $statusGizi,
            'status_stunting' => $statusStunting,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Data pemeriksaan berhasil diperbarui!',
            'record' => $record
        ]);
    }
    
    /**
     * Hapus data KMS anak
     */
    public function kmsAnakDestroy(HealthRecord $record)
    {
        $record->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Data pemeriksaan berhasil dihapus!'
        ]);
    }
    
    /**
     * Get riwayat KMS anak untuk ditampilkan di user dashboard
     */
    public function kmsAnakGetRiwayat(Request $request)
    {
        $child = Child::find($request->child_id);
        
        if (!$child) {
            return response()->json(['success' => false, 'message' => 'Anak tidak ditemukan'], 404);
        }
        
        $records = HealthRecord::where('child_id', $child->id)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }
    
/**
     * Getgrafik pertumbuhan KMS anak per RW
     */
    public function kmsAnakGrafik(Request $request)
    {
        $rw = $request->rw;
        
        $query = Child::with('healthRecords', 'user');
        
        if ($rw) {
            $query->whereHas('user', function($q) use ($rw) {
                $q->where('rw', $rw);
            });
        }
        
        $children = $query->get();
        
        // Siapkan data untuk grafik
        $labels = [];
        $beratData = [];
        $tinggiData = [];
        
        foreach ($children as $child) {
            $labels[] = $child->nama;
            
            $latestRecord = $child->healthRecords->first();
            $beratData[] = $latestRecord ? $latestRecord->berat : 0;
            $tinggiData[] = $latestRecord ? $latestRecord->tinggi : 0;
        }
        
        return response()->json([
            'success' => true,
            'labels' => $labels,
            'berat_data' => $beratData,
            'tinggi_data' => $tinggiData,
        ]);
    }
    
// ========== LAPORAN POSYANDU ==========
    /**
     * Halaman Laporan Posyandu - Daftar data dengan search, filter, dan export
     */
    public function laporan(Request $request)
    {
        $search = $request->search;
        $type = $request->type ?? 'anak';
        $rw = $request->rw;
        $status = $request->status;
        $kodeAkses = $request->kode_akses;
        
        // Validasi Kode Akses
        $validasi = $this->validasiKodeAkses($kodeAkses);
        
        if (!$validasi['valid']) {
            $results = new LengthAwarePaginator([], 0, 15);
            // Hitung stats meskipun kode tidak valid
            $stats = [
                'total_anak' => Child::count(),
                'normal' => Child::whereHas('healthRecords', function($q) {
                    $q->where('status_gizi', 'BAIK');
                })->count(),
                'underweight' => Child::whereHas('healthRecords', function($q) {
                    $q->where('status_gizi', 'KURANG');
                })->count(),
                'stunting' => Child::whereHas('healthRecords', function($q) {
                    $q->where('status_stunting', 'STUNTING');
                })->count(),
            ];
            return view('admin.laporan', compact('results', 'type', 'search', 'rw', 'status', 'stats'))
                ->with('error_kode', $validasi['message']);
        }
        
        // Simpan kode ke session jika baru diverifikasi
        if ($kodeAkses && !Session::has('kode_akses')) {
            $this->simpanKodeSession(strtoupper(trim($kodeAkses)), $validasi['rw']);
        }
        
        // Filter RW berdasarkan kode yang diperbolehkan
        $rwFilter = $validasi['rw'];
        
// Hitung stats untuk diagram - gunakan filter RW yang sama
        $statsQuery = Child::with('healthRecords');
        if ($rwFilter) {
            $statsQuery->whereHas('user', function($q) use ($rwFilter) {
                $q->where('rw', $rwFilter);
            });
        }
        $allChildren = $statsQuery->get();
        
        $totalAnak = $allChildren->count();
        $normal = 0;
        $underweight = 0;
        $stunting = 0;
        
        foreach ($allChildren as $child) {
            if ($child->healthRecords->isNotEmpty()) {
                $latestRecord = $child->healthRecords->first();
                if ($latestRecord) {
                    if ($latestRecord->status_gizi === 'BAIK') {
                        $normal++;
                    } elseif ($latestRecord->status_gizi === 'KURANG') {
                        $underweight++;
                    }
                    if ($latestRecord->status_stunting === 'STUNTING') {
                        $stunting++;
                    }
                }
            }
        }
        
        // Stats tambahan seperti dashboard admin
        $totalJadwal = Schedule::count();
        $totalPosyandu = Posyandu::count();
        $totalKms = HealthRecord::count();
        
        $stats = [
            'total_anak' => $totalAnak,
            'total_jadwal' => $totalJadwal,
            'total_posyandu' => $totalPosyandu,
            'total_kms' => $totalKms,
            'normal' => $normal,
            'underweight' => $underweight,
            'stunting' => $stunting,
        ];
        
        if ($type === 'anak') {
            $query = Child::with('user', 'posyandu', 'healthRecords');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            
            // Filter RW
            $effectiveRw = $rwFilter ?: $rw;
            if ($effectiveRw) {
                $query->whereHas('user', function($q) use ($effectiveRw) {
                    $q->where('rw', $effectiveRw);
                });
            }
            
            // Filter status
            if ($status) {
                $query->where('status', $status);
            }
            
            $results = $query->latest()->paginate(15);
        } else {
            $query = Mother::with('user', 'posyandu');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            
            // Filter RW
            $effectiveRw = $rwFilter ?: $rw;
            if ($effectiveRw) {
                $query->whereHas('user', function($q) use ($effectiveRw) {
                    $q->where('rw', $effectiveRw);
                });
            }
            
            // Filter status
            if ($status) {
                $query->where('status', $status);
            }
            
            $results = $query->latest()->paginate(15);
        }
        
        return view('admin.laporan', compact('results', 'type', 'search', 'rw', 'status', 'stats'))
            ->with('success_kode', $validasi['message']);
    }
    
    /**
     * Get detail data untuk modal (via AJAX) -包含了 riwayat lengkap
     */
    public function laporanGetDetail(Request $request)
    {
        $id = $request->id;
        $type = $request->type ?? 'anak';
        
        if ($type === 'anak') {
            $child = Child::with('user', 'posyandu', 'healthRecords')->find($id);
            
            if (!$child) {
                return response()->json(['success' => false, 'message' => 'Anak tidak ditemukan'], 404);
            }
            
            // Ambil riwayat KMS
            $healthRecords = HealthRecord::where('child_id', $id)
                ->orderBy('tanggal', 'desc')
                ->get();
            
            // Ambil data imunisasi dan vitamin
            $imunisasi = [
                'HB0' => $child->imunisasi_hb0 ?? 'Belum',
                'BCG' => $child->imunisasi_bcg ?? 'Belum',
                'Polio 1' => $child->imunisasi_polio1 ?? 'Belum',
                'Polio 2' => $child->imunisasi_polio2 ?? 'Belum',
                'Polio 3' => $child->imunisasi_polio3 ?? 'Belum',
                'Polio 4' => $child->imunisasi_polio4 ?? 'Belum',
                'DPT-HB-Hib 1' => $child->imunisasi_dpt_hb_hib1 ?? 'Belum',
                'DPT-HB-Hib 2' => $child->imunisasi_dpt_hb_hib2 ?? 'Belum',
                'DPT-HB-Hib 3' => $child->imunisasi_dpt_hb_hib3 ?? 'Belum',
                'Campak' => $child->imunisasi_campak ?? 'Belum',
            ];
            
            $vitamin = [
                'Vitamin A (6-11 bulan)' => $child->vitamin_a_6_11 ?? 'Belum',
                'Vitamin A (12-59 bulan)' => $child->vitamin_a_12_59 ?? 'Belum',
            ];
            
            $html = view('admin.laporan-detail-anak', [
                'child' => $child,
                'healthRecords' => $healthRecords,
                'imunisasi' => $imunisasi,
                'vitamin' => $vitamin,
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
            ]);
        } else {
            $mother = Mother::with('user', 'posyandu')->find($id);
            
            if (!$mother) {
                return response()->json(['success' => false, 'message' => 'Ibu tidak ditemukan'], 404);
            }
            
            // Ambil riwayat kesehatan ibu
            $healthRecords = MotherHealthRecord::where('mother_id', $id)
                ->orderBy('bulan_ke', 'asc')
                ->get();
            
            // Ambil data TT (tablet tambah darah)
            $tt = [
                'TT 1' => $mother->tt1 ?? 'Belum',
                'TT 2' => $mother->tt2 ?? 'Belum',
                'TT 3' => $mother->tt3 ?? 'Belum',
                'TT 4' => $mother->tt4 ?? 'Belum',
                'TT 5' => $mother->tt5 ?? 'Belum',
            ];
            
            $html = view('admin.laporan-detail-ibu', [
                'mother' => $mother,
                'healthRecords' => $healthRecords,
                'tt' => $tt,
            ])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
            ]);
        }
    }
    
    /**
     * Export data laporan (PDF, Excel, CSV)
     */
    public function laporanExport(Request $request)
    {
        $selectedIds = $request->selected_ids;
        $exportType = $request->export_type;
        $type = $request->type ?? 'anak';
        
        if (!$selectedIds) {
            return back()->with('error', 'Pilih minimal satu data untuk dieksport!');
        }
        
        $ids = explode(',', $selectedIds);
        
        if ($type === 'anak') {
            $data = Child::with('user', 'posyandu', 'healthRecords')
                ->whereIn('id', $ids)
                ->get();
        } else {
            $data = Mother::with('user', 'posyandu')
                ->whereIn('id', $ids)
                ->get();
        }
        
        // Proses export berdasarkan tipe
        if ($exportType === 'Pdf') {
            $pdf = Pdf::loadView('admin.laporan-export-pdf', compact('data', 'type'));
            return $pdf->download('laporan-' . $type . '-' . date('Y-m-d') . '.pdf');
        } elseif ($exportType === 'Excel') {
            return Excel::download(new \App\Exports\LaporanExport($data, $type), 'laporan-' . $type . '-' . date('Y-m-d') . '.xlsx');
        } elseif ($exportType === 'csv') {
            return Excel::download(new \App\Exports\LaporanExport($data, $type), 'laporan-' . $type . '-' . date('Y-m-d') . '.csv');
        }
        
return back()->with('error', 'Tipe export tidak valid!');
    }
}
