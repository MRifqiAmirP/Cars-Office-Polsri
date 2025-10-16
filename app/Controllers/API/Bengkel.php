<?php

namespace App\Controllers\API;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use \App\Controllers\BaseController;
use \App\Models\Bengkel as BengkelModel;

class Bengkel extends BaseController
{
    protected $bengkel;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */

    public function __construct()
    {
        $this->bengkel = new BengkelModel();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('search');
        $statusKelayakan = $this->request->getGet('status_kelayakan');
        $statusAktif = $this->request->getGet('status_aktif');
        $sortBy = $this->request->getGet('sort_by') ?? 'created_at';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        $perPage = min(max($perPage, 1), 100);
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? $sortOrder : 'desc';
        $allowedSortColumns = ['nama_bengkel', 'email', 'status_kelayakan', 'status_aktif', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';

        $builder = $this->bengkel;

        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama_bengkel', $search)
                ->orLike('alamat_bengkel', $search)
                ->orLike('email', $search)
                ->orLike('nama_kontak_bengkel', $search)
                ->orLike('telepon_kontak_bengkel', $search)
                ->groupEnd();
        }

        if (!empty($statusKelayakan)) {
            $builder->where('status_kelayakan', $statusKelayakan);
        }

        if (!empty($statusAktif)) {
            $builder->where('status_aktif', $statusAktif);
        }

        $totalData = $builder->countAllResults(false);
        $totalPages = ceil($totalData / $perPage);

        $bengkel = $builder->orderBy($sortBy, $sortOrder)
            ->paginate($perPage, 'default', $page);

        $bengkelWithUrls = array_map(function ($item) {
            return $this->addFileUrls($item);
        }, $bengkel);

        return responseSuccess("Data bengkel berhasil diambil", [
            'bengkel' => $bengkelWithUrls,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total_data' => $totalData,
                'total_pages' => $totalPages,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
            ]
        ]);
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
        if (!$id) {
            return responseError("ID bengkel harus diisi");
        }

        $bengkel = $this->bengkel->find($id);

        if (!$bengkel) {
            return responseError("Data bengkel tidak ditemukan", 404);
        }

        $bengkelData = $this->addFileUrls($bengkel);

        return responseSuccess("Data bengkel berhasil diambil", $bengkelData);
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
        $data = $this->request->getPost();

        $existingBengkel = $this->bengkel->where('nama_bengkel', $data['nama_bengkel'])->first();
        if ($existingBengkel) {
            return responseError("Nama bengkel '" . $data['nama_bengkel'] . "' sudah ada");
        }

        $validation = $this->validate([
            'nama_bengkel' => 'required|min_length[3]',
            'alamat_bengkel' => 'required',
            'email' => 'required|valid_email',
            'nama_kontak_bengkel' => 'required|min_length[3]',
            'telepon_kontak_bengkel' => 'required|min_length[6]',
            'file_siup' => 'permit_empty|uploaded[file_siup]|max_size[file_siup,1024]|ext_in[file_siup,pdf,jpg,png,jpeg]',
            'file_situ' => 'permit_empty|uploaded[file_situ]|max_size[file_situ,1024]|ext_in[file_situ,pdf,jpg,png,jpeg]',
            'file_perjanjian_kerjasama' => 'permit_empty|uploaded[file_perjanjian_kerjasama]|max_size[file_perjanjian_kerjasama,1024]|ext_in[file_perjanjian_kerjasama,pdf,jpg,png,jpeg]',
        ]);

        if (!$validation) {
            return responseError("Gagal validasi input", 400, $this->validator->getErrors());
        }

        $data['file_siup'] = null;
        $data['file_situ'] = null;
        $data['file_perjanjian_kerjasama'] = null;

        $fileSiup = $this->request->getFile('file_siup');
        if ($fileSiup && $fileSiup->isValid() && !$fileSiup->hasMoved()) {
            $namaBengkel = $data['nama_bengkel'];
            $namaFileBase = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $namaBengkel));
            $randomString = bin2hex(random_bytes(8));

            $fileSiupName = $namaFileBase . '_siup_' . $randomString . '.' . $fileSiup->getExtension();
            $fileSiup->move('uploads/bengkel', $fileSiupName);
            $data['file_siup'] = $fileSiupName;
        }

        $fileSitu = $this->request->getFile('file_situ');
        if ($fileSitu && $fileSitu->isValid() && !$fileSitu->hasMoved()) {
            $namaBengkel = $data['nama_bengkel'];
            $namaFileBase = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $namaBengkel));
            $randomString = bin2hex(random_bytes(8));

            $fileSituName = $namaFileBase . '_situ_' . $randomString . '.' . $fileSitu->getExtension();
            $fileSitu->move('uploads/bengkel', $fileSituName);
            $data['file_situ'] = $fileSituName;
        }

        $filePerjanjian = $this->request->getFile('file_perjanjian_kerjasama');
        if ($filePerjanjian && $filePerjanjian->isValid() && !$filePerjanjian->hasMoved()) {
            $namaBengkel = $data['nama_bengkel'];
            $namaFileBase = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $namaBengkel));
            $randomString = bin2hex(random_bytes(8));

            $filePerjanjianName = $namaFileBase . '_perjanjian_kerjasama_' . $randomString . '.' . $filePerjanjian->getExtension();
            $filePerjanjian->move('uploads/bengkel', $filePerjanjianName);
            $data['file_perjanjian_kerjasama'] = $filePerjanjianName;
        }

        $data['status_kelayakan'] = 'proses';

        try {
            if (! $this->bengkel->insert($data)) {
                $this->hapusFileUploaded($data['file_siup'], $data['file_situ'], $data['file_perjanjian_kerjasama']);
                return responseError("Gagal memasukkan data bengkel", 400, $this->bengkel->errors());
            }

            return responseSuccess("Berhasil menambah data bengkel", $data);
        } catch (\Exception $e) {
            $this->hapusFileUploaded($data['file_siup'], $data['file_situ'], $data['file_perjanjian_kerjasama']);
            return responseError("Terjadi kesalahan sistem: " . $e->getMessage(), 500);
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
        $bengkel = $this->bengkel->find($id);
        if (!$bengkel) {
            return responseError("Data bengkel tidak ditemukan");
        }

        $data = $this->request->getPost();

        if (isset($data['nama_bengkel'])) {
            $existingBengkel = $this->bengkel->where('nama_bengkel', $data['nama_bengkel'])->where('id !=', $id)->first();
            if ($existingBengkel) {
                return responseError("Nama bengkel '" . $data['nama_bengkel'] . "' sudah ada");
            }
        }

        $this->handleHapusFile($bengkel, $data);

        $validationRules = [];
        $validationMessages = [];

        if (isset($data['nama_bengkel'])) {
            $validationRules['nama_bengkel'] = 'required|min_length[3]';
        }
        if (isset($data['email'])) {
            $validationRules['email'] = 'required|valid_email';
        }
        if (isset($data['nama_kontak_bengkel'])) {
            $validationRules['nama_kontak_bengkel'] = 'required|min_length[3]';
        }
        if (isset($data['telepon_kontak_bengkel'])) {
            $validationRules['telepon_kontak_bengkel'] = 'required|min_length[6]';
        }

        $fileSiup = $this->request->getFile('file_siup');
        $fileSitu = $this->request->getFile('file_situ');
        $filePerjanjian = $this->request->getFile('file_perjanjian_kerjasama');

        if ($fileSiup && $fileSiup->isValid()) {
            $validationRules['file_siup'] = 'max_size[file_siup,1024]|ext_in[file_siup,pdf,jpg,png,jpeg]';
        }
        if ($fileSitu && $fileSitu->isValid()) {
            $validationRules['file_situ'] = 'max_size[file_situ,1024]|ext_in[file_situ,pdf,jpg,png,jpeg]';
        }
        if ($filePerjanjian && $filePerjanjian->isValid()) {
            $validationRules['file_perjanjian_kerjasama'] = 'max_size[file_perjanjian_kerjasama,1024]|ext_in[file_perjanjian_kerjasama,pdf,jpg,png,jpeg]';
        }

        if (!empty($validationRules)) {
            $validation = $this->validate($validationRules);
            if (!$validation) {
                return responseError("Gagal validasi input", 400, $this->validator->getErrors());
            }
        }

        $uploadedFiles = [];

        try {
            if ($fileSiup && $fileSiup->isValid() && !$fileSiup->hasMoved()) {
                $namaBengkel = $data['nama_bengkel'] ?? $bengkel->nama_bengkel;
                $namaFileBase = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $namaBengkel));
                $randomString = bin2hex(random_bytes(8));

                $fileSiupName = $namaFileBase . '_siup_' . $randomString . '.' . $fileSiup->getExtension();
                $fileSiup->move('uploads/bengkel', $fileSiupName);
                $data['file_siup'] = $fileSiupName;
                $uploadedFiles[] = $fileSiupName;

                if ($bengkel->file_siup && file_exists('uploads/bengkel/' . $bengkel->file_siup)) {
                    unlink('uploads/bengkel/' . $bengkel->file_siup);
                }
            }

            if ($fileSitu && $fileSitu->isValid() && !$fileSitu->hasMoved()) {
                $namaBengkel = $data['nama_bengkel'] ?? $bengkel->nama_bengkel;
                $namaFileBase = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $namaBengkel));
                $randomString = bin2hex(random_bytes(8));

                $fileSituName = $namaFileBase . '_situ_' . $randomString . '.' . $fileSitu->getExtension();
                $fileSitu->move('uploads/bengkel', $fileSituName);
                $data['file_situ'] = $fileSituName;
                $uploadedFiles[] = $fileSituName;

                if ($bengkel->file_situ && file_exists('uploads/bengkel/' . $bengkel->file_situ)) {
                    unlink('uploads/bengkel/' . $bengkel->file_situ);
                }
            }

            if ($filePerjanjian && $filePerjanjian->isValid() && !$filePerjanjian->hasMoved()) {
                $namaBengkel = $data['nama_bengkel'] ?? $bengkel->nama_bengkel;
                $namaFileBase = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $namaBengkel));
                $randomString = bin2hex(random_bytes(8));

                $filePerjanjianName = $namaFileBase . '_perjanjian_kerjasama_' . $randomString . '.' . $filePerjanjian->getExtension();
                $filePerjanjian->move('uploads/bengkel', $filePerjanjianName);
                $data['file_perjanjian_kerjasama'] = $filePerjanjianName;
                $uploadedFiles[] = $filePerjanjianName;

                if ($bengkel->file_perjanjian_kerjasama && file_exists('uploads/bengkel/' . $bengkel->file_perjanjian_kerjasama)) {
                    unlink('uploads/bengkel/' . $bengkel->file_perjanjian_kerjasama);
                }
            }

            if (!empty($data)) {
                if (!$this->bengkel->update($id, $data)) {
                    $this->hapusFileUploadedArray($uploadedFiles);
                    return responseError("Gagal update data bengkel", 400, $this->bengkel->errors());
                }

                return responseSuccess("Berhasil update data bengkel", $data);
            }

            return responseSuccess("Tidak ada data yang diupdate");
        } catch (\Exception $e) {
            $this->hapusFileUploadedArray($uploadedFiles);
            return responseError("Terjadi kesalahan sistem: " . $e->getMessage(), 500);
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

    private function hapusFileUploadedArray($files)
    {
        $uploadPath = 'uploads/bengkel/';

        foreach ($files as $fileName) {
            if ($fileName && file_exists($uploadPath . $fileName)) {
                unlink($uploadPath . $fileName);
            }
        }
    }

    private function hapusFileUploaded($fileSiupName, $fileSituName, $filePerjanjianName)
    {
        $uploadPath = 'uploads/bengkel/';

        if (file_exists($uploadPath . $fileSiupName)) {
            unlink($uploadPath . $fileSiupName);
        }

        if (file_exists($uploadPath . $fileSituName)) {
            unlink($uploadPath . $fileSituName);
        }

        if (file_exists($uploadPath . $filePerjanjianName)) {
            unlink($uploadPath . $filePerjanjianName);
        }
    }

    private function handleHapusFile($bengkel, &$data)
    {
        $uploadPath = 'uploads/bengkel/';

        if (isset($data['hapus_file_siup']) && $data['hapus_file_siup'] == '1') {
            if ($bengkel->file_siup && file_exists($uploadPath . $bengkel->file_siup)) {
                unlink($uploadPath . $bengkel->file_siup);
            }
            $data['file_siup'] = null;
            unset($data['hapus_file_siup']);
        }

        if (isset($data['hapus_file_situ']) && $data['hapus_file_situ'] == '1') {
            if ($bengkel->file_situ && file_exists($uploadPath . $bengkel->file_situ)) {
                unlink($uploadPath . $bengkel->file_situ);
            }
            $data['file_situ'] = null;
            unset($data['hapus_file_situ']);
        }

        if (isset($data['hapus_file_perjanjian_kerjasama']) && $data['hapus_file_perjanjian_kerjasama'] == '1') {
            if ($bengkel->file_perjanjian_kerjasama && file_exists($uploadPath . $bengkel->file_perjanjian_kerjasama)) {
                unlink($uploadPath . $bengkel->file_perjanjian_kerjasama);
            }
            $data['file_perjanjian_kerjasama'] = null;
            unset($data['hapus_file_perjanjian_kerjasama']);
        }
    }

    private function kapitalisasiAwalKata($text)
    {
        $text = strtolower($text);
        $text = preg_replace_callback('/(^|[-\s])([a-z])/', function ($matches) {
            return $matches[1] . strtoupper($matches[2]);
        }, $text);
        return $text;
    }

    private function addFileUrls($bengkel)
    {
        $baseUrl = base_url();

        if (is_object($bengkel)) {
            $bengkel = $bengkel->toArray();
        }

        if (!empty($bengkel['file_siup'])) {
            $bengkel['file_siup_url'] = $baseUrl . 'uploads/bengkel/' . $bengkel['file_siup'];
            $bengkel['file_siup_exists'] = file_exists(FCPATH . 'uploads/bengkel/' . $bengkel['file_siup']);
        }

        if (!empty($bengkel['file_situ'])) {
            $bengkel['file_situ_url'] = $baseUrl . 'uploads/bengkel/' . $bengkel['file_situ'];
            $bengkel['file_situ_exists'] = file_exists(FCPATH . 'uploads/bengkel/' . $bengkel['file_situ']);
        }

        if (!empty($bengkel['file_perjanjian_kerjasama'])) {
            $bengkel['file_perjanjian_kerjasama_url'] = $baseUrl . 'uploads/bengkel/' . $bengkel['file_perjanjian_kerjasama'];
            $bengkel['file_perjanjian_kerjasama_exists'] = file_exists(FCPATH . 'uploads/bengkel/' . $bengkel['file_perjanjian_kerjasama']);
        }

        return $bengkel;
    }
}
