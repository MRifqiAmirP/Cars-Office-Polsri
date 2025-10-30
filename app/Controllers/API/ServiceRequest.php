<?php

namespace App\Controllers\API;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use \App\Controllers\BaseController;
use \App\Models\ServiceRequest as ServiceRequestModel;
use PHPUnit\Framework\TestStatus\Success;

class ServiceRequest extends BaseController
{
    protected $service_request;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function __construct()
    {
        $this->service_request = new ServiceRequestModel();
    }

    public function index()
    {
        $userId = $this->request->getGet('user_id');

        $query = $this->service_request
            ->select('
                service_request.*, 
                users.nama AS user_nama, 
                users.email AS user_email, 
                cars.merk,
                cars.type,
                cars.nopol AS plat_kendaran,
                mitra_bengkel.nama_bengkel
            ')
            ->join('users', 'users.id = service_request.user_id', 'left')
            ->join('cars', 'cars.id = service_request.kendaraan_id', 'left')
            ->join('mitra_bengkel', 'mitra_bengkel.id = service_request.bengkel_id', 'left')
            ->orderBy('service_request.created_at', 'DESC');

        if ($userId && is_numeric($userId)) {
            $query->where('service_request.user_id', (int)$userId);
        }

        $data = $query->findAll();

        $message = $userId ? "Data service request untuk user $userId berhasil diambil" : "Data service request berhasil diambil";

        return responseSuccess($message, $data);
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
        $data = $this->service_request
            ->select('
                service_request.*, 
                users.nama AS user_nama, 
                users.email AS user_email, 
                cars.merk,
                cars.type,
                cars.nopol AS plat_kendaran,
                mitra_bengkel.nama_bengkel
            ')
            ->join('users', 'users.id = service_request.user_id', 'left')
            ->join('cars', 'cars.id = service_request.kendaraan_id', 'left')
            ->join('mitra_bengkel', 'mitra_bengkel.id = service_request.bengkel_id', 'left')
            ->where('service_request.id', $id)
            ->findAll();

        if (empty($data)) {
            return responseError("Data service request tidak ditemukan", 404);
        }

        return responseSuccess("Data service request by ID berhasil diambil", $data);
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
        $validationRules = [
            'user_id'      => 'required|is_not_unique[users.id]',
            'kendaraan_id' => 'required|is_not_unique[cars.id]',
            'bengkel_id'   => 'permit_empty|is_not_unique[mitra_bengkel.id]',
            'keluhan'      => 'required|string|min_length[5]',
            'status'       => 'permit_empty|string',
            'total_harga'  => 'permit_empty',
            'file'         => 'permit_empty|uploaded[file]|max_size[file,1024]|ext_in[file,png,jpg,jpeg,pdf]',
            'foto_nota'    => 'permit_empty|uploaded[file]|max_size[file,1024]ext_in[file,png,jpg,jpeg,pdf]'
        ];

        if (!$this->validate($validationRules)) {
            return responseError("Gagal validasi", 400, $this->validator->getErrors());
        }

        $file = $this->request->getFile('file');
        $fileName = null;

        if ($file && $file->isValid()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/service_request/form', $fileName);
        }

        $data = [
            'user_id'      => $this->request->getPost('user_id'),
            'kendaraan_id' => $this->request->getPost('kendaraan_id'),
            'bengkel_id'   => $this->request->getPost('bengkel_id') ?: null,
            'keluhan'      => $this->request->getPost('keluhan'),
            'status'       => $this->request->getPost('status') ?? 'Pending',
            'file'         => $fileName,
        ];

        if (! $this->service_request->insert($data)) {
            return responseError("Gagal membuat service request");
        }

        return responseSuccess("Berhasil membuat service request", $data, 201);
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
        $request = $this->request;

        $oldData = $this->service_request->find($id);
        if (!$oldData) {
            return responseError("Data tidak ditemukan", 404);
        }

        $data = [
            'user_id'      => $request->getPost('user_id') ?: $oldData->user_id,
            'kendaraan_id' => $request->getPost('kendaraan_id') ?: $oldData->kendaraan_id,
            'bengkel_id'   => $request->getPost('bengkel_id') ?: $oldData->bengkel_id,
            'keluhan'      => $request->getPost('keluhan') ?: $oldData->keluhan,
            'status'       => $request->getPost('status') ?: $oldData->status,
            'total_harga'  => $request->getPost('total_harga') ?: $oldData->total_harga
        ];

        $file = $request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!empty($oldData->file) && file_exists(FCPATH . 'uploads/service_requests/file/' . $oldData->file)) {
                unlink(FCPATH . 'uploads/service_requests/file/' . $oldData->file);
            }

            $uploadPath = FCPATH . 'uploads/service_requests/file/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['file'] = $newName;
        }

        $nota = $this->request->getFile('foto_nota');
        $fileNota = null;

        if ($nota && $nota->isValid()) {
            if (!empty($oldData->foto_nota) && file_exists(FCPATH . 'uploads/service_requests/nota/' . $oldData->foto_nota)) {
                unlink(FCPATH . 'uploads/service_requests/nota/' . $oldData->foto_nota);
            }

            $fileNota = $nota->getRandomName();
            $nota->move(FCPATH . 'uploads/service_requests/nota/', $fileNota);
            $data['foto_nota'] = $fileNota;
        }

        $this->service_request->update($id, $data);

        return responseSuccess("Data berhasil diupdate", $this->service_request->find($id));
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
        //
    }
}
