@extends('layouts.app')

@section('content')

<div class="p-6">
    <h1 class="text-2xl mb-4">Data Anak</h1>

    <a href="/children/create" class="bg-blue-500 px-4 py-2 text-white rounded">
        Tambah Anak
    </a>

    <table class="w-full mt-4 border">
        <tr>
            <th>Nama</th>
            <th>Posyandu</th>
            <th>Aksi</th>
        </tr>

        @foreach($children as $c)
        <tr>
            <td>{{ $c->nama }}</td>
            <td>{{ $c->posyandu->nama_posyandu }}</td>
            <td>
                <a href="/children/{{ $c->id }}/edit">Edit</a>

                <form action="/children/{{ $c->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>

@endsection