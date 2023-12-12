<?php

namespace App\Controllers;

class Layout extends BaseController
{
    public function index() {
        return "Hello";
    }
    public function pageview($page = 'home')
    {
        if (!file_exists(APPPATH . 'Views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data = [
            'page_title' => ucfirst($page),
        ];

        return view('common/header')
            . view('pages/' . $page, $data)
            . view('common/footer');
    }
}
