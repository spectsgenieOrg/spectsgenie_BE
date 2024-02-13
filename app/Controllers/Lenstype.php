<?php

namespace App\Controllers;

use App\Models\LenspackageModel;
use App\Models\LenstypeModel;
use Exception;


class Lenstype extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    function uniqidReal($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public function add()
    {
        $db = db_connect();

        $lensPackageModel = new LenspackageModel($db);

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

    public function edit($id)
    {
        $db = db_connect();

        $lensTypeModel = new LenstypeModel($db);
        $data = array("lensType" => $lensTypeModel->getLensTypeById($id));

        return view('common/header') . view('pages/edit-lenstype', $data) . view('common/footer');
    }

    /** APIs */

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
        $post['uid'] = $this->uniqidReal();


        $post = json_decode(json_encode($post));

        $isSaved = $lensTypeModel->addLensType($post);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Lens type added successfully" : "Error occurred while adding");

        echo json_encode($response);
    }

    public function update($id)
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
                $post['icon'] = $image;
            }
        }


        $post = json_decode(json_encode($post));

        $isSaved = $lensTypeModel->updateLensType($post, $id);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Lens type updated successfully" : "Error occurred while updating");

        echo json_encode($response);
    }
}
