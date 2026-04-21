@extends('layouts.app')

@section('content')

<div class="p-6">
    <h1>Edit Anak</h1>

    <form action="/children/{{ $child->id }}" method="POST">
        @csrf
        @method('PUT')

        <input type="text" name="nama" value="{{ $child->nama }}"><br><br>

        <input type="date" name="tanggal_lahir" value="{{ $child->tanggal_lahir }}"><br><br>

        <select name="jenis_kelamin">
            <option {{ $child->jenis_kelamin=='Laki-laki'?'selected':'' }}>Laki-laki</option>
            <option {{ $child->jenis_kelamin=='Perempuan'?'selected':'' }}>Perempuan</option>
        </select><br><br>

        <select name="posyandu_id">
            @foreach($posyandus as $p)
                <option value="{{ $p->id }}" {{ $child->posyandu_id==$p->id?'selected':'' }}>
                    {{ $p->nama_posyandu }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</div>

@endsection