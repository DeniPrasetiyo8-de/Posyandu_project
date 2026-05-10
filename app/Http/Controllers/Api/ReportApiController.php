<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    public function index(Request $request)
    {
        $reports = array(
            array(
                'id' => 1,
                'title' => 'Cek Kehamilan',
                'content' => 'Pemeriksaan kehamilan meliputi tekanan darah, berat badan...',
                'photo_urls' => array(asset('images/G Cek Kehamilan.jpg')),
                'activity_date' => '2024-10-01',
                'posyandu_id' => 1,
            ),
            array(
                'id' => 2,
                'title' => 'Pemberian Vitamin',
                'content' => 'Pemberian vitamin A untuk ibu nifas dan balita...',
                'photo_urls' => array(asset('images/G Pemberian Vitamin.jpg')),
                'activity_date' => '2024-10-15',
                'posyandu_id' => 1,
            ),
            array(
                'id' => 3,
                'title' => 'Imunisasi Anak',
                'content' => 'Imunisasi BCG, Polio, Campak, DPT-HB-Hib...',
                'photo_urls' => array(asset('images/G Imunisasi Anak.jpg')),
                'activity_date' => '2024-10-20',
                'posyandu_id' => 1,
            ),
        );
        
        if ($request->has('posyandu_id')) {
            $reports = array_filter($reports, function($r) use ($request) {
                return $r['posyandu_id'] == $request->posyandu_id;
            });
        }
        
        return response()->json(array('success' => true, 'data' => array_values($reports)));
    }

    public function show($id)
    {
        return response()->json(array('success' => true, 'data' => array('id' => $id, 'title' => 'Laporan')));
    }

    public function store(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        return response()->json(array('success' => true, 'message' => 'Laporan berhasil ditambahkan'), 201);
    }

    public function update(Request $request, $id)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        return response()->json(array('success' => true, 'message' => 'Laporan berhasil diperbarui'));
    }

    public function destroy(Request $request, $id)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        return response()->json(array('success' => true, 'message' => 'Laporan berhasil dihapus'));
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && in_array($request->user()->role, array('admin', 'admin_posyandu'));
    }
}
