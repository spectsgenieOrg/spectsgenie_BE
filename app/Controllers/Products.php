<?php

namespace App\Controllers;

use App\Models\LenspackageModel;
use App\Models\LenstypeModel;
use App\Models\ProductModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Products extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    public $baseURL = 'https://newpos.spectsgenie.com/';

    /* Load UI pages */

    public function all()
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $products = $productModel->allProducts('online');

        foreach ($products as $product) {
            $product->parent_product = $productModel->getParentProductById($product->parent_product_id);
            $product->productCategory = $productModel->getCategoryDetail($product->ca_id);
        }

        $data['products'] = $products;

        return view('common/header')
            . view('pages/all-products', $data)
            . view('common/footer');
    }

    public function offline()
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $products = $productModel->allProducts('');

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
        $lensTypeModel = new LensTypeModel($db);

        $lensTypes = $lensTypeModel->allLensTypes();

        $data = array("brands" => $productModel->getBrands(), "genders" => $productModel->getGenders(), "categories" => $productModel->getCategories(), "parents" => $productModel->getParentProducts(), "lensTypes" => $lensTypes);
        return view('common/header')
            . view('pages/add-product', $data)
            . view('common/footer');
    }

    public function edit($id)
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $lensTypeModel = new LenstypeModel($db);

        $data = array("product" => $productModel->getProduct($id), "brands" => $productModel->getBrands(), "genders" => $productModel->getGenders(), "categories" => $productModel->getCategories(), "parents" => $productModel->getParentProducts(), "lensTypes" => $lensTypeModel->allLensTypes());
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
        $images = "";
        $gender = "";
        $lensTypeIds = "";
        $post = $this->request->getVar();
        foreach ($post['sg_gender_ids'] as $gen) {
            $gender .= $gen . ",";
        }

        $gender = rtrim($gender, ',');

        foreach ($post['lens_type_ids'] as $lensTypeId) {
            $lensTypeIds .= $lensTypeId . ",";
        }

        $lensTypeIds = rtrim($lensTypeIds, ',');

        if ($this->request->getFileMultiple('images')) {
            $files = $this->request->getFileMultiple('images');

            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $images .= 'uploads/' . $newName . ',';
                    $file->move(ROOTPATH . 'public/uploads', $newName);
                }
            }
        }

        $imgList = rtrim($images, ',');

        $post['pr_image'] = $imgList;
        $post['sg_gender_ids'] = $gender;
        $post['lens_type_ids'] = $lensTypeIds;

        $post = json_decode(json_encode($post));

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
        $lensTypeIds = "";
        $areImagesSet = false;
        $post = $this->request->getVar();
        foreach ($post['sg_gender_ids'] as $gen) {
            $gender .= $gen . ",";
        }

        $gender = rtrim($gender, ',');

        foreach ($post['lens_type_ids'] as $lensTypeId) {
            $lensTypeIds .= $lensTypeId . ",";
        }

        $lensTypeIds = rtrim($lensTypeIds, ',');

        if ($this->request->getFileMultiple('images')) {
            $files = $this->request->getFileMultiple('images');

            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $images .= 'uploads/' . $newName . ',';
                    $file->move(ROOTPATH . 'public/uploads', $newName);
                    $areImagesSet = true;
                }
            }
        }

        if ($areImagesSet) {
            $imgList = rtrim($images, ',');

            $post['pr_image'] = $imgList;
        }


        $post['sg_gender_ids'] = $gender;
        $post['lens_type_ids'] = $lensTypeIds;

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

            foreach ($parent->products as $product) {
                if ($product->pr_image !== "") {
                    $images = explode(",", $product->pr_image);
                    $i = 0;
                    foreach ($images as $image) {
                        $images[$i] = $this->baseURL . $image;
                        $i++;
                    }

                    $product->pr_image = $images;
                } else {
                    $product->pr_image = [];
                }
            }
        }

        $response = array("status" => count($parentProducts) > 0 ? true : false, "message" => count($parentProducts) > 0 ? "List of products" : "List is empty", "data" => $parentProducts);

        echo json_encode($response);
    }

    public function getProductByParentAndSlug($parentName, $slug)
    {
        $db = db_connect();

        $productModel = new ProductModel($db);
        $lensTypeModel = new LenstypeModel($db);
        $lensPackageModel = new LenspackageModel($db);

        $parentProduct = $productModel->getParentProductByName($parentName);

        $currentProduct = $productModel->getProductByParentIdandSlug($parentProduct->id, $slug);

        if ($currentProduct->pr_image !== "") {
            $currentProductImages = explode(",", $currentProduct->pr_image);
            $i = 0;
            foreach ($currentProductImages as $image) {
                $currentProductImages[$i] = $this->baseURL . $image;
                $i++;
            }
            $currentProduct->pr_image = $currentProductImages;
        } else {
            $currentProduct->pr_image = [];
        }


        if ($currentProduct->lens_type_ids !== "") {
            $lensTypeForCurrentProduct = explode(",", $currentProduct->lens_type_ids);
            $lensTypeIndex = 0;
            foreach ($lensTypeForCurrentProduct as $lensTypeID) {
                $lensTypeForCurrentProduct[$lensTypeIndex] = $lensTypeModel->getLensTypeById($lensTypeID);

                $lensTypeForCurrentProduct[$lensTypeIndex]->packages = $lensPackageModel->getLensPackageByLensTypeID($lensTypeForCurrentProduct[$lensTypeIndex]->uid);
                $lensTypeForCurrentProduct[$lensTypeIndex]->icon = $this->baseURL . $lensTypeForCurrentProduct[$lensTypeIndex]->icon;
                $lensTypeIndex++;
            }

            $currentProduct->lens_types = $lensTypeForCurrentProduct;
        } else {
            $currentProduct->lens_types = [];
        }

        $productsBySameParent = $productModel->getProductsByParentId($parentProduct->id);

        $similarProducts = array_filter($productsBySameParent, function ($product) use ($currentProduct) {
            return $product->pr_id !== $currentProduct->pr_id;
        });

        foreach ($similarProducts as $product) {
            if ($product->pr_image !== "") {
                $images = explode(",", $product->pr_image);
                $i = 0;

                foreach ($images as $image) {
                    $images[$i] = $this->baseURL . $image;
                    $i++;
                }

                $product->pr_image = $images;
            } else {
                $product->pr_image = [];
            }

            if ($product->lens_type_ids !== "") {
                $lensTypeForProduct = explode(",", $product->lens_type_ids);
                $lenstypeidx = 0;
                foreach ($lensTypeForProduct as $ltID) {
                    $lensTypeForProduct[$lenstypeidx] = $lensTypeModel->getLensTypeById($ltID);
                    $lensTypeForProduct[$lenstypeidx]->packages = $lensPackageModel->getLensPackageByLensTypeID($lensTypeForProduct[$lenstypeidx]->uid);
                    $lensTypeForProduct[$lenstypeidx]->icon = $this->baseURL . $lensTypeForProduct[$lenstypeidx]->icon;
                    $lenstypeidx++;
                }
                $product->lens_types = $lensTypeForProduct;
            } else {
                $product->lens_types = [];
            }
        }

        $response = array("status" => true, "message" => "Product Details", "current_product" => $currentProduct, "similar_products" => array_values($similarProducts), "similar_products_count" => count($similarProducts));

       echo json_encode($response);
    }

    public function addfromexcel()
    {
        $file = $this->request->getFile('productsSheet');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());

        $dataFromActiveSheet = $spreadsheet->getActiveSheet()->toArray();

        foreach ($dataFromActiveSheet as $row) {
            var_dump($row);
        }
    }
}
