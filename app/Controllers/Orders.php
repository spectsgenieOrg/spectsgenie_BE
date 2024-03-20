<?php

namespace App\Controllers;

use App\Libraries\ImplementJWT as GlobalImplementJWT;
use App\Models\CartModel;
use App\Models\OrderModel;

class Orders extends BaseController
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

    public function add()
    {
        $db = db_connect();

        $orders = new OrderModel($db);
        $cart = new CartModel($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            $post = json_decode($this->request->getBody());
            $orderId = "SG_ORDER_" . round(microtime(true));


            $idx = 0;
            $areAllItemsSaved = false;

            foreach ($post->items as $item) {
                $item->order_id = $orderId;
                $orders->addOrderDetail($item);
                $idx++;

                if ($idx === count($post->items)) {
                    $areAllItemsSaved = true;
                }
            }

            if ($areAllItemsSaved) {
                $orderData = (object) array("customer_id" => $data['id'], "order_id" => $orderId, "address_id" => $post->address_id, "discount" => $post->discount, "discount_code" => $post->discount_code, "actual_total_amount" => $post->total_amount, "total_amount" => (int) $post->total_amount - (int) $post->discount, "order_status" => "pending");

                $isOrderCreated = $orders->addOrder($orderData);
                if ($isOrderCreated) {
                    $cart->removeCartItemsByCustomerID($data['id']);
                    $response = array("message" => "Order Successful", "status" => true);
                } else {
                    $response = array("message" => "Some error occurred, please re-order", "status" => false);
                }
            } else {
                $response = array("message" => "Order Failed", "status" => true);
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }
        echo json_encode($response);
    }
}
