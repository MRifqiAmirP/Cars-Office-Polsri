<?php

namespace App\Controllers\API;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Users;
use App\Controllers\BaseController;

class User extends BaseController
{
    protected $users;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function __construct()
    {
        $this->users = new Users();
    }

    public function index()
    {
        try {
            // $data = $this->users->findAll();
            $data = $this->users->getUsersWithCars();

            if (empty($data)) {
                return responseError('Data user belum diisi', 200);
            }

            return responseSuccess('Data user', $data);
        } catch (\Throwable $th) {
            return responseInternalServerError($th);
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
            $data = $this->users->getUserIdWithCars($id);
            if (!$data) {
                return responseError('User tidak ditemukan', 404, "User tidak ditemukan");
            }
            return responseSuccess('Data user ditemukan by ID', $data);
        } catch (\Throwable $th) {
            return responseInternalServerError("Error");
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

            if (empty($input['nip']) || empty($input['nama']) || empty($input['jabatan']) || empty($input['password']) || empty($input['role'])) {
                return responseError('NIP, Nama, Jabatan, Role dan Password harus diisi', 400);
            }

            if (!$this->users->insert($input)) {
                $errors = $this->users->errors();

                return responseError('Gagal menambahkan user', 400, $errors);
            }

            return responseSuccess('User created successfully', ['id' => $this->users->insertID()]);
        } catch (\Throwable $th) {
            return responseInternalServerError($th);
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

            if (empty($input)) {
                return responseError('Tidak ada data input untuk update', 400, 'Input kosong');
            }

            if (!$this->users->find($id)) {
                return responseError('User dengan ID ' . $id . ' tidak ditemukan', 404, 'User tidak ditemukan');
            }

            if (!$this->users->update($id, $input)) {
                return responseError('Gagal update data user', 400, $this->users->errors());
            }

            return responseSuccess('User updated successfully', ['id' => $id]);
        } catch (\Throwable $th) {
            return responseInternalServerError($th);
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
