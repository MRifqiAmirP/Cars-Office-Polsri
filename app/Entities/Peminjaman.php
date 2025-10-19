<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\Cars as CarsModel;

class Peminjaman extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'start_date', 'end_date'];
    protected $casts   = [
        'id'         => 'integer',
        'car_id'     => 'integer',
        'nama'       => 'string',
        'nip'        => 'string',
        'jabatan'    => 'string',
        'file'       => 'string',
    ];

    public function getCar()
    {
        $carsModel = new CarsModel();
        return $carsModel->find($this->attributes['car_id']);
    }

    public function isActive(): bool
    {
        $now = date('Y-m-d H:i:s');
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function isUpcoming(): bool
    {
        $now = date('Y-m-d H:i:s');
        return $this->start_date > $now;
    }

    public function isCompleted(): bool
    {
        $now = date('Y-m-d H:i:s');
        return $this->end_date < $now;
    }

    public function getDuration(): int
    {
        $start = strtotime($this->start_date);
        $end = strtotime($this->end_date);
        $diff = $end - $start;
        return ceil($diff / (60 * 60 * 24));
    }
}
