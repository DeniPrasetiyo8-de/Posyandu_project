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
}
