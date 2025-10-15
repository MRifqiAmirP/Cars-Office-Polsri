<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JenisPerawatan extends Entity
{
    protected $attributes = [
        'id' => null,
        'jenis_perawatan' => null,
    ];
    protected $services;
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getServices()
    {
        if ($this->services === null) {
            $serviceModel = model('App\Models\ServiceModel');
            $pivotModel = model('App\Models\ServiceJenisPerawatanPivotModel');

            $pivotData = $pivotModel->where('jenis_perawatan_id', $this->attributes['id'])->findAll();

            $serviceIds = array_column($pivotData, 'service_id');

            $this->services = $serviceModel
                ->whereIn('id', $serviceIds)
                ->findAll();
        }

        return $this->services;
    }
}
