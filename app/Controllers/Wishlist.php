<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\WishlistModel;
use App\Libraries\ImplementJWT as GlobalImplementJWT;

class Wishlist extends BaseController
{
    protected $objOfJwt;
    public function __construct()
    {
        $this->objOfJwt = new GlobalImplementJWT();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    public function add()
    {
        $db = db_connect();

        $wishlist = new WishlistModel($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            if ($data['id']) {
                $post = json_decode($this->request->getBody());
                $isProductAlreadyInWishlist = !is_null($wishlist->getWishlistByCustomerAndProductId($data['id'], $post->product_id));

                if ($isProductAlreadyInWishlist) {
                    $response = array("message" => "Product already in wishlist, can't be added multiple times", "status" => false);
                } else {
                    $post->is_active = "true";
                    $post->customer_id = $data['id'];
                    $data = $wishlist->addWishlist($post);

                    if ($data) {
                        $response = array("message" => "Product added to wishlist", "status" => true, "data" => $post);
                    } else {
                        $response = array("message" => "Product not added to wishlist", "status" => false);
                    }
                }
            } else {
                $response = array("message" => "Customer data not present, hence unauthorized access. Please login again.", "status" => false);
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }
        echo json_encode($response);
    }

    public function user($customerId)
    {
        $db = db_connect();

        $wishlistModel = new WishlistModel($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            if ($customerId === $data['id']) {
                $customerWishlists = $wishlistModel->getWishlistsByCustomerId($customerId);

                if ($customerWishlists) {
                    $productModel = new ProductModel($db);
                    foreach ($customerWishlists as $wishlist) {
                        $parentProduct = $productModel->getGroupedParentByProductID($wishlist->product_id);
                        $product = array($productModel->getProduct($wishlist->product_id));
                        $parentProduct->products = $product;
                        $wishlist->parent_product = $parentProduct;
                    }
                    $response = array('message' => 'Products list that are added in wishlist', 'data' => $customerWishlists, 'status' => true);
                } else {
                    $response = array('message' => 'Wishlist is empty', 'status' => false);
                }
            } else {
                $response = array("message" => "User is not authorized", "status" => false);
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }


        echo json_encode($response);
    }
}
