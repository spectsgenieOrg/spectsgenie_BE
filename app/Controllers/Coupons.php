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

    public function all()
    {
        $db = db_connect();
        $couponModel = new CouponsModel($db);
        $categoryModel = new CategoryModel($db);

        $coupons = $couponModel->getAllCoupons();
        $categories = $categoryModel->allCategories();

        // Map category IDs to names for quick lookup
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->ca_id] = $category->ca_name;
        }

        // Append ca_name to each coupon if ca_id is present
        foreach ($coupons as &$coupon) {
            if (isset($coupon->ca_id) && isset($categoryMap[$coupon->ca_id])) {
                $coupon->ca_name = $categoryMap[$coupon->ca_id];
            }
        }
        unset($coupon);

        $data['coupons'] = $coupons;

        return view('common/header')
            . view('pages/all-coupons', $data)
            . view('common/footer');
    }

    public function edit($couponCode)
    {
        $db = db_connect();
        $couponModel = new CouponsModel($db);
        $categoryModel = new CategoryModel($db);

        $coupon = $couponModel->getCouponByCode($couponCode);
        if (!$coupon) {
            return redirect()->to(base_url('coupons/all'))->with('error', 'Coupon not found');
        }

        $categories = $categoryModel->allCategories();
        $data['coupon'] = $coupon;
        $data['categories'] = $categories;

        return view('common/header')
            . view('pages/edit-coupon', $data)
            . view('common/footer');
    }

    public function update()
    {
        $db = db_connect();
        $couponModel = new CouponsModel($db);

        $post = $this->request->getVar();

        if ($couponModel->updateCoupon($post['coupon_code'], $post)) {
            $response = array("status" => true, "message" => "Coupon updated successfully");
        } else {
            $response = array("status" => false, "message" => "Invalid coupon data");
        }

        echo json_encode($response);
    }

    public function delete($couponCode)
    {
        $db = db_connect();
        $couponModel = new CouponsModel($db);

        if ($couponModel->deleteCoupon($couponCode)) {
            $response = array("status" => true, "message" => "Coupon deleted successfully");
        } else {
            $response = array("status" => false, "message" => "Failed to delete coupon");
        }

        return redirect()->to(base_url('coupons/all'))->with('response', $response);
    }
}
