<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use \App\Models\ServiceRequest as ServiceRequestModel;
use \App\Models\Cars as CarModel;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getCars()
    {
        $carModel = new CarModel();
        return $carModel->where('user_id', $this->attributes['id'])->findAll();
    }

    public function getServiceRequests()
    {
        $serviceRequestModel = new ServiceRequestModel();
        return $serviceRequestModel->where('user_id', $this->attributes['id'])->findAll();
    }

    public function getAllServices()
    {
        $carModel = new CarModel();
        $serviceRequestModel = new ServiceRequestModel();

        $cars = $carModel->where('user_id', $this->attributes['id'])->findAll();

        if (empty($cars)) {
            return [];
        }

        $carIds = array_column($cars, 'id');

        return $serviceRequestModel->whereIn('kendaraan_id', $carIds)->findAll();
    }

    public function getFullName()
    {
        return ucfirst($this->attributes['nama'] ?? '');
    }
}
