<?php

namespace App\Models;

use CodeIgniter\Model;

class Cars extends Model
{
    protected $table            = 'cars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Cars::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'foto_kendaraan',
        'nopol',
        'merk',
        'type',
        'no_bpkb',
        'no_mesin',
        'no_rangka',
        'tahun_pembuatan',
        'keterangan'
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
    protected $validationRules = [
        'nopol'           => 'required|max_length[12]',
        'foto_kendaraan'  => 'permit_empty|max_size[foto_kendaraan,5120]|is_image[foto_kendaraan]',
        'merk'            => 'required|max_length[50]',
        'type'            => 'required|max_length[50]',
        'no_bpkb'         => 'permit_empty|max_length[50]',
        'no_mesin'        => 'permit_empty|max_length[50]',
        'no_rangka'       => 'permit_empty|max_length[50]',
        'tahun_pembuatan' => 'required|integer',
        'keterangan'      => 'permit_empty|max_length[255]',
        'user_id'         => 'required|integer'
    ];

    protected $validationMessages = [
        'nopol' => [
            'required'    => 'Nomor polisi wajib diisi',
            'max_length'  => 'Nomor polisi maksimal 15 karakter',
        ],
        'foto_kendaraan' => [
            'max_size' => 'Ukuran file foto maksimal 5MB',
            'is_image' => 'File harus berupa gambar (JPG, PNG, GIF)'
        ],
        'merk' => [
            'required'    => 'Merk kendaraan wajib diisi',
        ],
        'type' => [
            'required'    => 'Tipe kendaraan wajib diisi',
        ],
        'tahun_pembuatan' => [
            'required'          => 'Tahun pembuatan wajib diisi',
            'integer'           => 'Tahun harus berupa angka',
            'greater_than'      => 'Tahun tidak boleh kurang dari 1900',
            'less_than_equal_to' => 'Tahun tidak boleh lebih dari 2100',
        ],
        'user_id' => [
            'required'    => 'User pemilik kendaraan wajib diisi',
            'integer'     => 'User ID harus berupa angka',
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

    public function getCarWithUser($carId)
    {
        return $this->select('cars.*, users.nama as user_nama, users.nip as user_nip')
                    ->join('users', 'users.id = cars.user_id')
                    ->where('cars.id', $carId)
                    ->first();
    }

    public function getAllCarsWithUser()
    {
        return $this->select('cars.*, users.nama as user_nama, users.jabatan')
                    ->join('users', 'users.id = cars.user_id')
                    ->findAll();
    }
}
