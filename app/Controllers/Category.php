<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
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
            . view('pages/add-category')
            . view('common/footer');
    }

    public function edit($id)
    {
        $db = db_connect();

        $categoryModel = new CategoryModel($db);
        $data = array("category" => $categoryModel->getCategoryById($id));

        return view('common/header') . view('pages/edit-category', $data) . view('common/footer');
    }

    public function all()
    {
        $db = db_connect();
        $categoryModel = new CategoryModel($db);
        $categories = $categoryModel->allCategories();

        $data['categories'] = $categories;
        return view('common/header')
            . view('pages/all-categories', $data)
            . view('common/footer');
    }

    // APIs
    public function addcategory()
    {
        $db = db_connect();
        $categoryModel = new CategoryModel($db);

        $post = $this->request->getVar();

        if ($categoryModel->checkIfCategoryAlreadyExist($post['ca_name'])) {
            $response = array("status" => false, "message" => "Category already exists");
        } else {
            $isCategoryAvailable = $categoryModel->addCategory($post);
            $response = array("status" => $isCategoryAvailable, "message" => $isCategoryAvailable ? "Category created successfully" : "Error occurred while creating");
        }

        echo json_encode($response);
    }

    public function update($categoryId)
    {
        $db = db_connect();
        $categoryModel = new CategoryModel($db);

        $post = $this->request->getVar();


        $isCategoryUpdated = $categoryModel->updateCategory($post, $categoryId);
        $response = array("status" => $isCategoryUpdated, "message" => $isCategoryUpdated ? "Category updated successfully" : "Error occurred while updating");


        echo json_encode($response);
    }
}
