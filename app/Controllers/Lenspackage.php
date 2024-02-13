<?php

namespace App\Controllers;

use App\Models\LenspackageModel;
use App\Models\LenstypeModel;

class Lenspackage extends BaseController
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

        $lensTypeModel = new LenstypeModel($db);
        $data['lensTypes'] = $lensTypeModel->allLensTypes();

        return view('common/header')
            . view('pages/add-lenspackage', $data)
            . view('common/footer');
    }

    public function edit($id)
    {
        $db = db_connect();
        $lensPackageModel = new LensPackageModel($db);
        $lensTypeModel = new LenstypeModel($db);
        $data['lensTypes'] = $lensTypeModel->allLensTypes();
        $lensPackage = $lensPackageModel->getLensPackageById($id);
        $data['lensPackage'] = $lensPackage;
        return view('common/header')
            . view('pages/edit-lenspackage', $data)
            . view('common/footer');
    }

    public function all()
    {
        $db = db_connect();
        $lensPackageModel = new LensPackageModel($db);
        $lensPackages = $lensPackageModel->allLensPackages();

        $data['lensPackages'] = $lensPackages;
        return view('common/header')
            . view('pages/all-lenspackages', $data)
            . view('common/footer');
    }

    /** APIs List */

    // Add new lens package
    public function addlenspackage()
    {
        $db = db_connect();
        $lensPackageModel = new LensPackageModel($db);
        $post = $this->request->getVar();
        $lensTypes = "";
        foreach ($post['lens_type_ids'] as $lt) {
            $lensTypes .= $lt . ",";
        }

        $lensTypes = rtrim($lensTypes, ',');

        $post['lens_type_ids'] = $lensTypes;

        $isLensPackageAdded = $lensPackageModel->addLensPackage($post);

        $response = array("status" => $isLensPackageAdded, "message" => $isLensPackageAdded ? "Lens package added successfully" : "Error occurred while creating");
        echo json_encode($response);
    }

    // Get single package by ID
    public function get($id)
    {
        $db = db_connect();
        $lensPackageModel = new LensPackageModel($db);
        $lensPackage = $lensPackageModel->getLensPackageById($id);

        $response = array("status" => $lensPackage !== NULL, "message" => $lensPackage !== NULL ? "Lens package details" : "Detail not available", "data" => $lensPackage);
        echo json_encode($response);
    }

    //Get all packages
    public function fetchall()
    {
        $db = db_connect();
        $lensPackageModel = new LensPackageModel($db);
        $lensPackages = $lensPackageModel->allLensPackages();

        $response = array("status" => count($lensPackages) > 0, "message" => count($lensPackages) > 0 ? "Lens package list" : "Lens package list not available", "data" => $lensPackages);
        echo json_encode($response);
    }

    // Update package
    public function update($id)
    {
        $db = db_connect();

        $lensPackageModel = new LensPackageModel($db);

        $post = $this->request->getVar();
        $lensTypes = "";
        foreach ($post['lens_type_ids'] as $lt) {
            $lensTypes .= $lt . ",";
        }

        $lensTypes = rtrim($lensTypes, ',');

        $post['lens_type_ids'] = $lensTypes;

        $isSaved = $lensPackageModel->updateLensPackage($post, $id);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Lens package updated successfully" : "Error occurred while updating");

        echo json_encode($response);
    }

    // Get Lens packages by Lens type ID
    // public function getlenspackagebylenstypeid($lensTypeId) {
    //     $db = db_connect();

    //     $lensTypeModel = new LenstypeModel($db);
    //     $lensTypeData = 
    // }
}
