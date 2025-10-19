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
            $user = $this->users->find($id);
            if (!$user) {
                return responseError('User tidak ditemukan', 404, "User tidak ditemukan");
            }
    
            $car = $user->getCars()[0] ?? null;
            $services = $user->getAllServices();
            $serviceRequests = $user->getServiceRequests();
    
            $serviceRequestData = array_map(function ($req) {
                $bengkel = $req->getBengkel();
                return [
                    ...$req->toArray(),
                    'nama_bengkel' => $bengkel?->nama_bengkel ?? null
                ];
            }, $serviceRequests);
    
            $serviceData = array_map(fn($s) => $s->toArrayWithRelations(), $services);
    
            $data = [
                'user' => [
                    'id' => $user->id,
                    'nip' => $user->nip,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'no_handphone' => $user->no_handphone,
                    'jabatan' => $user->jabatan,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'kendaraan' => $car,
                'service' => $serviceData,
                'service_request' => $serviceRequestData,
            ];
    
            return responseSuccess('Data user ditemukan by ID', $data);
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

            if (empty($input['nip']) || empty($input['nama']) || empty($input['jabatan']) || empty($input['password']) || empty($input['role'])) {
                return responseError('NIP, Nama, Jabatan, Role dan Password harus diisi', 400);
            }

            $duplicateCheck = $this->checkForDuplicates($input);
            if ($duplicateCheck !== true) {
                return $duplicateCheck;
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
    public function update($id)
    {
        try {
            $input = $this->request->getPost();

            $duplicateCheck = $this->checkForDuplicates($input, $id);
            if ($duplicateCheck !== true) {
                return $duplicateCheck;
            }

            if (!$this->users->update($id, $input)) {
                $errors = $this->users->errors();
                return responseError('Gagal mengupdate user', 400, $errors);
            }

            return responseSuccess('User updated successfully');
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

    private function checkForDuplicates($input, $excludeId = null)
    {
        $builder = $this->users;

        if (!empty($input['nip'])) {
            $builder->where('nip', $input['nip']);
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            $existingNip = $builder->first();
            if ($existingNip) {
                return responseError('NIP sudah terdaftar', 400, ['nip' => 'NIP sudah terdaftar']);
            }
        }

        if (!empty($input['email'])) {
            $builder = $this->users->where('email', $input['email']);
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            $existingEmail = $builder->first();
            if ($existingEmail) {
                return responseError('Email sudah terdaftar', 400, ['email' => 'Email sudah terdaftar']);
            }
        }

        if (!empty($input['no_handphone'])) {
            $builder = $this->users->where('no_handphone', $input['no_handphone']);
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            $existingPhone = $builder->first();
            if ($existingPhone) {
                return responseError('Nomor handphone sudah terdaftar', 400, ['no_handphone' => 'Nomor handphone sudah terdaftar']);
            }
        }

        return true;
    }
}
