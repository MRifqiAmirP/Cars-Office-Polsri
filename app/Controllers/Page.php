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
        $data = ['title' => 'Dashboard'];
        return view('pages/index', $data);
    }

    public function calendar()
    {
        $data = ['title' => 'Calendar'];
        return view('pages/calendar', $data);
    }
}
