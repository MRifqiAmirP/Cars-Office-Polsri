<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\Users;

class Auth extends BaseController
{
    protected $users;

    public function __construct()
    {
        $this->users = new Users();
    }

    public function login()
    {
        $rules = [
            'nip' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return responseError('Validasi gagal', 422, $this->validator->getErrors());
        }

        $nip = $this->request->getPost('nip');
        $password = $this->request->getPost('password');

        $user = $this->users->where('nip', $nip)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return responseError('NIP atau password salah', 401);
        }

        session()->set([
            'isLoggedIn'   => true,
            'userId'       => $user->id,
            'nip'          => $user->nip,
            'nama'         => $user->nama,
            'email'        => $user->email,
            'no_handphone' => $user->no_handphone,
            'jabatan'      => $user->jabatan,
            'role'         => $user->role
        ]);

        return responseSuccess('Login berhasil', $user->role);
    }

    public function logout(): ResponseInterface
    {
        session()->destroy();

        setcookie(session_name(), '', time() - 3600, '/');

        setcookie('csrf_cookie_name', '', time() - 3600, '/');

        return redirect()->to(base_url('/login'));
    }

    public function auth()
    {
        if (!session('isLoggedIn')) {
            return responseError('Harus login terlebih dahulu', 401, 'Unauthorized');
        }

        return responseSuccess('User is authenticated', [
            'userId' => session('userId'),
            'nip' => session('nip'),
            'nama' => session('nama'),
            'email' => session('email'),
            'no_handphone' => session('no_handphone'),
            'jabatan' => session('jabatan'),
            'user'  => session('role')
        ]);
    }

    public function me()
    {
        if (!session('isLoggedIn')) {
            return responseError('Harus login terlebih dahulu', 401, 'Unauthorized');
        }

        return responseSuccess('User data retrieved successfully', [
            'userId' => session('userId'),
            'nip' => session('nip'),
            'nama' => session('nama'),
            'email' => session('email'),
            'no_handphone' => session('no_handphone'),
            'jabatan' => session('jabatan')
        ]);
    }

    private function isApiRequest(RequestInterface $request): bool
    {
        // Cek custom header
        $clientType = $request->getHeaderLine('X-Client-Type');
        if ($clientType === 'browser') {
            return true; // Tetap return JSON untuk AJAX dari browser
        }

        $xRequestedWith = $request->getHeaderLine('X-Requested-With');
        if ($xRequestedWith === 'XMLHttpRequest') {
            return true;
        }

        $acceptHeader = $request->getHeaderLine('Accept');
        if (strpos($acceptHeader, 'application/json') !== false) {
            return true;
        }

        $userAgent = $request->getHeaderLine('User-Agent');
        $apiTools = ['postman', 'insomnia', 'thunder-client', 'curl'];
        $userAgentLower = strtolower($userAgent);

        foreach ($apiTools as $tool) {
            if (strpos($userAgentLower, $tool) !== false) {
                return true;
            }
        }

        return false;
    }
}
