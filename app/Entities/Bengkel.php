<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Bengkel extends Entity
{
    protected $attributes = [
        'id' => null,
        'nama_bengkel' => null,
        'alamat_bengkel' => null,
        'telepon_bengkel' => null,
        'email' => null,
        'nama_kontak_bengkel' => null,
        'telepon_kontak_bengkel' => null,
        'latitude' => null,
        'longitude' => null,
        'file_siup' => null,
        'file_situ' => null,
        'file_perjanjian_kerjasama' => null,
        'nilai_kelayakan' => null,
        'status_kelayakan' => 'proses_penilaian',
        'tanggal_penilaian' => null,
        'keterangan_penilaian' => null,
        'status_aktif' => 'aktif',
        'created_at' => null,
        'updated_at' => null,
    ];
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'nilai_kelayakan' => 'float',
    ];

    public function getStatusKelayakanText()
    {
        switch ($this->attributes['status_kelayakan']) {
            case 'layak':
                return 'Layak';
            case 'tidak_layak':
                return 'Tidak Layak';
            default:
                return 'Dalam Proses Penilaian';
        }
    }

    public function setNamaBengkel(string $nama)
    {
        $this->attributes['nama_bengkel'] = ucwords(strtolower($nama));
        return $this;
    }
}
