<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use \App\Models\ServiceRequest as ServiceRequestModel;
use \App\Models\Bengkel as BengkelModel;

class Cars extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getUser()
    {
        $userModel = new \App\Models\Users();
        return $userModel->find($this->attributes['user_id']);
    }

    public function getServices()
    {
        $servicesModel = new \App\Models\Services();
        return $servicesModel->where('kendaraan_id', $this->attributes['id'])->findAll();
    }

    public function getServiceRequests()
    {
        $serviceRequestModel = new ServiceRequestModel();
        return $serviceRequestModel->where('kendaraan_id', $this->attributes['id'])->findAll();
    }

    public function getBengkels()
    {
        $serviceRequestModel = new ServiceRequestModel();
        $bengkelModel = new BengkelModel();

        $serviceRequests = $serviceRequestModel
            ->select('bengkel_id')
            ->where('kendaraan_id', $this->attributes['id'])
            ->findAll();

        $bengkelIds = array_column($serviceRequests, 'bengkel_id');
        $bengkelIds = array_unique(array_filter($bengkelIds));

        if (empty($bengkelIds)) {
            return [];
        }

        return $bengkelModel->whereIn('id', $bengkelIds)->findAll();
    }

    public function getFotoUrl()
    {
        if ($this->foto_kendaraan) {
            return base_url('uploads/cars/' . $this->foto_kendaraan);
        }
        return null;
    }

    public function isDeleted()
    {
        return $this->deleted_at !== null;
    }
    
    public function getDeletedAtFormatted()
    {
        return $this->deleted_at ? $this->deleted_at->format('d/m/Y H:i') : null;
    }

    public function getPeminjaman() {
        $peminjamanModel =  new \App\Models\Peminjaman();
        return $peminjamanModel->where('car_id', $this->attributes['id'])->findAll();
    }
}
