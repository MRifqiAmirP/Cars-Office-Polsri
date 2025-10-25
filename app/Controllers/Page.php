<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Page extends Controller
{
    public function login()
    {
        $data = [
            'title' => 'Login',
            'bodyClass' => 'login-layout'
        ];
        return view('pages/login', $data);
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'css' => 'dashboard.css',
            'role' => $this->getUserRole()
        ];
        return view('pages/index', $data);
    }

    public function user() {
        $data = [
            'title' => 'User Management',
            'role' => $this->getUserRole()
        ];

        return view('pages/admin/user/user', $data);
    }

    public function cars() {
        $data = [
            'title' => 'Car Managemenet',
            'role' => $this->getUserRole()
        ];

        return view('pages/admin/cars/cars', $data);
    }

    protected function getUserRole() {
        return session()->get('role') ?? 'guest';
    }
}
