<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mother;

class MotherApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Mother::with(['posyandu', 'user']);
        
        if ($request->has('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $mothers = $query->get()->map(function($mother) {
            $pregnancyWeek = $mother->calculatePregnancyWeek();
            $trimester = $mother->getPregnancyStatus($pregnancyWeek);
            
            return [
                'id' => $mother->id,
                'nik' => $mother->nik,
                'full_name' => $mother->nama,
                'pregnancy_date' => $mother->tgl_hamil,
                'estimated_due_date' => $mother->calculateDueDate(),
                'pregnancy_week' => $pregnancyWeek,
                'trimester' => $trimester,
                'weight_kg' => $mother->berat_badan,
                'posyandu_id' => $mother->posyandu_id,
                'posyandu_name' => $mother->posyandu->nama_posyandu ?? null,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $mothers
        ]);
    }

    public function show(Mother $mother)
    {
        $mother->load(['posyandu', 'user']);
        
        $pregnancyWeek = $mother->calculatePregnancyWeek();
        $trimester = $mother->getPregnancyStatus($pregnancyWeek);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mother->id,
                'nik' => $mother->nik,
                'full_name' => $mother->nama,
                'pregnancy_date' => $mother->tgl_hamil,
                'estimated_due_date' => $mother->calculateDueDate(),
                'pregnancy_week' => $pregnancyWeek,
                'trimester' => $trimester,
                'weight_kg' => $mother->berat_badan,
                'posyandu_id' => $mother->posyandu_id,
                'posyandu_name' => $mother->posyandu->nama_posyandu ?? null,
                'user_name' => $mother->user->name ?? null,
                'tt_status' => json_decode($mother->tt_status ?? '{}', true),
                'trimester_status' => json_decode($mother->trimester_status ?? '{}', true),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'nik' => 'nullable|string|min:16|max:16',
            'full_name' => 'required|string|max:255',
            'pregnancy_date' => 'required|date|before_or_equal:today',
            'weight_kg' => 'nullable|numeric|min:0',
            'posyandu_id' => 'nullable|exists:posyandus,id',
        ]);
        
        $mother = Mother::create([
            'user_id' => $user->id,
            'nik' => $validated['nik'] ?? null,
            'nama' => $validated['full_name'],
            'tgl_hamil' => $validated['pregnancy_date'],
            'berat_badan' => $validated['weight_kg'] ?? null,
            'posyandu_id' => $validated['posyandu_id'] ?? null,
            'jenis_kelamin' => 'Perempuan',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Data ibu hamil berhasil ditambahkan',
            'data' => ['id' => $mother->id, 'full_name' => $mother->nama]
        ], 201);
    }

    public function update(Request $request, Mother $mother)
    {
        if (!$this->isAdmin($request) && $mother->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses'
            ], 403);
        }
        
        $validated = $request->validate([
            'nik' => 'sometimes|string|min:16|max:16',
            'full_name' => 'sometimes|string|max:255',
            'pregnancy_date' => 'sometimes|date',
            'weight_kg' => 'nullable|numeric|min:0',
            'posyandu_id' => 'nullable|exists:posyandus,id',
        ]);
        
        if (isset($validated['full_name'])) {
            $validated['nama'] = $validated['full_name'];
            unset($validated['full_name']);
        }
        
        if (isset($validated['pregnancy_date'])) {
            $validated['tgl_hamil'] = $validated['pregnancy_date'];
            unset($validated['pregnancy_date']);
        }
        
        $mother->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Data ibu hamil berhasil diperbarui',
            'data' => $mother
        ]);
    }

    public function destroy(Request $request, Mother $mother)
    {
        if (!$this->isAdmin($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }
        
        $mother->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Data ibu hamil berhasil dihapus'
        ]);
    }
    
    public function pregnancy(Mother $mother)
    {
        $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true);
        
        $data = collect([
            ['week_number' => 1, 'name' => 'Minggu 1-4', 'description' => 'Pembuahan dan implantasi'],
            ['week_number' => 2, 'name' => 'Minggu 5-8', 'description' => 'Organogenesis'],
            ['week_number' => 3, 'name' => 'Minggu 9-12', 'description' => ' Trimester 1'],
            ['week_number' => 4, 'name' => 'Minggu 13-16', 'description' => ' Trimester 2'],
            ['week_number' => 5, 'name' => 'Minggu 17-20', 'description' => 'Gerakan bayi terasa'],
            ['week_number' => 6, 'name' => 'Minggu 21-24', 'description' => ' Trimester 3'],
            ['week_number' => 7, 'name' => 'Minggu 25-28', 'description' => 'Pertumbuhan pesat'],
            ['week_number' => 8, 'name' => 'Minggu 29-32', 'description' => 'Persiapan kelahiran'],
            ['week_number' => 9, 'name' => 'Minggu 33-36', 'description' => 'Matangnya organ'],
            ['week_number' => 10, 'name' => 'Minggu 37-40', 'description' => 'Persalinan'],
        ])->map(function($trim) use ($trimesterStatus) {
            $key = (string) $trim['week_number'];
            $record = $trimesterStatus[$key] ?? null;
            
            return [
                'week_number' => $trim['week_number'],
                'name' => $trim['name'],
                'description' => $trim['description'],
                'status' => $record['status'] ?? 'pending',
                'checked_at' => $record['checked_at'] ?? null,
                'notes' => $record['notes'] ?? null,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function storePregnancy(Request $request, Mother $mother)
    {
        $validated = $request->validate([
            'week_number' => 'required|integer|min:1|max:10',
            'status' => 'nullable|in:pending,checked,completed',
            'checked_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true);
        $key = (string) $validated['week_number'];
        
        $trimesterStatus[$key] = [
            'status' => $validated['status'] ?? 'checked',
            'checked_at' => $validated['checked_at'] ?? now()->toDateString(),
            'notes' => $validated['notes'] ?? null,
        ];
        
        $mother->trimester_status = json_encode($trimesterStatus);
        $mother->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status kehamilan berhasil dicatat'
        ]);
    }
    
    public function ironSupplements(Mother $mother)
    {
        $ironStatus = json_decode($mother->iron_status ?? '{}', true);
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_provided' => $ironStatus['total_provided'] ?? 0,
                'records' => $ironStatus['records'] ?? []
            ]
        ]);
    }
    
    public function storeIronSupplement(Request $request, Mother $mother)
    {
        $validated = $request->validate([
            'given_at' => 'nullable|date',
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        
        $ironStatus = json_decode($mother->iron_status ?? '{}', true);
        
        $records = $ironStatus['records'] ?? [];
        $records[] = [
            'given_at' => $validated['given_at'] ?? now()->toDateString(),
            'quantity' => $validated['quantity'] ?? 1,
            'notes' => $validated['notes'] ?? null,
        ];
        
        $ironStatus['records'] = $records;
        $ironStatus['total_provided'] = count($records);
        
        $mother->iron_status = json_encode($ironStatus);
        $mother->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Tablet tambah darah berhasil dicatat'
        ]);
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && 
               in_array($request->user()->role, ['admin', 'admin_posyandu']);
    }
}
