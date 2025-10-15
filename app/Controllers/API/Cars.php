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
            return responseSuccess(
                'Data mobil',
                $result
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

            return responseSuccess('Berhasil. Data mobil by ID', $car);
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

            if (!$this->cars->insert($input)) {
                return responseError('Gagal input data kendaraan', 400, $this->cars->errors());
            }

            return responseSuccess('Berhasil tambah data kendaraan', $input);
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
            $input = $this->request->getPost();

            if (!$input) {
                return responseError('Tidak ada data yang diinput', 400, 'Empty input');
            }

            if (! $this->cars->update($id, $input)) {
                return responseError('Gagal update data mobil', 400, $this->cars->errors());
            }

            return responseSuccess('Berhasil update data mobil');
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
