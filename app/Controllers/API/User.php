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

     function __construct()
    {
        $this->users = new Users();
    }

     function index()
    {
            // $data = $this->users->findAll();
        return view('pages/master/users', [
            'data' => $this->users->getUsersWithCars(),
            'role'  => $this->getUserRole(),
            'title' => 'Dashboard',
        ]);

    }
    protected function getUserRole() {
        // Contoh: ambil dari session
        return session()->get('role') ?? 'guest';
    }
    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    function show($id = null)
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

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    function create()
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
        $user = $this->users->find($id);

        if (!$user) {
            return redirect()->to(base_url('master/user'))->with('error', 'User tidak ditemukan');
        }

        return view('pages/master/editUsers', [
            'user' => $user,
            'role'  => $this->getUserRole(),
            'title' => 'Dashboard',
        ]);
    }


    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
<<<<<<< HEAD
public function update($id = null)
{
    $this->users->setValidationRules([
        'nip'          => 'required|is_unique[users.nip,id,'.$id.']',
        'email'        => 'permit_empty|valid_email|is_unique[users.email,id,'.$id.']',
        'no_handphone' => 'permit_empty|is_unique[users.no_handphone,id,'.$id.']',
    ]);

    try {
        $input = $this->request->getPost();

        if (empty($input)) {
            return responseError('Tidak ada data input untuk update', 400, 'Input kosong');
=======
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
>>>>>>> 540f18b7ba0e282322c9b0389c197ebb3c9672b6
        }

        $user = $this->users->find($id);
        if (!$user) {
            return responseError('User dengan ID ' . $id . ' tidak ditemukan', 404, 'User tidak ditemukan');
        }

        // Handle password update (optional)
        if (empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
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
     function delete($id = null)
    {
        //
    }
<<<<<<< HEAD
}
=======

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
>>>>>>> 540f18b7ba0e282322c9b0389c197ebb3c9672b6
