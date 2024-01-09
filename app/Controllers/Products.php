<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    /* Load UI pages */

    public function all()
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $products = $productModel->allProducts();

        foreach ($products as $product) {
            $product->parent_product = $productModel->getParentProductById($product->parent_product_id);
            $product->productCategory = $productModel->getCategoryDetail($product->ca_id);
        }

        $data['products'] = $products;

        return view('common/header')
            . view('pages/all-products', $data)
            . view('common/footer');
    }

    public function addparent()
    {
        return view('common/header')
            . view('pages/add-parent-product')
            . view('common/footer');
    }

    public function add()
    {
        $db = db_connect();

        $productModel = new ProductModel($db);

        $data = array("brands" => $productModel->getBrands(), "genders" => $productModel->getGenders(), "categories" => $productModel->getCategories(), "parents" => $productModel->getParentProducts());
        return view('common/header')
            . view('pages/add-product', $data)
            . view('common/footer');
    }

    public function edit($id)
    {
        $db = db_connect();

        $productModel = new ProductModel($db);

        $data = array("product" => $productModel->getProduct($id), "brands" => $productModel->getBrands(), "genders" => $productModel->getGenders(), "categories" => $productModel->getCategories(), "parents" => $productModel->getParentProducts());
        return view('common/header')
            . view('pages/edit-product', $data)
            . view('common/footer');
    }

    /** ---- Load UI pages ends ---- */


    /* APIs List */

    public function addparentdetails()
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $post = json_decode($this->request->getBody());
        $isSaved = $productModel->addParentProduct($post);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Product parent added successfully" : "Error occurred while creating");

        echo json_encode($response);
    }

    public function addproduct()
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $post = json_decode($this->request->getBody());

        $isSaved = $productModel->addProduct($post);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Product added successfully" : "Error occurred while adding");

        echo json_encode($response);
    }

    public function update($id)
    {
        $db = db_connect();

        $productModel = new ProductModel($db);

        $images = "";
        $gender = "";
        $post = $this->request->getVar();
        foreach ($post['sg_gender_ids'] as $gen) {
            $gender .= $gen . ",";
        }

        $gender = rtrim($gender, ',');

        if ($this->request->getFileMultiple('images')) {
            $files = $this->request->getFileMultiple('images');

            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $images .= 'uploads/' . $newName . ',';
                    $file->move(WRITEPATH . 'uploads', $newName);
                }
            }
        }

        $imgList = rtrim($images, ',');

        $post['pr_image'] = $imgList;
        $post['sg_gender_ids'] = $gender;

        $post = json_decode(json_encode($post));

        $isSaved = $productModel->updateProduct($post, $id);

        $response = array("status" => $isSaved, "message" => $isSaved ? "Product updated successfully" : "Error occurred while updating");

        echo json_encode($response);
    }

    public function getProductByCategory($category, $gender)
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $parentProducts = $productModel->getGroupedParentProduct($category, $gender);

        foreach ($parentProducts as $parent) {
            $parent->products = $productModel->getProductByCategoryGenderParent($category, $gender, $parent->parent_product_id);
        }

        $response = array("status" => count($parentProducts) > 0 ? true : false, "message" => count($parentProducts) > 0 ? "List of products" : "List is empty", "data" => $parentProducts);

        echo json_encode($response);
    }
}
