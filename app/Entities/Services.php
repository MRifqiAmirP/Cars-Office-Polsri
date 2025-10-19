<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Services extends Entity
{
    protected $attributes = [
        'id' => null,
        'kendaraan_id' => null,
        'bengkel_id' => null,
        'tanggal' => null,
        'speedometer_yang_lalu' => null,
        'speedometer_saat_ini' => null,
        'total_harga' => null,
        'foto_nota' => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $jenisPerawatanList;
    protected $mitraBengkel;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function getCars()
    {
        $modelCars = new \App\Models\Cars();
        return $modelCars->find($this->attributes['kendaraan_id']);
    }

    public function getMitraBengkel()
    {
        if (!empty($this->attributes['bengkel_id'])) {
            try {
                $mitraBengkelModel = new \App\Models\Bengkel();
                return $mitraBengkelModel->find($this->attributes['bengkel_id']);
            } catch (\Exception $e) {
                log_message('error', 'Error getting mitra bengkel: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

    public function getFotoNotaUrl()
    {
        if (empty($this->attributes['foto_nota'])) {
            return null;
        }

        return base_url('uploads/nota/' . $this->attributes['foto_nota']);
    }

    public function hasFotoNota()
    {
        return !empty($this->attributes['foto_nota']);
    }

    public function getNamaBengkel()
    {
        $bengkel = $this->getMitraBengkel();

        if ($bengkel) {
            if (is_object($bengkel)) {
                if (property_exists($bengkel, 'nama_bengkel')) {
                    return $bengkel->nama_bengkel;
                } elseif (isset($bengkel->nama_bengkel)) {
                    return $bengkel->nama_bengkel;
                }
            } elseif (is_array($bengkel) && isset($bengkel['nama_bengkel'])) {
                return $bengkel['nama_bengkel'];
            }
        }

        return 'Bengkel tidak ditemukan';
    }

    public function getMitraBengkelArray()
    {
        $bengkel = $this->getMitraBengkel();

        if (!$bengkel) {
            return null;
        }

        if (is_object($bengkel)) {
            if (method_exists($bengkel, 'toArray')) {
                return $bengkel->toArray();
            } else {
                return (array) $bengkel;
            }
        }

        return $bengkel;
    }

    public function getJarakTempuh()
    {
        if ($this->attributes['speedometer_yang_lalu'] && $this->attributes['speedometer_saat_ini']) {
            return $this->attributes['speedometer_saat_ini'] - $this->attributes['speedometer_yang_lalu'];
        }
        return 0;
    }

    public function getTotalHargaFormatted()
    {
        if ($this->attributes['total_harga']) {
            return 'Rp ' . number_format($this->attributes['total_harga'], 0, ',', '.');
        }
        return 'Rp 0';
    }

    public function getTanggalFormatted()
    {
        if ($this->attributes['tanggal']) {
            return date('d F Y', strtotime($this->attributes['tanggal']));
        }
        return '-';
    }

    public function getJenisPerawatanList()
    {
        if ($this->jenisPerawatanList === null) {
            $jenisPerawatanModel = model('App\Models\JenisPerawatan');
            $pivotModel = model('App\Models\ServiceJenisPerawatanPivot');

            $pivotData = $pivotModel->where('service_id', $this->attributes['id'])->findAll();
            $jenisPerawatanIds = array_column($pivotData, 'jenis_perawatan_id');

            $this->jenisPerawatanList = $jenisPerawatanModel
                ->select('id, jenis_perawatan')
                ->whereIn('id', $jenisPerawatanIds)
                ->findAll();
        }

        return $this->jenisPerawatanList;
    }

    public function toArrayWithRelations()
    {
        $data = $this->toArray();

        try {
            $data['kendaraan'] = $this->getCars();
            $data['jenis_perawatan'] = $this->getJenisPerawatanList();

            // Gunakan method yang aman untuk bengkel
            $data['mitra_bengkel'] = $this->getMitraBengkelArray(); // PASTI ARRAY
            $data['nama_bengkel'] = $this->getNamaBengkel(); // STRING
            $data['foto_nota_url'] = $this->getFotoNotaUrl();
            $data['has_foto_nota'] = $this->hasFotoNota();
            $data['jarak_tempuh'] = $this->getJarakTempuh();
            $data['total_harga_formatted'] = $this->getTotalHargaFormatted();
            $data['tanggal_formatted'] = $this->getTanggalFormatted();
        } catch (\Throwable $e) {
            // Fallback jika ada error
            log_message('error', 'Error in toArrayWithRelations: ' . $e->getMessage());
            $data['mitra_bengkel'] = null;
            $data['nama_bengkel'] = 'Error loading bengkel';
            $data['foto_nota_url'] = null;
            $data['has_foto_nota'] = false;
            $data['jarak_tempuh'] = 0;
            $data['total_harga_formatted'] = 'Rp 0';
            $data['tanggal_formatted'] = '-';
        }

        return $data;
    }
}
