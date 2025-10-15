<?php

namespace App\Models;

use CodeIgniter\Model;

class Services extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Services::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kendaraan_id',
        'tanggal',
        'speedometer_yang_lalu',
        'speedometer_saat_ini',
        'total_harga',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'total_harga' => 'float'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'kendaraan_id' => 'required|integer|is_not_unique[cars.id]',
        'tanggal' => 'required|valid_date[Y-m-d]',
        'speedometer_yang_lalu' => 'integer|greater_than_equal_to[0]',
        'speedometer_saat_ini' => 'integer',
        'total_harga' => 'required|decimal|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'kendaraan_id' => [
            'required' => 'ID kendaraan wajib diisi.',
            'integer' => 'ID kendaraan harus berupa angka.',
            'is_not_unique' => 'Kendaraan yang dipilih tidak ditemukan dalam database.'
        ],
        'tanggal' => [
            'required' => 'Tanggal service wajib diisi.',
            'valid_date' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.'
        ],
        'speedometer_yang_lalu' => [
            'integer' => 'Speedometer yang lalu harus berupa angka.',
            'greater_than_equal_to' => 'Speedometer yang lalu tidak boleh bernilai negatif.'
        ],
        'speedometer_saat_ini' => [
            'integer' => 'Speedometer saat ini harus berupa angka.'
        ],
        'total_harga' => [
            'required' => 'Total harga wajib diisi.',
            'decimal' => 'Total harga harus berupa angka desimal.',
            'greater_than_equal_to' => 'Total harga tidak boleh negatif.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['formatCurrency'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['formatCurrency'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function formatCurrency(array $data)
    {
        if (isset($data['data']['total_harga'])) {
            $data['data']['total_harga'] = preg_replace('/[^0-9.]/', '', $data['data']['total_harga']);
        }
        return $data;
    }
}
