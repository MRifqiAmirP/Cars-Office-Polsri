<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Services extends Entity
{
    protected $attributes = [
        'id' => null,
        'kendaraan_id' => null,
        'tanggal' => null,
        'speedometer_yang_lalu' => null,
        'speedometer_saat_ini' => null,
        'total_harga' => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $jenisPerawatanList;

    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];

    public function getCars()
    {
        $modelCars = new \App\Models\Cars();
        return $modelCars->find($this->attributes['kendaraan_id']);
    }

    public function getJenisPerawatanList()
    {
        if ($this->jenisPerawatanList === null) {
            $jenisPerawatanModel = model('App\Models\JenisPerawatan');
            $pivotModel = model('App\Models\ServiceJenisPerawatanPivot');

            $pivotData = $pivotModel->where('service_id', $this->attributes['id'])->findAll();

            $jenisPerawatanIds = array_column($pivotData, 'jenis_perawatan_id');

            $this->jenisPerawatanList = $jenisPerawatanModel
                ->select('id, jenis_perawatan')
                ->whereIn('id', $jenisPerawatanIds)
                ->findAll();
        }

        return $this->jenisPerawatanList;
    }
}
