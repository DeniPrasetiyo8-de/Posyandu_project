@extends('layouts.app')

@section('content')

<div class="p-6">
    <h1>Tambah Anak</h1>

    <form action="/children" method="POST">
        @csrf

        <input type="text" name="nama" placeholder="Nama"><br><br>

        <input type="date" name="tanggal_lahir"><br><br>

        <select name="jenis_kelamin">
            <option>Laki-laki</option>
            <option>Perempuan</option>
        </select><br><br>

        <select name="posyandu_id">
            @foreach($posyandus as $p)
                <option value="{{ $p->id }}">{{ $p->nama_posyandu }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Simpan</button>
    </form>
</div>

@endsection