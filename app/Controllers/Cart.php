<?php

namespace App\Controllers;

use App\Models\LenstypeModel;
use App\Models\CartModel;
use App\Models\ProductModel;
use App\Libraries\ImplementJWT as GlobalImplementJWT;
use App\Models\LenspackageModel;

class Cart extends BaseController
{
    protected $objOfJwt;

    public function __construct()
    {
        $this->objOfJwt = new GlobalImplementJWT();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, OPTIONS, PUT, DELETE');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    public $baseURL = 'https://newpos.spectsgenie.com/';

    public function add()
    {
        $db = db_connect();

        $cart = new CartModel($db);
        $productModel = new ProductModel($db);
        $lensPackageModel = new LenspackageModel($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            $post = json_decode($this->request->getBody());

            $post->customer_id = $data['id'];
            $isItemAddedInCart = $cart->getCartByMultipleKeys($post->product_id, $post->lens_package_id, $post->customer_id, $post->lens_type_id);

            if ($isItemAddedInCart !== NULL) {
                $response = array("message" => "Item already present in the cart, try adding another product", "status" => false);
            } else {
                $totalTax = 0;
                $item = $productModel->getProduct($post->product_id);
                $lensPackage = $lensPackageModel->getLensPackageById($post->lens_package_id);
                $totalTax = $item->ca_id !== "9" ? (float) $item->pr_sprice * 0.12 : (float) $item->pr_sprice * 0.18;

                $totalTax = $totalTax + (float) $lensPackage->price * 0.12;
                $post->price = (int) $post->price + (int) $totalTax;
                $isSaved = $cart->addCart($post);

                if ($isSaved) {
                    $lastAddedCartItemRow = $cart->getCartByMultipleKeys($post->product_id, $post->lens_package_id, $post->customer_id, $post->lens_type_id);
                    $response = array("message" => "Added item to cart successfully", "data" => $lastAddedCartItemRow, "status" => true);
                } else {
                    $response = array("message" => "Not added to cart, please try again", "status" => false);
                }
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }
        echo json_encode($response);
    }

    public function items()
    {
        $db = db_connect();

        $cart = new CartModel($db);
        $productModel = new ProductModel($db);
        $lensTypeModel = new LenstypeModel($db);
        $lensPackageModel = new LenspackageModel($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            $itemsInCart = $cart->getCartByCustomerID($data['id']);

            $itemsList = [];
            $i = 0;
            if (count($itemsInCart) < 1) {
                $response = array("message" => "Cart is empty", "status" => false);
            } else {
                foreach ($itemsInCart as $item) {
                    $parentProduct = $productModel->getGroupedParentByProductID($item->product_id);
                    $itemsList[$i]['parent_product'] = $parentProduct;
                    $itemsList[$i]['product'] = $productModel->getProduct($item->product_id);

                    if ($itemsList[$i]['product']->pr_image !== "") {
                        $currentProductImages = explode(",", $itemsList[$i]['product']->pr_image);
                        $j = 0;
                        foreach ($currentProductImages as $image) {
                            $currentProductImages[$j] = $this->baseURL . $image;
                            $j++;
                        }
                        $itemsList[$i]['product']->pr_image = $currentProductImages;
                    } else {
                        $itemsList[$i]['product']->pr_image = [];
                    }

                    $itemsList[$i]['lens_type'] = $lensTypeModel->getLensTypeById($item->lens_type_id);
                    $itemsList[$i]['lens_package'] = $lensPackageModel->getLensPackageById($item->lens_package_id);
                    $itemsList[$i]['total_price'] = $item->price;
                    $i++;
                }
                $response = array("message" => "Cart items list", "data" => $itemsList, "status" => true);
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }
        echo json_encode($response);
    }
}
