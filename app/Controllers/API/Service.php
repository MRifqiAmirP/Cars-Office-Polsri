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
            $kendaraanId = $this->request->getGet('kendaraan_id');

            $serviceModel = new \App\Models\Services();

            $query = $serviceModel;

            if ($kendaraanId) {
                if (!is_numeric($kendaraanId)) {
                    return responseError("Parameter kendaraan_id harus berupa angka", 400);
                }
                $query->where('kendaraan_id', (int)$kendaraanId);
            }

            $services = $query->findAll();

            if (empty($services)) {
                $message = $kendaraanId
                    ? "Tidak ada data services untuk kendaraan $kendaraanId"
                    : "Tidak ada data services";
                return responseSuccess($message, 400);
            }

            $data = [];
            foreach ($services as $service) {
                $data[] = $service->toArrayWithRelations();
            }

            $message = $kendaraanId
                ? "Data service untuk kendaraan $kendaraanId berhasil diambil"
                : "Data service berhasil diambil";

            return responseSuccess($message, $data);
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

            if (!empty($data['speedometer_yang_lalu']) && !empty($data['speedometer_saat_ini'])) {
                if ($data['speedometer_saat_ini'] < $data['speedometer_yang_lalu']) {
                    return responseError('Speedometer saat ini tidak boleh lebih kecil dari speedometer yang lalu.', 400);
                }
            }

            if (!empty($data['bengkel_id'])) {
                $mitraBengkelModel = new \App\Models\Bengkel();
                $bengkel = $mitraBengkelModel->find($data['bengkel_id']);
                if (!$bengkel) {
                    return responseError('Bengkel yang dipilih tidak ditemukan.', 400);
                }
            }

            $fotoNota = $this->request->getFile('foto_nota');
            if ($fotoNota && $fotoNota->isValid() && !$fotoNota->hasMoved()) {
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $fileExtension = $fotoNota->getClientExtension();

                if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                    return responseError('Format file tidak didukung. Gunakan format: jpg, jpeg, png, gif, atau webp.', 400);
                }

                if ($fotoNota->getSize() > 2097152) {
                    return responseError('Ukuran file terlalu besar. Maksimal 2MB.', 400);
                }

                $newName = $fotoNota->getRandomName();
                $fotoNota->move(WRITEPATH . 'uploads/nota', $newName);
                $data['foto_nota'] = $newName;
            } else {
                $data['foto_nota'] = null;
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

            if (isset($data['total_harga']) && !empty($data['total_harga'])) {
                $data['total_harga'] = preg_replace('/[^0-9.]/', '', $data['total_harga']);
            }

            $data['speedometer_yang_lalu'] = !empty($data['speedometer_yang_lalu']) ? $data['speedometer_yang_lalu'] : null;
            $data['speedometer_saat_ini'] = !empty($data['speedometer_saat_ini']) ? $data['speedometer_saat_ini'] : null;
            $data['total_harga'] = !empty($data['total_harga']) ? $data['total_harga'] : null;
            $data['bengkel_id'] = !empty($data['bengkel_id']) ? $data['bengkel_id'] : null;

            $serviceId = $serviceModel->insert($data, true);

            if (!$serviceId) {
                if (isset($data['foto_nota']) && file_exists(WRITEPATH . 'uploads/nota/' . $data['foto_nota'])) {
                    unlink(WRITEPATH . 'uploads/nota/' . $data['foto_nota']);
                }
                return responseError("Gagal menambahkan service", 400, $serviceModel->errors());
            }

            foreach ($jenisPerawatanIds as $jenisId) {
                $pivotModel->insert([
                    'service_id' => $serviceId,
                    'jenis_perawatan_id' => $jenisId
                ]);
            }

            $service = $serviceModel->getServiceWithRelations($serviceId);

            return responseSuccess("Service berhasil ditambahkan", [
                'service' => $service,
                'jenis_perawatan' => $jenisPerawatanIds
            ]);
        } catch (\Throwable $th) {
            if (isset($data['foto_nota']) && !empty($data['foto_nota']) && file_exists(WRITEPATH . 'uploads/nota/' . $data['foto_nota'])) {
                unlink(WRITEPATH . 'uploads/nota/' . $data['foto_nota']);
            }

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
    public function update($id)
    {
        try {
            $serviceModel = new \App\Models\Services();
            $service = $serviceModel->find($id);

            if (!$service) {
                return responseError('Service tidak ditemukan.', 404);
            }

            $data = $this->request->getPost();

            if (!empty($data['speedometer_yang_lalu']) && !empty($data['speedometer_saat_ini'])) {
                if ($data['speedometer_saat_ini'] < $data['speedometer_yang_lalu']) {
                    return responseError('Speedometer saat ini tidak boleh lebih kecil dari speedometer yang lalu.', 400);
                }
            }

            if (!empty($data['bengkel_id'])) {
                $mitraBengkelModel = new \App\Models\Bengkel();
                $bengkel = $mitraBengkelModel->find($data['bengkel_id']);
                if (!$bengkel) {
                    return responseError('Bengkel yang dipilih tidak ditemukan.', 400);
                }
            }

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
                $fileExtension = $file->getClientExtension();

                if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                    return responseError('Format file tidak didukung. Gunakan format: jpg, jpeg, png, gif, atau webp.', 400);
                }

                if ($file->getSize() > 2097152) {
                    return responseError('Ukuran file terlalu besar. Maksimal 2MB.', 400);
                }

                if ($service->file && file_exists(FCPATH . 'uploads/service/file/' . $service->file)) {
                    unlink(FCPATH . 'uploads/service/file/' . $service->file);
                }

                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/service/file', $newName);
                $data['file'] = $newName;
            }

            $fotoNota = $this->request->getFile('foto_nota');
            if ($fotoNota && $fotoNota->isValid() && !$fotoNota->hasMoved()) {
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $fileExtension = $fotoNota->getClientExtension();

                if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                    return responseError('Format file tidak didukung. Gunakan format: jpg, jpeg, png, gif, atau webp.', 400);
                }

                if ($fotoNota->getSize() > 2097152) {
                    return responseError('Ukuran file terlalu besar. Maksimal 2MB.', 400);
                }

                if ($service->foto_nota && file_exists(FCPATH . 'uploads/service/nota/' . $service->foto_nota)) {
                    unlink(FCPATH . 'uploads/service/nota/' . $service->foto_nota);
                }

                $newName = $fotoNota->getRandomName();
                $fotoNota->move(FCPATH . 'uploads/service/nota', $newName);
                $data['foto_nota'] = $newName;
            }

            if (isset($data['jenis_perawatan'])) {
                $jenisPerawatanIds = [];
                if (!empty($data['jenis_perawatan'])) {
                    if (is_string($data['jenis_perawatan'])) {
                        $jenisPerawatanIds = array_map('trim', explode(',', $data['jenis_perawatan']));
                    } elseif (is_array($data['jenis_perawatan'])) {
                        $jenisPerawatanIds = $data['jenis_perawatan'];
                    }
                }

                $pivotModel = new \App\Models\ServiceJenisPerawatanPivot();
                $jenisModel = new \App\Models\JenisPerawatan();

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

                $pivotModel->where('service_id', $id)->delete();
                foreach ($jenisPerawatanIds as $jenisId) {
                    $pivotModel->insert([
                        'service_id' => $id,
                        'jenis_perawatan_id' => $jenisId
                    ]);
                }

                unset($data['jenis_perawatan']);
            }

            if (isset($data['total_harga']) && !empty($data['total_harga'])) {
                $data['total_harga'] = preg_replace('/[^0-9.]/', '', $data['total_harga']);
            }

            if (!$serviceModel->update($id, $data)) {
                return responseError("Gagal mengupdate service", 400, $serviceModel->errors());
            }

            $updatedService = $serviceModel->getServiceWithRelations($id);

            return responseSuccess("Service berhasil diupdate", [
                'service' => $updatedService
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

    public function uploadNota($serviceId)
    {
        try {
            $serviceModel = new \App\Models\Services();
            $service = $serviceModel->find($serviceId);

            if (!$service) {
                return responseError('Service tidak ditemukan.', 404);
            }

            $fotoNota = $this->request->getFile('foto_nota');
            if (!$fotoNota || !$fotoNota->isValid()) {
                return responseError('File foto nota tidak valid.', 400);
            }

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileExtension = $fotoNota->getClientExtension();

            if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                return responseError('Format file tidak didukung. Gunakan format: jpg, jpeg, png, gif, atau webp.', 400);
            }

            if ($fotoNota->getSize() > 2097152) {
                return responseError('Ukuran file terlalu besar. Maksimal 2MB.', 400);
            }

            if ($service->foto_nota && file_exists(WRITEPATH . 'uploads/nota/' . $service->foto_nota)) {
                unlink(WRITEPATH . 'uploads/nota/' . $service->foto_nota);
            }

            $newName = $fotoNota->getRandomName();
            $fotoNota->move(WRITEPATH . 'uploads/nota', $newName);

            $serviceModel->updateFotoNota($serviceId, $newName);

            $updatedService = $serviceModel->getServiceWithRelations($serviceId);

            return responseSuccess("Foto nota berhasil diupload", [
                'service' => $updatedService
            ]);
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }
}
