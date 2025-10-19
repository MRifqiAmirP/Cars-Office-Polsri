<?php

namespace App\Controllers\API;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Controllers\BaseController;
use App\Models\Peminjaman as PeminjamanModel;

class Peminjaman extends BaseController
{
    protected $peminjaman;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function __construct()
    {
        $this->peminjaman = new PeminjamanModel();
    }

    public function index() {
        try {
            $result = $this->peminjaman->findAll();

            $data = [];
            foreach($result as $peminjaman) {
                $data[] = [
                    'peminjaman' => $peminjaman,
                    'car' => $peminjaman->getCar()
                ];
            }

            return responseSuccess("Data Peminjaman", $data);
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
            $peminjaman = $this->peminjaman->find($id);
            if (! $peminjaman) {
                return responseError("Peminjaman tidak ditemukan", 404);
            }

            $data = $this->peminjaman->find($id);
            $result = $data->getCar();

            return responseSuccess("Data Peminjaman by ID", [
                'peminjaman' => $data,
                'car' => $result
            ]);
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

            $file = $this->request->getFile('file');
            if ($file && $file->isValid()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/peminjaman', $newName);
                $data['file'] = 'uploads/peminjaman/' . $newName;
            }

            if (! $this->peminjaman->insert($data)) {
                return responseError("Gagal membuat peminjaman", 400, $this->peminjaman->errors());
            }

            return responseSuccess("Berhasil membuat peminjaman", $data);
        } catch (\Throwable $err) {
            return responseInternalServerError($err->getMessage());
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
            $existingPeminjaman = $this->peminjaman->find($id);
            if (!$existingPeminjaman) {
                return responseError("Peminjaman tidak ditemukan", 404);
            }

            $data = $this->request->getPost();

            $file = $this->request->getFile('file');
            if ($file && $file->isValid()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/peminjaman', $newName);
                $data['file'] = 'uploads/peminjaman/' . $newName;

                if ($existingPeminjaman->file && file_exists(FCPATH . $existingPeminjaman->file)) {
                    unlink(FCPATH . $existingPeminjaman->file);
                }
            }

            if (! $this->peminjaman->update($id, $data)) {
                return responseError("Gagal update data peminjaman", 400, $this->peminjaman->errors());
            }

            return responseSuccess("Berhasil update data peminjaman", $data);
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
        //
    }
}
