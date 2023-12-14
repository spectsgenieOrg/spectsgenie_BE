<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function login()
    {
        return view('common/header')
            . view('pages/login')
            . view('common/footer');
    }
}
