<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('pages/login');
    }

    public function action()
    {
        $data = ['title' => 'Dashboard'];  
        return view('pages/index', $data);
    }
}
