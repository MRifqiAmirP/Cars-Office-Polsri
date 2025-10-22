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

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    function create()
    {
        try {
            $input = $this->request->getPost([
                'nip',
                'nama',
                'email',
                'no_handphone',
                'jabatan',
                'password',
            ]);

            if (empty($input['nip']) || empty($input['nama']) || empty($input['jabatan']) || empty($input['password'])) {
                return responseError('NIP, Nama, Jabatan, dan Password harus diisi', 400);
            }

            if (!$this->users->insert($input)) {
                $errors = $this->users->errors();
                $error  = [];

                $customMessages = [
                    'nip'          => 'NIP sudah terdaftar',
                    'email'        => 'Email sudah terdaftar',
                    'no_handphone' => 'No Handphone sudah terdaftar',
                ];

                foreach ($errors as $field => $msg) {
                    if (isset($customMessages[$field])) {
                        $error[$field] = $customMessages[$field];
                    } else {
                        $error[$field] = $msg;
                    }
                }

                return responseError('Gagal menambahkan user', 400, $error);
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
}