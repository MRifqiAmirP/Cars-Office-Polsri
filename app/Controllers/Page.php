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
<<<<<<< HEAD
            'role' => $this->getUserRole(),
=======
            'role' => $this->getUserRole()
>>>>>>> 540f18b7ba0e282322c9b0389c197ebb3c9672b6
        ];
        return view('pages/index', $data);
    }

        protected function getUserRole() {
        // Contoh: ambil dari session
        return session()->get('role') ?? 'guest';
    }

    public function calendar()
    {
        $data = ['title' => 'Calendar'];
        return view('pages/calendar', $data);
    }

    protected function getUserRole() {
        return session()->get('role') ?? 'guest';
    }
}
