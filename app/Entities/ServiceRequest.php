<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use \App\Models\Users as UsersModel;
use \App\Models\Cars as CarsModel;
use \App\Models\Bengkel as BengkelModel;

class ServiceRequest extends Entity
{
    protected $attributes = [
        'id' => null,
        'user_id' => null,
        'kendaraan_id' => null,
        'bengkel_id' => null,
        'keluhan' => null,
        'status' => 'pending',
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'bengkel_id',
        'keluhan',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'kendaraan_id' => 'integer',
        'bengkel_id' => 'integer',
        'keluhan' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getUser()
    {
        if (empty($this->attributes['user_id'])) {
            return null;
        }

        $userModel = new UsersModel();
        return $userModel->find($this->attributes['user_id']);
    }

    public function getCar()
    {
        if (empty($this->attributes['kendaraan_id'])) {
            return null;
        }

        $carModel = new CarsModel();
        return $carModel->find($this->attributes['kendaraan_id']);
    }

    public function getBengkel()
    {
        if (empty($this->attributes['bengkel_id'])) {
            return null;
        }

        $bengkelModel = new BengkelModel();
        return $bengkelModel->find($this->attributes['bengkel_id']);
    }

    public function getBengkelNama()
    {
        $bengkel = $this->getBengkel();
        return $bengkel ? $bengkel->nama_bengkel : '-';
    }

    public function getStatusLabel()
    {
        switch ($this->attributes['status']) {
            case 'pending':
                return 'Menunggu Konfirmasi';
            case 'proses':
                return 'Sedang Dikerjakan';
            case 'selesai':
                return 'Selesai';
            default:
                return 'Tidak Diketahui';
        }
    }

    public function setKeluhan(string $keluhan)
    {
        $this->attributes['keluhan'] = ucfirst($keluhan);
        return $this;
    }
}
