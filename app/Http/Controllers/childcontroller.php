<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Posyandu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 🔍 LIST DATA (user-specific)
    public function index()
    {
        $children = Auth::user()->children()->with('posyandu')->get();
        return view('children.index', compact('children'));
    }

    // ➕ FORM TAMBAH
    public function create()
    {
        $posyandus = Posyandu::all();
        return view('children.create', compact('posyandus'));
    }

    // 💾 SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posyandu_id' => 'required|exists:posyandus,id',
        ]);

        $data = [
            'user_id' => Auth::id(),
'posyandu_id' => $request->posyandu_id ?? null,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('children', 'public');
        }

        Child::create($data);

        return redirect('/children')->with('success','Data anak berhasil ditambahkan!');
    }

    // ✏️ FORM EDIT
    public function edit($id)
    {
        $child = Child::findOrFail($id);
        $posyandus = Posyandu::all();
        return view('children.edit', compact('child','posyandus'));
    }

    // 🔄 UPDATE
    public function update(Request $request, $id)
    {
        $child = Child::findOrFail($id);

        $data = $request->only(['nama', 'tanggal_lahir', 'jenis_kelamin', 'berat_badan', 'tinggi_badan', 'posyandu_id']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($child->foto) {
                Storage::disk('public')->delete($child->foto);
            }
            $data['foto'] = $request->file('foto')->store('children', 'public');
        }

        $child->update($data);

        return redirect('/children')->with('success','Data anak berhasil diperbarui!');
    }

    // ❌ DELETE
    public function destroy($id)
    {
        Child::destroy($id);
        return redirect('/children')->with('success','Data berhasil dihapus');
    }
}