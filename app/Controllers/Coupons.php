<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\CouponsModel;


class Coupons extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, OPTIONS, PUT, DELETE');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    public function add()
    {
        $db = db_connect();
        $categoryModel = new CategoryModel($db);
        $categories = $categoryModel->allCategories();

        $data['categories'] = $categories;
        return view('common/header')
            . view('pages/add-coupon', $data)
            . view('common/footer');
    }

    public function create()
    {
        $db = db_connect();
        $couponModel = new CouponsModel($db);

        $post = $this->request->getVar();

        if ($couponModel->createCoupon($post)) {
            $response = array("status" => true, "message" => "Coupon created successfully");
        } else {
            $response = array("status" => false, "message" => "Invalid coupon data");
        }

        echo json_encode($response);
    }

    public function apply()
    {
        $db = db_connect();
        $couponModel = new CouponsModel($db);

        $post = json_decode($this->request->getBody());

        if ($couponModel->checkIfCouponIsValid($post->coupon_code)) {
            $coupon = $couponModel->getCouponByCode($post->coupon_code);
            $response = array("status" => true, "message" => "Coupon applied", "coupon" => $coupon);
        } else {
            $response = array("status" => false, "message" => "Invalid coupon data");
        }

        echo json_encode($response);
    }
}
