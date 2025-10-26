<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Cars as CarsModel;

class Cars extends BaseController
{
    protected $cars;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function __construct()
    {
        $this->cars = new CarsModel();
    }

    public function index()
    {
        try {
            $result = $this->cars->getAllCarsWithUser();

            $formattedResult = [];
            foreach ($result as $car) {
                $carArray = $car->toArray();
                $carArray['foto_url'] = $car->getFotoUrl();
                $formattedResult[] = $carArray;
            }

            return responseSuccess(
                'Data mobil',
                $formattedResult
            );
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
            $car = $this->cars->getCarWithUser($id);

            if (!$car) {
                return responseError('Data mobil dengan user ID' . $id . 'tidak ditemukan', 404);
            }

            $carData = $car->toArray();
            $carData['foto_url'] = $car->getFotoUrl();

            return responseSuccess('Berhasil. Data mobil by ID', $carData);
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
            $input = $this->request->getPost();

            if (empty($input)) {
                return responseError('Tidak ada data yang diinput', 400, 'Bad request');
            }

            $fotoKendaraan = $this->request->getFile('foto_kendaraan');

            if ($fotoKendaraan && $fotoKendaraan->isValid() && !$fotoKendaraan->hasMoved()) {
                $validMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($fotoKendaraan->getMimeType(), $validMimeTypes)) {
                    return responseError('Format file tidak didukung. Gunakan format JPG, PNG, atau GIF', 400);
                }

                if ($fotoKendaraan->getSize() > 5242880) {
                    return responseError('Ukuran file terlalu besar. Maksimal 5MB', 400);
                }

                $merk = url_title($input['merk'], '_', true);
                $type = url_title($input['type'], '_', true);
                $nopol = url_title($input['nopol'], '_', true);
                $kodeUnik = bin2hex(random_bytes(4));

                $extension = $fotoKendaraan->getExtension();
                $newName = "{$merk}_{$type}_{$nopol}_{$kodeUnik}.{$extension}";

                $fotoKendaraan->move(FCPATH . 'uploads/cars', $newName);

                $input['foto_kendaraan'] = $newName;
            } else {
                $input['foto_kendaraan'] = $input['foto_kendaraan'] ?? null;
            }

            if (!$this->cars->insert($input)) {
                if (isset($newName)) {
                    @unlink(FCPATH . 'uploads/cars/' . $newName);
                }
                return responseError('Gagal input data kendaraan', 400, $this->cars->errors());
            }

            return responseSuccess('Berhasil tambah data kendaraan', $input);
        } catch (\Throwable $th) {
            if (isset($newName)) {
                @unlink(FCPATH . 'uploads/cars/' . $newName);
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
    public function update($id = null)
    {
        try {
            $input = $this->request->getPost();

            if (!$input) {
                return responseError('Tidak ada data yang diinput', 400, 'Empty input');
            }

            $existingCar = $this->cars->find($id);
            if (!$existingCar) {
                return responseError('Data mobil tidak ditemukan', 404);
            }

            $oldFoto = $existingCar->foto_kendaraan ?? '';

            $fotoKendaraan = $this->request->getFile('foto_kendaraan');

            if ($fotoKendaraan && $fotoKendaraan->isValid() && !$fotoKendaraan->hasMoved()) {
                $validMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($fotoKendaraan->getMimeType(), $validMimeTypes)) {
                    return responseError('Format file tidak didukung. Gunakan format JPG, PNG, atau GIF', 400);
                }

                if ($fotoKendaraan->getSize() > 5242880) {
                    return responseError('Ukuran file terlalu besar. Maksimal 5MB', 400);
                }

                $merk = !empty($input['merk']) ? $input['merk'] : $existingCar->merk;
                $type = !empty($input['type']) ? $input['type'] : $existingCar->type;
                $nopol = !empty($input['nopol']) ? $input['nopol'] : $existingCar->nopol;

                $merk = url_title(trim($merk), '_', true);
                $type = url_title(trim($type), '_', true);
                $nopol = url_title(trim($nopol), '_', true);
                $kodeUnik = bin2hex(random_bytes(4));

                $extension = $fotoKendaraan->getExtension();
                $newName = "{$merk}_{$type}_{$nopol}_{$kodeUnik}.{$extension}";

                $uploadPath = FCPATH . 'uploads/cars';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                if ($fotoKendaraan->move($uploadPath, $newName)) {
                    $input['foto_kendaraan'] = $newName;
                } else {
                    return responseError('Gagal mengupload file foto', 400);
                }
            } else {
                $input['foto_kendaraan'] = $oldFoto;
            }

            if ($input['delete_foto'] == 'true') {
                $this->deleteFoto($id);
                $input['foto_kendaraan'] = null;
            }

            $result = $this->cars->update($id, $input);

            if (!$result) {
                if (isset($newName) && file_exists(FCPATH . 'uploads/cars/' . $newName)) {
                    @unlink(FCPATH . 'uploads/cars/' . $newName);
                }
                return responseError('Gagal update data mobil', 400, $this->cars->errors());
            }

            if (isset($newName) && !empty($oldFoto) && file_exists(FCPATH . 'uploads/cars/' . $oldFoto)) {
                @unlink(FCPATH . 'uploads/cars/' . $oldFoto);
            }

            $updatedCar = $this->cars->find($id);

            return responseSuccess('Berhasil update data mobil', $updatedCar);
        } catch (\Throwable $th) {
            if (isset($newName) && file_exists(FCPATH . 'uploads/cars/' . $newName)) {
                @unlink(FCPATH . 'uploads/cars/' . $newName);
            }
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
            $car = $this->cars->find($id);
            if (!$car) {
                return responseError('Data mobil tidak ditemukan', 404);
            }

            $hasActiveRelations = $this->checkActiveRelations($id);

            if ($hasActiveRelations) {
                return responseError(
                    'Tidak dapat menghapus mobil karena memiliki riwayat service aktif',
                    400,
                    ['active_relations' => $hasActiveRelations]
                );
            }

            if (!$this->cars->delete($id)) {
                return responseError('Gagal menghapus data mobil', 400);
            }

            return responseSuccess('Data mobil berhasil dihapus (soft delete)');
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    private function deleteFoto($id = null)
    {
        try {
            $car = $this->cars->find($id);
            if (!$car) {
                return responseError('Data mobil tidak ditemukan', 404);
            }

            $fotoKendaraan = $car->foto_kendaraan;

            if ($fotoKendaraan && file_exists(FCPATH . 'uploads/cars/' . $fotoKendaraan)) {
                @unlink(FCPATH . 'uploads/cars/' . $fotoKendaraan);
            }

            $this->cars->update($id, ['foto_kendaraan' => null]);

            return responseSuccess('Foto kendaraan berhasil dihapus');
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    public function restore($id = null)
    {
        try {
            $car = $this->cars->onlyDeleted()->find($id);

            if (!$car) {
                return responseError('Data mobil yang dihapus tidak ditemukan', 404);
            }

            $restoreData = ['deleted_at' => null];

            if (!$this->cars->update($id, $restoreData)) {
                return responseError('Gagal mengembalikan data mobil', 400);
            }

            return responseSuccess('Data mobil berhasil dikembalikan');
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    public function forceDelete($id = null)
    {
        try {
            $car = $this->cars->onlyDeleted()->find($id);

            if (!$car) {
                return responseError('Data mobil tidak ditemukan atau belum di soft delete', 404);
            }

            $this->cars->db->transStart();

            if ($car->foto_kendaraan && file_exists(FCPATH . 'uploads/cars/' . $car->foto_kendaraan)) {
                @unlink(FCPATH . 'uploads/cars/' . $car->foto_kendaraan);
            }

            $this->cars->purgeDeleted();

            $this->cars->db->transComplete();

            if ($this->cars->db->transStatus() === FALSE) {
                return responseError('Gagal menghapus permanen data mobil', 400);
            }

            return responseSuccess('Data mobil berhasil dihapus permanen');
        } catch (\Throwable $th) {
            $this->cars->db->transRollback();
            return responseInternalServerError($th->getMessage());
        }
    }

    private function checkActiveRelations($carId)
    {
        $servicesModel = new \App\Models\Services();
        $serviceRequestModel = new \App\Models\ServiceRequest();

        $activeRelations = [];

        $services = $servicesModel->where('kendaraan_id', $carId)->countAllResults();
        if ($services > 0) {
            $activeRelations['services'] = $services . ' riwayat service';
        }

        $activeRequests = $serviceRequestModel
            ->where('kendaraan_id', $carId)
            ->whereIn('status', ['pending', 'proses'])
            ->countAllResults();

        if ($activeRequests > 0) {
            $activeRelations['active_requests'] = $activeRequests . ' request service aktif';
        }

        return empty($activeRelations) ? false : $activeRelations;
    }

    public function getDeletedCars()
    {
        try {
            $deletedCars = $this->cars
                ->onlyDeleted()
                ->select('cars.*, users.nama as user_nama, users.nip as user_nip')
                ->join('users', 'users.id = cars.user_id')
                ->findAll();

            return responseSuccess('Data mobil yang dihapus', $deletedCars);
        } catch (\Throwable $th) {
            return responseInternalServerError($th->getMessage());
        }
    }

    public function getAllCars()
    {
        return $this->cars->select('cars.*, users.nama as user_nama, users.nip as user_nip')
            ->join('users', 'users.id = cars.user_id')
            ->findAll();
    }
}
