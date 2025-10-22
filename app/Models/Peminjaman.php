<?php

namespace App\Models;

use CodeIgniter\Model;

class Peminjaman extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Peminjaman::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['car_id', 'nama', 'nip', 'jabatan', 'start_date', 'end_date', 'file'];

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
    protected $validationRules = [
        'car_id'    => 'required|integer|is_not_unique[cars.id]',
        'nama'      => 'required|max_length[50]',
        'nip'       => 'required|max_length[20]',
        'jabatan'   => 'required|max_length[50]',
        'start_date' => 'required|valid_date',
        'end_date'   => 'required|valid_date'
    ];
    protected $validationMessages   = [
        'car_id' => [
            'is_not_unique' => "Mobil tidak ada"
        ]
    ];
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

    public function withCars()
    {
        return $this->select('peminjaman.*, cars.nama as car_name, cars.plat_nomor, cars.merk, cars.tipe')
            ->join('cars', 'cars.id = peminjaman.car_id');
    }

    public function isCarAvailable(int $carId, string $startDate, string $endDate, ?int $excludePeminjamanId = null): bool
    {
        $query = $this->where('car_id', $carId)
            ->groupStart()
            ->groupStart()
            ->where('start_date <=', $startDate)
            ->where('end_date >=', $startDate)
            ->groupEnd()
            ->orGroupStart()
            ->where('start_date <=', $endDate)
            ->where('end_date >=', $endDate)
            ->groupEnd()
            ->orGroupStart()
            ->where('start_date >=', $startDate)
            ->where('end_date <=', $endDate)
            ->groupEnd()
            ->groupEnd();

        if ($excludePeminjamanId) {
            $query->where('id !=', $excludePeminjamanId);
        }

        return $query->countAllResults() === 0;
    }
}
