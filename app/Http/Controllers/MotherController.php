<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mother;
use App\Models\Posyandu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MotherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

// List Data Ibu
    public function index()
    {
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('dashboard.informasi-ibu', compact('mothers'));
    }

    // List Data Ibu (for mothers route)
    public function listIndex()
    {
        $mothers = Auth::user()->mothers()->with('posyandu')->get();
        return view('mothers.index', compact('mothers'));
    }

    // Form Tambah
    public function create()
    {
        $posyandus = Posyandu::all();
        return view('mothers.create', compact('posyandus'));
    }

    // Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'nullable|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_kehamilan' => 'required|date|before_or_equal:today',
            'berat_badan' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posyandu_id' => 'nullable|exists:posyandus,id',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => 'P',
            'tanggal_kehamilan' => $request->tanggal_kehamilan,
            'berat_badan' => $request->berat_badan,
            'posyandu_id' => $request->posyandu_id ?? null,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('mothers', 'public');
        }

        Mother::create($data);

        return redirect('/mothers')->with('success', 'Data ibu berhasil ditambahkan!');
    }

    // Form Edit
    public function edit($id)
    {
        $mother = Mother::findOrFail($id);
        $posyandus = Posyandu::all();
        return view('mothers.edit', compact('mother', 'posyandus'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $mother = Mother::findOrFail($id);

        $data = $request->only(['nik', 'nama_lengkap', 'tanggal_kehamilan', 'berat_badan', 'posyandu_id']);

        if ($request->hasFile('foto')) {
            if ($mother->foto) {
                Storage::disk('public')->delete($mother->foto);
            }
            $data['foto'] = $request->file('foto')->store('mothers', 'public');
        }

        $mother->update($data);

        return redirect('/mothers')->with('success', 'Data ibu berhasil diperbarui!');
    }

    // Hapus Data
    public function destroy($id)
    {
        $mother = Mother::findOrFail($id);
        
        if ($mother->foto) {
            Storage::disk('public')->delete($mother->foto);
        }
        
        $mother->delete();
        
        return redirect('/mothers')->with('success', 'Data ibu berhasil dihapus!');
    }
}
