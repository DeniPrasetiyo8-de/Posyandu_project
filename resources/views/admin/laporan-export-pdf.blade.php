<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<title>Laporan SIPANDU</title>

<style>
body{
    font-family: DejaVu Sans, sans-serif;
    font-size:11px;
}

h1,h2,h3{
    text-align:center;
    margin:0;
}

.info{
    margin-top:15px;
    margin-bottom:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:5px;
    text-align:left;
}

th{
    background:#eeeeee;
}

.footer{
    margin-top:20px;
}
</style>

</head>

<body>

<h2>SIPANDU</h2>
<h3>Sistem Informasi Posyandu</h3>
<h3>Laporan Data {{ strtoupper($type) }}</h3>

<div class="info">
Tanggal Cetak :
{{ date('d-m-Y H:i:s') }}
</div>

@if($type == 'anak')

<table>
<thead>
<tr>
<th>No</th>
<th>Nama Anak</th>
<th>NIK</th>
<th>Jenis Kelamin</th>
<th>Tanggal Lahir</th>
<th>Orang Tua</th>
<th>Posyandu</th>
<th>Status</th>
<th>BB Terakhir</th>
<th>TB Terakhir</th>
<th>Status Gizi</th>
<th>Status Stunting</th>
</tr>
</thead>

<tbody>

@foreach($data as $i => $item)

@php
$record = $item->healthRecords
->sortByDesc('tanggal')
->first();
@endphp

<tr>

<td>{{ $i+1 }}</td>

<td>{{ $item->nama ?? '-' }}</td>

<td>{{ $item->nik ?? '-' }}</td>

<td>{{ $item->jenis_kelamin ?? '-' }}</td>

<td>
{{ $item->tanggal_lahir
? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y')
: '-' }}
</td>

<td>{{ $item->user->name ?? '-' }}</td>

<td>{{ $item->posyandu->nama_posyandu ?? '-' }}</td>

<td>{{ $item->status ?? '-' }}</td>

<td>{{ $record->berat ?? '-' }}</td>

<td>{{ $record->tinggi ?? '-' }}</td>

<td>{{ $record->status_gizi ?? '-' }}</td>

<td>{{ $record->status_stunting ?? '-' }}</td>

</tr>

@endforeach

</tbody>
</table>

@else

<table>

<thead>
<tr>
<th>No</th>
<th>Nama Ibu</th>
<th>NIK</th>
<th>Tanggal Hamil</th>
<th>Berat Badan</th>
<th>Trimester</th>
<th>Status TT</th>
<th>Tablet Besi</th>
<th>Posyandu</th>
<th>Status</th>
</tr>
</thead>

<tbody>

@foreach($data as $i => $item)

<tr>

<td>{{ $i+1 }}</td>

<td>{{ $item->nama_lengkap ?? '-' }}</td>

<td>{{ $item->nik ?? '-' }}</td>

<td>
{{ $item->tgl_hamil
? \Carbon\Carbon::parse($item->tgl_hamil)->format('d-m-Y')
: '-' }}
</td>

<td>{{ $item->berat_badan ?? '-' }}</td>

<td>{{ $item->trimester_status ?? '-' }}</td>

<td>{{ $item->tt_status ?? '-' }}</td>

<td>{{ $item->iron_status ?? '-' }}</td>

<td>{{ $item->posyandu->nama_posyandu ?? '-' }}</td>

<td>{{ $item->status ?? '-' }}</td>

</tr>

@endforeach

</tbody>

</table>

@endif

<div class="footer">
Total Data : {{ count($data) }}
</div>

</body>
</html>
