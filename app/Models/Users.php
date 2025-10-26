<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\User::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "nip",
        "nama",
        "email",
        "no_handphone",
        "jabatan",
        "role",
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
        'nip' => 'required|max_length[20]',
        'nama' => 'required',
        'email' => 'permit_empty|valid_email',
        'no_handphone' => 'permit_empty|max_length[15]',
        'jabatan' => 'required',
        'role' => 'required|in_list[superuser,admin,user,ppk,wadir]',
        'password' => 'required|min_length[6]'
    ];
    protected $validationMessages   = [
        'nip' => [
            'required' => 'NIP harus diisi',
            'max_length' => 'NIP maksimal 20 karakter'
        ],
        'nama' => [
            'required' => 'Nama harus diisi',
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
        ],
        'no_handphone' => [
            'max_length' => 'Nomor handphone maksimal 15 digit'
        ],
        'jabatan' => [
            'required' => 'Jabatan harus diisi',
        ],
        'role' => [
            'required' => 'Role harus diisi',
            'in_list' => 'Role yang dipilih tidak valid'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ]
    ];
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
                    users.role,
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
                    users.role,
                    users.created_at,
                    users.updated_at,
                    cars.nopol,
                    cars.foto_kendaraan,
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

    public function getRoleOptions()
    {
        return [
            'superuser' => 'Super User',
            'admin' => 'Administrator',
            'user' => 'Dosen',
            'ppk' => 'PPK',
            'wadir' => 'Wakil Direktur 2'
        ];
    }

    public function getUserNameOnly() {
        return $this->select('users.id, users.nama')
                    ->findAll();
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
