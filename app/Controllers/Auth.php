<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('pages/login', [
            'title' => 'Login Page - Ace Admin',
            'bodyClass' => 'login-layout'
        ]);
    }

    public function action()
    {
        $data = ['title' => 'Dashboard'];  
        return view('pages/index', $data);
    }
}
