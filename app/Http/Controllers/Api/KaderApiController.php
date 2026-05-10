<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Posyandu;

class KaderApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Posyandu::query();
        
        if ($request->has('posyandu_id')) {
            $query->where('id', $request->posyandu_id);
        }
        
        $cadres = $query->get()->map(function($posyandu) {
            return array(
                'id' => $posyandu->id,
                'name' => $posyandu->nama_kader ?? 'Kader ' . $posyandu->nama_posyandu,
                'posyandu_id' => $posyandu->id,
                'posyandu_name' => $posyandu->nama_posyandu,
                'position' => $posyandu->jabatan ?? 'Kader Aktif',
                'is_present' => (bool) ($posyandu->is_present ?? false),
                'photo_url' => $posyandu->foto,
            );
        });
        
        return response()->json(array(
            'success' => true,
            'data' => $cadres
        ));
    }

    public function show(Posyandu $cadre)
    {
        return response()->json(array(
            'success' => true,
            'data' => array(
                'id' => $cadre->id,
                'name' => $cadre->nama_kader ?? 'Kader',
                'posyandu_id' => $cadre->id,
                'posyandu_name' => $cadre->nama_posyandu,
                'position' => $cadre->jabatan ?? 'Kader Aktif',
                'is_present' => (bool) $cadre->is_present,
                'photo_url' => $cadre->foto,
            )
        ));
    }

    public function store(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        $validated = $request->validate(array(
            'name' => 'required|string|max:255',
            'posyandu_id' => 'required|exists:posyandus,id',
            'position' => 'nullable|string|max:100',
        ));
        
        $posyandu = Posyandu::find($validated['posyandu_id']);
        $posyandu->nama_kader = $validated['name'];
        $posyandu->jabatan = $validated['position'] ?? 'Kader Aktif';
        $posyandu->save();
        
        return response()->json(array(
            'success' => true,
            'message' => 'Kader berhasil ditambahkan'
        ), 201);
    }

    public function update(Request $request, Posyandu $cadre)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        $validated = $request->validate(array(
            'name' => 'sometimes|string|max:255',
            'position' => 'nullable|string|max:100',
        ));
        
        if (isset($validated['name'])) {
            $cadre->nama_kader = $validated['name'];
        }
        if (isset($validated['position'])) {
            $cadre->jabatan = $validated['position'];
        }
        $cadre->save();
        
        return response()->json(array(
            'success' => true,
            'message' => 'Kader berhasil diperbarui'
        ));
    }

    public function updateStatus(Request $request, Posyandu $cadre)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        $validated = $request->validate(array(
            'is_present' => 'required|boolean',
        ));
        
        $cadre->is_present = $validated['is_present'];
        $cadre->save();
        
        return response()->json(array(
            'success' => true,
            'message' => 'Status kehadiran berhasil diperbarui'
        ));
    }

    public function destroy(Request $request, Posyandu $cadre)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        $cadre->nama_kader = null;
        $cadre->jabatan = null;
        $cadre->save();
        
        return response()->json(array(
            'success' => true,
            'message' => 'Kader berhasil dihapus'
        ));
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && in_array($request->user()->role, array('admin', 'admin_posyandu'));
    }
}
