<?php

namespace App\Controllers;

use App\Models\LenstypeModel;

class Lenstype extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    public function add()
    {
        return view('common/header')
            . view('pages/add-lenstype')
            . view('common/footer');
    }

    public function all()
    {
        $db = db_connect();

        $lensTypesModel = new LenstypeModel($db);
        $lensTypes = $lensTypesModel->allLensTypes();

        $data['lensTypes'] = $lensTypes;

        return view('common/header')
            . view('pages/all-lenstypes', $data)
            . view('common/footer');
    }

    public function addlenstype()
    {
        $db = db_connect();

        $lensTypeModel = new LenstypeModel($db);

        $post = $this->request->getVar();

        $image = "";

        if ($this->request->getFile('icon')) {
            $file = $this->request->getFile('icon');

            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $image = 'uploads/' . $newName;
                $file->move(ROOTPATH . 'public/uploads', $newName);
            }
        }

        $post['icon'] = $image;


        $post = json_decode(json_encode($post));

        $isSaved = $lensTypeModel->addLensType($post);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Lens type added successfully" : "Error occurred while adding");

        echo json_encode($response);
    }
}
