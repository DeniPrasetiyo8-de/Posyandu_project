<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Posyandu;

class ScheduleApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with('posyandu');
        
        if ($request->has('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
        
        if ($request->has('rw')) {
            $query->where('rw', $request->rw);
        }
        
        $schedules = $query->orderBy('tanggal', 'asc')->get();
        
        return response()->json(array(
            'success' => true,
            'data' => $schedules
        ));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load('posyandu');
        
        return response()->json(array(
            'success' => true,
            'data' => $schedule
        ));
    }

    public function store(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array(
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat menambahkan jadwal.'
            ), 403);
        }
        
        $validated = $request->validate(array(
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'rw' => 'nullable|string|max:10',
        ));
        
        $schedule = Schedule::create($validated);
        
        return response()->json(array(
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $schedule
        ), 201);
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array(
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat mengubah jadwal.'
            ), 403);
        }
        
        $validated = $request->validate(array(
            'kegiatan' => 'required|string|max:255',
            'tanggal' => 'sometimes|date',
            'description' => 'nullable|string',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'rw' => 'nullable|string|max:10',
        ));
        
        $schedule->update($validated);
        
        return response()->json(array(
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $schedule
        ));
    }

    public function destroy(Request $request, Schedule $schedule)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array(
                'success' => false,
                'message' => 'Akses ditolak. Hanya admin yang dapat menghapus jadwal.'
            ), 403);
        }
        
        $schedule->delete();
        
        return response()->json(array(
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ));
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && in_array($request->user()->role, array('admin', 'admin_posyandu'));
    }
}
