<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Posyandu;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    // 🔍 LIST DATA
    public function index()
    {
        $children = Child::with('posyandu')->get();
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
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'posyandu_id' => 'required',
        ]);

        Child::create([
            'user_id' => Auth::id(),
            'posyandu_id' => $request->posyandu_id,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect('/children')->with('success','Data berhasil ditambahkan');
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

        $child->update($request->all());

        return redirect('/children')->with('success','Data berhasil diupdate');
    }

    // ❌ DELETE
    public function destroy($id)
    {
        Child::destroy($id);
        return redirect('/children')->with('success','Data berhasil dihapus');
    }
}