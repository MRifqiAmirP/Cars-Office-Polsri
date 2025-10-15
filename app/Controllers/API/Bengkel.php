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
        //
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
        //
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
            if(! $this->bengkel->insert($data)) {
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
        //
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

    private function kapitalisasiAwalKata($text)
    {
        $text = strtolower($text);
        $text = preg_replace_callback('/(^|[-\s])([a-z])/', function ($matches) {
            return $matches[1] . strtoupper($matches[2]);
        }, $text);
        return $text;
    }
}
