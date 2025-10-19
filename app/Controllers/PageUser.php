<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PageUser extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'role' => $this->getUserRole()
        ];
        
        return view('pages/user/dashboard', $data);
    }

    public function service() {
        $data = [
            'title' => 'Service',
            'role' => $this->getUserRole()
        ];
        
        return view('pages/user/service/service', $data);
    }

    protected function getUserRole() {
        return session()->get('role');
    }
}
