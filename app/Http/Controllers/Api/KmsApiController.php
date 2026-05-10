<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Mother;
use App\Models\HealthRecord;
use App\Models\Posyandu;

class KmsApiController extends Controller
{
    public function childGrowth(Child $child)
    {
        $records = HealthRecord::where('child_id', $child->id)
            ->orderBy('tanggal_pengukuran', 'asc')
            ->get();
        
        $data = $records->map(function($record) use ($child) {
            $ageMonths = $child->umur_bulan ?? 0;
            return [
                'id' => $record->id,
                'date' => $record->tanggal_pengukuran,
                'weight' => $record->berat_badan,
                'height' => $record->tinggi_badan,
                'z_score_bb' => 0,
                'z_score_tb' => 0,
                'category' => 'Gizi Baik',
                'age_months' => $ageMonths,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => [
                'records' => $data,
                'current_status' => [
                    'weight' => $child->berat_badan,
                    'height' => $child->tinggi_badan,
                    'last_check' => $records->first()->tanggal_pengukuran ?? null,
                ],
                'trend' => 'stabil',
                'recommendations' => ['Terus pantau pertumbuhan anak'],
            ]
        ]);
    }
    
    public function storeChildRecord(Request $request, Child $child)
    {
        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:0',
            'height_cm' => 'nullable|numeric|min:0',
            'recorded_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $record = HealthRecord::create([
            'child_id' => $child->id,
            'berat_badan' => $validated['weight_kg'],
            'tinggi_badan' => $validated['height_cm'] ?? null,
            'tanggal_pengukuran' => $validated['recorded_at'] ?? now()->toDateString(),
            'catatan' => $validated['notes'] ?? null,
        ]);
        
        $child->berat_badan = $validated['weight_kg'];
        if (!empty($validated['height_cm'])) {
            $child->tinggi_badan = $validated['height_cm'];
        }
        $child->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Data pengukuran berhasil ditambahkan',
            'data' => $record
        ], 201);
    }
    
    public function motherPregnancy(Mother $mother)
    {
        $ironStatus = json_decode($mother->iron_status ?? '{}', true);
        
        return response()->json([
            'success' => true,
            'data' => [
                'pregnancy_week' => $mother->calculatePregnancyWeek(),
                'estimated_due_date' => $mother->calculateDueDate(),
                'weight_gain_total' => 0,
                'recommended_gain' => 10,
                'status' => 'sesuai',
                'initial_weight' => $mother->berat_badan,
                'current_weight' => $mother->berat_badan,
                'iron_supplements' => [
                    'total_provided' => $ironStatus['total_provided'] ?? 0,
                    'target' => 90,
                ],
            ]
        ]);
    }
    
    public function posyanduAnalytics(Request $request, Posyandu $posyandu)
    {
        if (!$this->isAdmin($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin.'
            ], 403);
        }
        
        $children = Child::where('posyandu_id', $posyandu->id)->get();
        $mothers = Mother::where('posyandu_id', $posyandu->id)->get();
        $total = $children->count() ?: 1;
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_children' => $children->count(),
                'total_mothers' => $mothers->count(),
                'gizi_distribution' => [
                    ['status' => 'Gizi Buruk', 'count' => 0, 'percentage' => 0],
                    ['status' => 'Gizi Kurang', 'count' => 0, 'percentage' => 0],
                    ['status' => 'Gizi Baik', 'count' => $total, 'percentage' => 100],
                    ['status' => 'Gizi Lebih', 'count' => 0, 'percentage' => 0],
                ],
            ]
        ]);
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && 
               in_array($request->user()->role, ['admin', 'admin_posyandu']);
    }
}
