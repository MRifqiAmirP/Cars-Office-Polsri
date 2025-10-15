<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getCars() {
        $carsModel = new \App\Models\Cars();
        return $carsModel->where('user_id', $this->attributes['id'])->first();
    }
}
