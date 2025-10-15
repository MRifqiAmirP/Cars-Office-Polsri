<?php

namespace App\Controllers\API;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use \App\Controllers\BaseController;
use \App\Models\Services as ServicesModel;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;
use \App\Entities\Services as ServicesEntities;

class Service extends BaseController
{
    protected $services;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function __construct()
    {
        $this->services = new ServicesModel();
    }

    public function index()
    {
        try {
            $services = $this->services->findAll();

            if (empty($services)) {
                return responseError("Tidak ada data services", 400);
            }

            $data = [];
            foreach ($services as $service) {
                $data[] = [
                    'service' => $service,
                    'jenis_perawatan' => $service->getJenisPerawatanList()
                ];
            }

            return responseSuccess("Data service berhasil diambil", $data);
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        try {
            $service = $this->services->find($id);

            if (empty($service)) {
                return responseError("Data services tidak ditemukan", 404);
            }

            $data = [
                'service' => $service,
                'jenis_perawatan' => $service->getJenisPerawatanList()
            ];

            return responseSuccess("Data service berhasil diambil", $data);
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        try {
            $data = $this->request->getPost();

            if (!$data) {
                return responseError("Tidak ada data yang dikirim", 400);
            }

            if ($data['speedometer_saat_ini'] < $data['speedometer_yang_lalu']) {
                return responseError('Speedometer saat ini tidak boleh lebih kecil dari speedometer yang lalu.', 400);
            }

            $jenisPerawatanIds = [];
            if (!empty($data['jenis_perawatan'])) {
                if (is_string($data['jenis_perawatan'])) {
                    $jenisPerawatanIds = array_map('trim', explode(',', $data['jenis_perawatan']));
                } elseif (is_array($data['jenis_perawatan'])) {
                    $jenisPerawatanIds = $data['jenis_perawatan'];
                }
            }

            unset($data['jenis_perawatan']);

            $serviceModel = new \App\Models\Services();
            $pivotModel   = new \App\Models\ServiceJenisPerawatanPivot();
            $jenisModel   = new \App\Models\JenisPerawatan();

            if (!empty($jenisPerawatanIds)) {
                $foundJenis = $jenisModel
                    ->whereIn('id', $jenisPerawatanIds)
                    ->findAll();

                $foundIds = array_column($foundJenis, 'id');
                $missing = array_diff($jenisPerawatanIds, $foundIds);

                if (!empty($missing)) {
                    return responseError(
                        'Beberapa jenis perawatan tidak ditemukan di database.',
                        400,
                        ['jenis_perawatan_tidak_ditemukan' => array_values($missing)]
                    );
                }
            }

            $serviceId = $serviceModel->insert($data, true);

            if (!$serviceId) {
                return responseError("Gagal menambahkan service", 400, $serviceModel->errors());
            }

            foreach ($jenisPerawatanIds as $jenisId) {
                $pivotModel->insert([
                    'service_id' => $serviceId,
                    'jenis_perawatan_id' => $jenisId
                ]);
            }

            $service = $serviceModel->find($serviceId);

            return responseSuccess("Service berhasil ditambahkan", [
                'service' => $service,
                'jenis_perawatan' => $jenisPerawatanIds
            ]);
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        try {
            $serviceModel = new \App\Models\Services();
            $pivotModel = new \App\Models\ServiceJenisPerawatanPivot();

            $data = $this->request->getPost();

            if (!$data) {
                return responseError("Tidak ada data yang dikirim", 400);
            }

            $service = $serviceModel->find($id);
            if (!$service) {
                return responseError("Data service tidak ditemukan", 404);
            }

            if (isset($data['speedometer_saat_ini']) && isset($data['speedometer_yang_lalu'])) {
                if ($data['speedometer_saat_ini'] < $data['speedometer_yang_lalu']) {
                    return responseError('Speedometer saat ini tidak boleh lebih kecil dari speedometer yang lalu.', 400);
                }
            }

            $jenisPerawatanIds = [];
            if (isset($data['jenis_perawatan'])) {
                if (is_string($data['jenis_perawatan'])) {
                    $jenisPerawatanIds = array_map('trim', explode(',', $data['jenis_perawatan']));
                    $jenisPerawatanIds = array_filter($jenisPerawatanIds);
                } elseif (is_array($data['jenis_perawatan'])) {
                    $jenisPerawatanIds = array_filter($data['jenis_perawatan']);
                }
            }
            unset($data['jenis_perawatan']);

            if (!$serviceModel->update($id, $data)) {
                return responseError("Gagal memperbarui service", 400, $serviceModel->errors());
            }

            $pivotModel->where('service_id', $id)->delete();

            if (!empty($jenisPerawatanIds)) {
                $jenisPerawatanIds = array_values(array_filter($jenisPerawatanIds));

                if (!empty($jenisPerawatanIds)) {
                    $jenisModel = new \App\Models\JenisPerawatan();
                    $foundJenis = $jenisModel
                        ->whereIn('id', $jenisPerawatanIds)
                        ->findAll();

                    $foundIds = array_column($foundJenis, 'id');
                    $missing = array_diff($jenisPerawatanIds, $foundIds);

                    if (!empty($missing)) {
                        return responseError(
                            'Beberapa jenis perawatan tidak ditemukan di database.',
                            400,
                            ['jenis_perawatan_tidak_ditemukan' => array_values($missing)]
                        );
                    }

                    $insertData = [];
                    foreach ($jenisPerawatanIds as $jpId) {
                        $insertData[] = [
                            'service_id' => $id,
                            'jenis_perawatan_id' => $jpId
                        ];
                    }
                    $pivotModel->insertBatch($insertData);
                }
            }

            $updatedService = $serviceModel->find($id);

            $jenisPerawatanList = [];
            if (!empty($jenisPerawatanIds)) {
                $jenisModel = new \App\Models\JenisPerawatan();
                $jenisPerawatanList = $jenisModel->whereIn('id', $jenisPerawatanIds)->findAll();
            }

            return responseSuccess("Data service berhasil diperbarui", [
                'service' => $updatedService,
                'jenis_perawatan' => $jenisPerawatanList
            ]);
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        try {
            $pivotModel = new \App\Models\ServiceJenisPerawatanPivot();
    
            if (!$id) {
                return responseError("ID service tidak diberikan", 400);
            }
    
            $service = $this->services->find($id);
            if (!$service) {
                return responseError("Data service tidak ditemukan", 404);
            }
    
            $pivotModel->where('service_id', $id)->delete();
    
            $this->services->delete($id);
    
            return responseSuccess("Data service berhasil dihapus", [
                'deleted_id' => $id
            ]);
    
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }
}
