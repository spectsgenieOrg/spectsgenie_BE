<?php

namespace App\Controllers;

use App\Models\LenspackageModel;
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
        $db = db_connect();

        $lensPackageModel = new LenspackageModel($db);

        $data["lensPackages"] = $lensPackageModel->allLensPackages();

        return view('common/header')
            . view('pages/add-lenstype', $data)
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
        $lensPackageModel = new LenspackageModel($db);
        $data = array("lensType" => $lensTypeModel->getLensTypeById($id), "lensPackages" => $lensPackageModel->allLensPackages());

        return view('common/header') . view('pages/edit-lenstype', $data) . view('common/footer');
    }

    /** APIs */

    public function addlenstype()
    {
        $db = db_connect();

        $lensTypeModel = new LenstypeModel($db);

        $post = $this->request->getVar();

        $image = "";
        $lensPackageIds = "";

        foreach ($post['lens_package_ids'] as $package) {
            $lensPackageIds .= $package . ",";
        }

        $lensPackageIds = rtrim($lensPackageIds, ',');

        if ($this->request->getFile('icon')) {
            $file = $this->request->getFile('icon');

            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $image = 'uploads/' . $newName;
                $file->move(ROOTPATH . 'public/uploads', $newName);
            }
        }

        $post['icon'] = $image;
        $post['lens_package_ids'] = $lensPackageIds;


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
        $lensPackageIds = "";

        foreach ($post['lens_package_ids'] as $package) {
            $lensPackageIds .= $package . ",";
        }

        $lensPackageIds = rtrim($lensPackageIds, ',');


        if ($this->request->getFile('icon')) {
            $file = $this->request->getFile('icon');

            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $image = 'uploads/' . $newName;
                $file->move(ROOTPATH . 'public/uploads', $newName);
                $post['icon'] = $image;
            }
        }

        $post['lens_package_ids'] = $lensPackageIds;


        $post = json_decode(json_encode($post));

        $isSaved = $lensTypeModel->updateLensType($post, $id);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Lens type updated successfully" : "Error occurred while updating");

        echo json_encode($response);
    }
}
