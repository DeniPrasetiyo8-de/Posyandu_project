<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function collection()
    {
        $rows = collect();

        if ($this->type === 'anak') {

            foreach ($this->data as $item) {

                $record = $item->healthRecords
                    ->sortByDesc('tanggal')
                    ->first();

                $rows->push([
                    'Nama Anak' => $item->nama ?? '-',
                    'NIK' => $item->nik ?? '-',
                    'Jenis Kelamin' => $item->jenis_kelamin ?? '-',
                    'Tanggal Lahir' => $item->tanggal_lahir ?? '-',
                    'Orang Tua' => $item->user->name ?? '-',
                    'Posyandu' => $item->posyandu->nama_posyandu ?? '-',
                    'Status' => $item->status ?? '-',
                    'BB Terakhir' => $record->berat ?? '-',
                    'TB Terakhir' => $record->tinggi ?? '-',
                    'Status Gizi' => $record->status_gizi ?? '-',
                    'Status Stunting' => $record->status_stunting ?? '-',
                ]);
            }

        } else {

            foreach ($this->data as $item) {

                $rows->push([
                    'Nama Ibu' => $item->nama_lengkap ?? '-',
                    'NIK' => $item->nik ?? '-',
                    'Tanggal Hamil' => $item->tgl_hamil ?? '-',
                    'Berat Badan' => $item->berat_badan ?? '-',
                    'Trimester' => $item->trimester_status ?? '-',
                    'Status TT' => $item->tt_status ?? '-',
                    'Tablet Besi' => $item->iron_status ?? '-',
                    'Posyandu' => $item->posyandu->nama_posyandu ?? '-',
                    'Status' => $item->status ?? '-',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        if ($this->type === 'anak') {
            return [
                'Nama Anak',
                'NIK',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Orang Tua',
                'Posyandu',
                'Status',
                'BB Terakhir',
                'TB Terakhir',
                'Status Gizi',
                'Status Stunting'
            ];
        }

        return [
            'Nama Ibu',
            'NIK',
            'Tanggal Hamil',
            'Berat Badan',
            'Trimester',
            'Status TT',
            'Tablet Besi',
            'Posyandu',
            'Status'
        ];
    }
}
