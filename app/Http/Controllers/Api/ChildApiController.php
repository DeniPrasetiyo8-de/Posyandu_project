<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\HealthRecord;
use App\Models\Posyandu;

class ChildApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Child::with(['posyandu', 'user']);
        
        if ($request->has('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }
        
        $children = $query->get()->map(function($child) {
            return [
                'id' => $child->id,
                'nik' => $child->nik,
                'full_name' => $child->nama,
                'gender' => $child->jenis_kelamin,
                'birth_date' => $child->tanggal_lahir,
                'age_months' => $child->umur_bulan,
                'photo_url' => $child->foto_url,
                'posyandu_id' => $child->posyandu_id,
                'posyandu_name' => $child->posyandu->nama_posyandu ?? null,
                'user_name' => $child->user->name ?? null,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $children
        ]);
    }

    public function show(Child $child)
    {
        $child->load(['posyandu', 'user', 'healthRecords']);
        
        $latestRecords = $child->healthRecords()->latest()->take(5)->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $child->id,
                'nik' => $child->nik,
                'full_name' => $child->nama,
                'gender' => $child->jenis_kelamin,
                'birth_date' => $child->tanggal_lahir,
                'age_months' => $child->umur_bulan,
                'weight_kg' => $child->berat_badan,
                'height_cm' => $child->tinggi_badan,
                'photo_url' => $child->foto_url,
                'posyandu_id' => $child->posyandu_id,
                'posyandu_name' => $child->posyandu->nama_posyandu ?? null,
                'user_id' => $child->user_id,
                'user_name' => $child->user->name ?? null,
                'imunisasi_status' => json_decode($child->imunisasi_status ?? '{}', true),
                'vitamin_status' => json_decode($child->vitamin_status ?? '{}', true),
                'growth_records' => $latestRecords->map(function($record) {
                    return [
                        'id' => $record->id,
                        'weight' => $record->berat_badan,
                        'height' => $record->tinggi_badan,
                        'recorded_at' => $record->tanggal_pengukuran,
                        'notes' => $record->catatan,
                    ];
                }),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'nik' => 'nullable|string|min:16|max:16',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date|before_or_equal:today',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'weight_kg' => 'nullable|numeric|min:0',
            'height_cm' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        if (!empty($validated['nik']) && !empty($validated['posyandu_id'])) {
            $exists = Child::where('nik', $validated['nik'])
                ->where('posyandu_id', $validated['posyandu_id'])
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIK sudah terdaftar di posyandu ini'
                ], 422);
            }
        }
        
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('children', 'public');
            $photoUrl = asset('storage/' . $path);
        }
        
        $child = Child::create([
            'user_id' => $user->id,
            'nik' => $validated['nik'] ?? null,
            'nama' => $validated['full_name'],
            'jenis_kelamin' => $validated['gender'],
            'tanggal_lahir' => $validated['birth_date'],
            'posyandu_id' => $validated['posyandu_id'] ?? null,
            'berat_badan' => $validated['weight_kg'] ?? null,
            'tinggi_badan' => $validated['height_cm'] ?? null,
            'foto' => $photoUrl,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil ditambahkan',
            'data' => [
                'id' => $child->id,
                'full_name' => $child->nama,
            ]
        ], 201);
    }

    public function update(Request $request, Child $child)
    {
        if (!$this->isAdmin($request) && $child->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah data ini'
            ], 403);
        }
        
        $validated = $request->validate([
            'nik' => 'sometimes|string|min:16|max:16',
            'full_name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:Laki-laki,Perempuan',
            'birth_date' => 'sometimes|date',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'weight_kg' => 'nullable|numeric|min:0',
            'height_cm' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('children', 'public');
            $validated['foto'] = asset('storage/' . $path);
        }
        
        if (isset($validated['full_name'])) {
            $validated['nama'] = $validated['full_name'];
            unset($validated['full_name']);
        }
        
        if (isset($validated['gender'])) {
            $validated['jenis_kelamin'] = $validated['gender'];
            unset($validated['gender']);
        }
        
        $child->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil diperbarui',
            'data' => $child
        ]);
    }

    public function destroy(Request $request, Child $child)
    {
        if (!$this->isAdmin($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat menghapus data anak.'
            ], 403);
        }
        
        $child->healthRecords()->delete();
        $child->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil dihapus'
        ]);
    }
    
    public function immunizations(Child $child)
    {
        $imunisasiStatus = json_decode($child->imunisasi_status ?? '{}', true);
        $schedule = Child::getDaftarImunisasi();
        $today = now()->toDateString();
        
        $data = collect($schedule)->map(function($label, $key) use ($imunisasiStatus, $today) {
            $record = $imunisasiStatus[$key] ?? null;
            
            $status = 'terjadwal';
            if (!empty($record['given_at'])) {
                $status = 'selesai';
            } elseif (!empty($record['target_date']) && $record['target_date'] < $today) {
                $status = 'terlewat';
            }
            
            return [
                'vaccine_name' => $label,
                'status' => $status,
                'given_at' => $record['given_at'] ?? null,
                'next_schedule' => $record['next_schedule'] ?? null,
                'notes' => $record['notes'] ?? null,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function storeImmunization(Request $request, Child $child)
    {
        $validated = $request->validate([
            'vaccine_key' => 'required|string',
            'given_at' => 'nullable|date',
            'next_schedule' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:selesai,terjadwal,terlewat',
        ]);
        
        $imunisasiStatus = json_decode($child->imunisasi_status ?? '{}', true);
        
        $imunisasiStatus[$validated['vaccine_key']] = [
            'given_at' => $validated['given_at'] ?? now()->toDateString(),
            'next_schedule' => $validated['next_schedule'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'selesai',
        ];
        
        $child->imunisasi_status = json_encode($imunisasiStatus);
        $child->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Imunisasi berhasil dicatat'
        ]);
    }
    
    public function updateImmunization(Request $request, Child $child)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }
        
        return $this->storeImmunization($request, $child);
    }
    
    public function destroyImmunization(Request $request, Child $child)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }
        
        $validated = $request->validate([
            'vaccine_key' => 'required|string',
        ]);
        
        $imunisasiStatus = json_decode($child->imunisasi_status ?? '{}', true);
        unset($imunisasiStatus[$validated['vaccine_key']]);
        
        $child->imunisasi_status = json_encode($imunisasiStatus);
        $child->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Imunisasi berhasil dihapus'
        ]);
    }
    
    public function vitamins(Child $child)
    {
        $vitaminStatus = json_decode($child->vitamin_status ?? '{}', true);
        $schedule = Child::getDaftarVitamin();
        
        $data = collect($schedule)->map(function($label, $key) use ($vitaminStatus) {
            $record = $vitaminStatus[$key] ?? null;
            
            return [
                'vitamin_type' => $label,
                'given_at' => $record['given_at'] ?? null,
                'notes' => $record['notes'] ?? null,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function storeVitamin(Request $request, Child $child)
    {
        $validated = $request->validate([
            'vitamin_key' => 'required|string',
            'given_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $vitaminStatus = json_decode($child->vitamin_status ?? '{}', true);
        
        $vitaminStatus[$validated['vitamin_key']] = [
            'given_at' => $validated['given_at'] ?? now()->toDateString(),
            'notes' => $validated['notes'] ?? null,
        ];
        
        $child->vitamin_status = json_encode($vitaminStatus);
        $child->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Vitamin berhasil dicatat'
        ]);
    }
    
    public function immunizationSchedule()
    {
        $schedule = [
            ['vaccine_name' => 'Hepatitis B', 'target_age' => '0 bulan', 'doses' => [0, 1, 6]],
            ['vaccine_name' => 'BCG', 'target_age' => '0 bulan', 'doses' => [0]],
            ['vaccine_name' => 'Polio', 'target_age' => '2 bulan', 'doses' => [0, 2, 4, 6]],
            ['vaccine_name' => 'DPT-HB-Hib', 'target_age' => '2 bulan', 'doses' => [2, 3, 4]],
            ['vaccine_name' => 'PCV', 'target_age' => '2 bulan', 'doses' => [2, 4, 6]],
            ['vaccine_name' => 'Rotavirus', 'target_age' => '2 bulan', 'doses' => [2, 4, 6]],
            ['vaccine_name' => 'Campak-Rubella', 'target_age' => '9 bulan', 'doses' => [9]],
        ];
        
        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && 
               in_array($request->user()->role, ['admin', 'admin_posyandu']);
    }
}
