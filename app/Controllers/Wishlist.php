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
        header('Access-Control-Allow-Headers: Authorization, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, OPTIONS, PUT, DELETE');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    public $baseURL = 'https://newpos.spectsgenie.com/';

    private function getCategoryName($category)
    {
        if (strtolower($category) === "sunglass") {
            $categoryName = "sunglasses";
        }
        if (strtolower($category) === "eyeglass") {
            $categoryName = "eyeglasses";
        }
        if (strtolower($category) === "lens") {
            $categoryName = "lens";
        }
        if (strtolower($category) === "accesories") {
            $categoryName = "accessories";
        }
        if (strtolower($category) === "contact lens") {
            $categoryName = "contacts";
        }

        return $categoryName;
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

    public function user()
    {
        $db = db_connect();

        $wishlistModel = new WishlistModel($db);
        $productModel = new ProductModel($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);
            $flattenedWishlistArray = [];
            $customerWishlists = $wishlistModel->getWishlistsByCustomerId($data['id']);
            $flattenedWishlistArray = array_column($customerWishlists, 'product_id');

            if ($customerWishlists) {
                $productModel = new ProductModel($db);
                foreach ($customerWishlists as $wishlist) {
                    $parentProduct = $productModel->getGroupedParentByProductID($wishlist->product_id);
                    $products = array($productModel->getProduct($wishlist->product_id));
                    foreach ($products as $product) {
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
                        $product->is_wishlisted = false;

                        if (in_array($product->pr_id, $flattenedWishlistArray)) {
                            $product->is_wishlisted = true;
                        }
                    }
                    $category = $productModel->getCategoryDetail($products[0]->ca_id);
                    $wishlist->category_name = $this->getCategoryName($category->ca_name);
                    $parentProduct->products = $products;
                    $wishlist->parent_product = $parentProduct;
                }
                $response = array('message' => 'Products list that are added in wishlist', 'data' => $customerWishlists, 'status' => true);
            } else {
                $response = array('message' => 'Wishlist is empty', 'status' => false);
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }


        echo json_encode($response);
    }
}
