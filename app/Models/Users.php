<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Cars::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "nip",
        "nama",
        "email",
        "no_handphone",
        "jabatan",
        "password"
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
    protected $validationRules      = [
        'nip' => 'required|is_unique[users.nip,id,{id}]',
        'nama' => 'required',
        'email' => 'permit_empty|valid_email|is_unique[users.email,id,{id}]',
        'no_handphone' => 'permit_empty|is_unique[users.no_handphone,id,{id}]',
        'jabatan' => 'required',
        'password' => 'required|min_length[6]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getUsersWithCars()
    {
        return $this->select('
                    users.id,
                    users.nip,
                    users.nama,
                    users.email,
                    users.no_handphone,
                    users.jabatan,
                    users.password,
                    users.created_at,
                    users.updated_at,
                    cars.id as car_id,
                    cars.nopol,
                    cars.merk,
                    cars.type,
                    cars.no_bpkb,
                    cars.no_mesin,
                    cars.no_rangka,
                    cars.tahun_pembuatan,
                    cars.keterangan
                ')
            ->join('cars', 'cars.user_id = users.id', 'left')
            ->findAll();
    }

    public function getUserIdWithCars($id)
    {
        return $this->select('
                    users.id,
                    users.nip,
                    users.nama,
                    users.email,
                    users.no_handphone,
                    users.jabatan,
                    users.password,
                    users.created_at,
                    users.updated_at,
                    cars.id as car_id,
                    cars.nopol,
                    cars.merk,
                    cars.type,
                    cars.no_bpkb,
                    cars.no_mesin,
                    cars.no_rangka,
                    cars.tahun_pembuatan,
                    cars.keterangan
                ')
            ->join('cars', 'cars.user_id = users.id', 'left')
            ->where('users.id', $id)
            ->first();
    }

    // protected function hashPassword(array $data)
    // {
    //     if (isset($data['data']['password'])) {
    //         if (password_get_info($data['data']['password'])['algo'] === 0) {
    //             $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
    //         }
    //     }
    //     return $data;
    // }
}
