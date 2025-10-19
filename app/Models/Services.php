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
        'bengkel_id',
        'tanggal',
        'speedometer_yang_lalu',
        'speedometer_saat_ini',
        'total_harga',
        'foto_nota',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'total_harga' => '?float',
        'bengkel_id' => '?integer'
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
        'bengkel_id' => 'permit_empty|integer|is_not_unique[mitra_bengkel.id]',
        'tanggal' => 'required|valid_date[Y-m-d]',
        'speedometer_yang_lalu' => 'integer|greater_than_equal_to[0]',
        'speedometer_saat_ini' => 'integer',
        'total_harga' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'foto_nota' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'kendaraan_id' => [
            'required' => 'ID kendaraan wajib diisi.',
            'integer' => 'ID kendaraan harus berupa angka.',
            'is_not_unique' => 'Kendaraan yang dipilih tidak ditemukan dalam database.'
        ],
        'bengkel_id' => [
            'integer' => 'ID bengkel harus berupa angka.',
            'is_not_unique' => 'Bengkel yang dipilih tidak ditemukan dalam database.'
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
            'decimal' => 'Total harga harus berupa angka desimal.',
            'greater_than_equal_to' => 'Total harga tidak boleh negatif.'
        ],
        'foto_nota' => [
            'max_length' => 'Nama file foto nota tidak boleh lebih dari 255 karakter.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['formatCurrency', 'validateSpeedometer'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['formatCurrency', 'validateSpeedometer'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function formatCurrency(array $data)
    {
        if (isset($data['data']['total_harga']) && !empty($data['data']['total_harga'])) {
            $data['data']['total_harga'] = preg_replace('/[^0-9.]/', '', $data['data']['total_harga']);
        }
        return $data;
    }

    public function getServiceWithRelations($id = null)
    {
        if ($id) {
            $service = $this->find($id);
            return $service ? $service->toArrayWithRelations() : null;
        }

        $services = $this->findAll();
        return array_map(fn($s) => $s->toArrayWithRelations(), $services);
    }

    protected function validateSpeedometer(array $data)
    {
        if (isset($data['data']['speedometer_yang_lalu']) && isset($data['data']['speedometer_saat_ini'])) {
            $speedometerLalu = $data['data']['speedometer_yang_lalu'];
            $speedometerSaatIni = $data['data']['speedometer_saat_ini'];

            if ($speedometerLalu && $speedometerSaatIni && $speedometerSaatIni <= $speedometerLalu) {
                throw new \RuntimeException('Speedometer saat ini harus lebih besar dari speedometer yang lalu.');
            }
        }
        return $data;
    }

    public function getServiceWithBengkel($id = null)
    {
        $builder = $this->db->table('services s')
            ->select('s.*, mb.nama_bengkel, mb.alamat as alamat_bengkel, mb.telepon as telepon_bengkel, c.nama_kendaraan, c.plat_nomor')
            ->join('mitra_bengkel mb', 'mb.id = s.bengkel_id', 'left')
            ->join('cars c', 'c.id = s.kendaraan_id', 'left');

        if ($id) {
            return $builder->where('s.id', $id)->get()->getRow();
        }

        return $builder->get()->getResult();
    }

    public function getRekapBulanan($bulan, $tahun)
    {
        return $this->db->table('services s')
            ->select('s.*, mb.nama_bengkel, c.nama_kendaraan, c.plat_nomor')
            ->join('mitra_bengkel mb', 'mb.id = s.bengkel_id', 'left')
            ->join('cars c', 'c.id = s.kendaraan_id', 'left')
            ->where('MONTH(s.tanggal)', $bulan)
            ->where('YEAR(s.tanggal)', $tahun)
            ->orderBy('s.tanggal', 'DESC')
            ->get()
            ->getResult();
    }

    public function getTotalBiayaBulanan($bulan, $tahun)
    {
        $result = $this->db->table('services')
            ->selectSum('total_harga', 'total_biaya')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->get()
            ->getRow();

        return $result->total_biaya ?? 0;
    }

    public function updateFotoNota($serviceId, $filename)
    {
        return $this->update($serviceId, ['foto_nota' => $filename]);
    }

    public function getServiceByBengkel($bengkelId, $limit = null)
    {
        $builder = $this->where('bengkel_id', $bengkelId)
            ->orderBy('tanggal', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getStatistikService($tahun = null)
    {
        $tahun = $tahun ?? date('Y');

        return $this->db->table('services')
            ->select('MONTH(tanggal) as bulan, COUNT(*) as total_service, SUM(total_harga) as total_biaya')
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('MONTH(tanggal)')
            ->orderBy('bulan', 'ASC')
            ->get()
            ->getResult();
    }
}
