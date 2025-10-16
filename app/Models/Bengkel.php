<?php

namespace App\Models;

use CodeIgniter\Model;

class Bengkel extends Model
{
    protected $table            = 'mitra_bengkel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Bengkel::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_bengkel',
        'alamat_bengkel',
        'telepon_bengkel',
        'email',
        'nama_kontak_bengkel',
        'telepon_kontak_bengkel',
        'latitude',
        'longitude',
        'file_siup',
        'file_situ',
        'file_perjanjian_kerjasama',
        'nilai_kelayakan',
        'status_kelayakan',
        'tanggal_penilaian',
        'keterangan_penilaian',
        'status_aktif',
        'created_at',
        'updated_at',
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
        'nama_bengkel' => 'required|min_length[3]|max_length[255]',
        'alamat_bengkel' => 'required|min_length[10]|max_length[500]',
        'telepon_bengkel' => 'permit_empty|min_length[6]|max_length[15]|regex_match[/^[0-9\-\+\s\(\)]+$/]',
        'email' => 'required|valid_email|max_length[100]',
        'nama_kontak_bengkel' => 'required|min_length[3]|max_length[100]',
        'telepon_kontak_bengkel' => 'required|min_length[6]|max_length[15]|regex_match[/^[0-9\-\+\s\(\)]+$/]',
        'latitude' => 'permit_empty|decimal',
        'longitude' => 'permit_empty|decimal',
        'file_siup' => 'permit_empty|max_length[255]',
        'file_situ' => 'permit_empty|max_length[255]',
        'file_perjanjian_kerjasama' => 'permit_empty|max_length[255]',
        'nilai_kelayakan' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'status_kelayakan' => 'permit_empty|in_list[lulus,ditolak,proses]',
        'tanggal_penilaian' => 'permit_empty|valid_date',
        'keterangan_penilaian' => 'permit_empty|max_length[1000]',
        'status_aktif' => 'permit_empty|in_list[aktif,nonaktif,proses]',
    ];

    protected $validationMessages = [
        'nama_bengkel' => [
            'required' => 'Nama bengkel harus diisi.',
            'min_length' => 'Nama bengkel minimal 3 karakter.',
            'max_length' => 'Nama bengkel maksimal 255 karakter.'
        ],
        'alamat_bengkel' => [
            'required' => 'Alamat bengkel harus diisi.',
            'min_length' => 'Alamat bengkel minimal 10 karakter.',
            'max_length' => 'Alamat bengkel maksimal 500 karakter.'
        ],
        'telepon_bengkel' => [
            'min_length' => 'Telepon bengkel minimal 6 karakter.',
            'max_length' => 'Telepon bengkel maksimal 15 karakter.',
            'regex_match' => 'Format telepon bengkel tidak valid.'
        ],
        'email' => [
            'required' => 'Email bengkel wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'max_length' => 'Email maksimal 100 karakter.'
        ],
        'nama_kontak_bengkel' => [
            'required' => 'Nama kontak bengkel harus diisi.',
            'min_length' => 'Nama kontak bengkel minimal 3 karakter.',
            'max_length' => 'Nama kontak bengkel maksimal 100 karakter.'
        ],
        'telepon_kontak_bengkel' => [
            'required' => 'Telepon kontak bengkel harus diisi.',
            'min_length' => 'Telepon kontak bengkel minimal 6 karakter.',
            'max_length' => 'Telepon kontak bengkel maksimal 15 karakter.',
            'regex_match' => 'Format telepon kontak bengkel tidak valid.'
        ],
        'latitude' => [
            'decimal' => 'Latitude harus berupa angka desimal.'
        ],
        'longitude' => [
            'decimal' => 'Longitude harus berupa angka desimal.'
        ],
        'file_siup' => [
            'max_length' => 'Nama file SIUP maksimal 255 karakter.'
        ],
        'file_situ' => [
            'max_length' => 'Nama file SITU maksimal 255 karakter.'
        ],
        'file_perjanjian_kerjasama' => [
            'max_length' => 'Nama file perjanjian kerjasama maksimal 255 karakter.'
        ],
        'nilai_kelayakan' => [
            'numeric' => 'Nilai kelayakan harus berupa angka.',
            'greater_than_equal_to' => 'Nilai kelayakan minimal 0.',
            'less_than_equal_to' => 'Nilai kelayakan maksimal 100.'
        ],
        'status_kelayakan' => [
            'in_list' => 'Status kelayakan harus salah satu dari: lulus, ditolak, proses.'
        ],
        'tanggal_penilaian' => [
            'valid_date' => 'Format tanggal penilaian tidak valid.'
        ],
        'keterangan_penilaian' => [
            'max_length' => 'Keterangan penilaian maksimal 1000 karakter.'
        ],
        'status_aktif' => [
            'in_list' => 'Status aktif harus salah satu dari: aktif, nonaktif, proses.'
        ],
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

    public function getWithServiceCount()
    {
        return $this->select('
                bengkel.*,
                COUNT(service_request.id) AS total_service
            ')
            ->join('service_request', 'service_request.bengkel_id = bengkel.id', 'left')
            ->groupBy('bengkel.id')
            ->findAll();
    }

    public function getServices($bengkelId)
    {
        $serviceRequestModel = new \App\Models\ServiceRequest();
        return $serviceRequestModel->where('bengkel_id', $bengkelId)->findAll();
    }
}
