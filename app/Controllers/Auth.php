<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends Controller
{
    public function auth()
    {
        $nip = $this->request->getPost('nip');
        $password = $this->request->getPost('password');

        if ($nip === 'admin' && $password === 'admin') {
            return $this->response->setJSON([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'Login berhasil!'
            ]);
        } else {
            return $this->response->setJSON([
                'statusCode' => 401,
                'status' => 'error',
                'message' => 'NIP atau Password salah'
            ]);
        }
    }

    public function logout(): ResponseInterface
    {
        // Logic to handle user logout, e.g., destroying session
        return redirect()->to(base_url('/login'));
    }
}
