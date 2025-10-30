<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceRequest extends Model
{
    protected $table            = 'service_request';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\ServiceRequest::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'kendaraan_id',
        'bengkel_id',
        'keluhan',
        'status',
        'total_harga',
        'file',
        'foto_nota',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getFullData()
    {
        return $this->select('
                service_request.*,
                users.nama AS nama_user,
                cars.nama_kendaraan AS nama_kendaraan,
                bengkel.nama_bengkel AS nama_bengkel
            ')
            ->join('users', 'users.id = service_request.user_id', 'left')
            ->join('cars', 'cars.id = service_request.kendaraan_id', 'left')
            ->join('bengkel', 'bengkel.id = service_request.bengkel_id', 'left')
            ->findAll();
    }

    public function getByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function getByCar($carId)
    {
        return $this->where('kendaraan_id', $carId)->findAll();
    }

    public function getByBengkel($bengkelId)
    {
        return $this->where('bengkel_id', $bengkelId)->findAll();
    }
}
