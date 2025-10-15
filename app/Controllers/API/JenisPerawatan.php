<?php

namespace App\Controllers\API;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use \App\Controllers\BaseController;
use \App\Models\JenisPerawatan as JenisPerawatanModel;
use \App\Models\ServiceJenisPerawatanPivot as JenisPerawatanPivotModel;

class JenisPerawatan extends BaseController
{
    protected $jenis_perawatan;
    protected $pivotModel;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function __construct()
    {
        $this->jenis_perawatan = new JenisPerawatanModel();
        $this->pivotModel = new JenisPerawatanPivotModel();

    }

    public function index() {
        try {
            $result = $this->jenis_perawatan->findAll();

            return responseSuccess("Berhasil get data jenis perawatan", $result);
        } catch(\Throwable $th) {
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
            $result = $this->jenis_perawatan->find($id);

            if (! $result) {
                return responseError("Jenis perawatan tidak ditemukan", 404);
            }

            return responseSuccess("Berhasil mengambil data jenis perawatan by ID", $result);
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
            if (isset($data['jenis_perawatan'])) {
                $data['jenis_perawatan'] = ucwords(strtolower($data['jenis_perawatan']));
            }

            $validation = $this->validate([
                'jenis_perawatan' => 'required|is_unique[jenis_perawatan.jenis_perawatan]'
            ]);

            if (!$validation) {
                return responseError("Data sudah ada", 400, $this->validator->getErrors());
            }

            if (! $this->jenis_perawatan->insert($data)) {
                return responseError("Gagal menambahkan jenis perawatan", 400, $this->jenis_perawatan->errors());
            }

            return responseSuccess("Berhasil menambahkan jenis perawatan", $data, 201);
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
            $data = $this->request->getPost();

            if ($this->jenis_perawatan->where('jenis_perawatan', $data['jenis_perawatan'])->first()) {
                return responseError("Jenis perawatan sudah ada");
            }

            if (! $this->jenis_perawatan->update($id, $data)) {
                return responseError("Gagal update jenis perawatan", 400, $this->jenis_perawatan->errors());
            }

            return responseSuccess("Berhasil update jenis perawatan", $data);
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
        if(! $this->jenis_perawatan->where('id', $id)->first()) {
            return responseError("Jenis perawatan tidak ditemukan");
        }

        $existingPivot = $this->pivotModel->where('jenis_perawatan_id', $id)->countAllResults();
        
        if ($existingPivot > 0) {
            return responseError("Jenis perawatan ini digunakan di servis");
        }
        
        $this->jenis_perawatan->delete($id);
        
        return responseSuccess("Berhasil menghapus data jenis perawatan");
    }
}
