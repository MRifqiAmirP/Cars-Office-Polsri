<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Cars extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getUser() {
        $userModel = new \App\Models\Users();
        return $userModel->find($this->attributes['user_id']);
    }

    public function getServices() {
        $servicesModel = new \App\Models\Services();
        return $servicesModel->where('kendaraan_id', $this->attributes['id'])->findAll();
    }
}
