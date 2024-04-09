<?php

namespace App\Controllers;

use App\Libraries\ImplementJWT as GlobalImplementJWT;
use App\Models\Authentication;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\ProductModel;

class Orders extends BaseController
{
    protected $objOfJwt;
    public $baseURL = 'https://newpos.spectsgenie.com/';


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
        $auth = new Authentication($db);
        $product = new ProductModel($db);

        $curlRequest = \Config\Services::curlrequest([
            'baseURI' => 'https://apiv2.shiprocket.in/v1/external/',
        ]);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            $post = json_decode($this->request->getBody());
            $orderId = "SG_ORDER_" . round(microtime(true));


            $idx = 0;
            $areAllItemsSaved = false;

            $orderItems = [];


            foreach ($post->items as $item) {
                $item->order_id = $orderId;
                $orders->addOrderDetail($item);

                $itemDetail = $product->getProduct($item->product_id);
                $orderedItems = (object) array('name' => $itemDetail->pr_name, 'sku' => $itemDetail->pr_sku, 'selling_price' =>  $itemDetail->pr_sprice, 'units' => 1);

                $orderItems[] = $orderedItems;

                $idx++;

                if ($idx === count($post->items)) {
                    $areAllItemsSaved = true;
                }
            }

            if ($areAllItemsSaved) {
                $orderData = (object) array("customer_id" => $data['id'], "order_id" => $orderId, "address_id" => $post->address_id, "discount" => $post->discount, "discount_code" => $post->discount_code, "actual_total_amount" => $post->total_amount, "total_amount" => (int) $post->total_amount - (int) $post->discount, "order_status" => "pending");

                $customerAddress = $auth->getCustomerAddressByAddressId($post->address_id);

                //Shiprocket data
                $shipRocketOrderData = array(
                    "order_id" => $orderId,
                    "order_date" => date('yy-m-d'),
                    "pickup_location" => "Primary",
                    "billing_customer_name" => $data['name'],
                    "billing_last_name" => "",
                    "billing_city" => $customerAddress->city,
                    "billing_pincode" => $customerAddress->pincode,
                    "billing_state" => $customerAddress->state,
                    "billing_country" => $customerAddress->country,
                    "billing_email" => $data['email'],
                    "billing_phone" => $data['mobile'],
                    "billing_address" => $customerAddress->address_line_1 . ', ' . $customerAddress->address_line_2,
                    "shipping_is_billing" => true,
                    "order_items" => $orderItems,
                    "payment_method" => "COD",
                    "sub_total" => (int) $post->total_amount - (int) $post->discount,
                    "length" => 10.0,
                    "breadth" => 10.0,
                    "height" => 10.0,
                    "weight" => 0.25,
                );

                $shipRocketLoginRequest = $curlRequest->request('POST', 'auth/login', ['json' => ['email' => 'sajal.suraj@gmail.com', 'password' => 'S.S.suraj123']]);
                $shipRocketLoginResponse = json_decode($shipRocketLoginRequest->getBody());


                $shipRocketCreateOrder = $curlRequest->request('POST', 'orders/create/adhoc', ['json' => $shipRocketOrderData, 'headers' => ['Authorization' => 'Bearer ' . $shipRocketLoginResponse->token . '', 'Content-Type' => 'application/json']]);
                $shipRocketOrderResponse = json_decode($shipRocketCreateOrder->getBody());



                $shipRocketOrderResponseArray = json_decode(json_encode($shipRocketOrderResponse), true);
                $shipRocketOrderDataResponse = array(...$shipRocketOrderResponseArray);
                $orders->addShipRocketOrderDetail($shipRocketOrderDataResponse);


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

    public function fetch($customerId)
    {
        $db = db_connect();

        $orderModel = new OrderModel($db);

        if ($this->request->hasHeader('Authorization')) {

            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            if ($data && $data['id'] && $data['id'] === $customerId) {
                $orders = $orderModel->getOrdersByCustomerId($data['id']);

                if (count($orders) > 0) {
                    foreach ($orders as $key => $order) {
                        $orderDetailIds = explode(",", $order['order_detail_id']);
                        $itemDetailArr = [];
                        foreach ($orderDetailIds as $orderDetailId) {
                            $item_detail = $orderModel->getOrderDetailById($orderDetailId);
                            if ($item_detail->product_images !== "") {
                                $images = explode(",", $item_detail->product_images);
                                $i = 0;

                                foreach ($images as $image) {
                                    $images[$i] = $this->baseURL . $image;
                                    $i++;
                                }

                                $item_detail->product_images = $images;
                            } else {
                                $item_detail->product_images = [];
                            }
                            $itemDetailArr[] = $item_detail;
                        }
                        $orders[$key]['ordered_items'] = $itemDetailArr;
                    }

                    $response = array("message" => "List of orders", "status" => true, "data" => $orders);
                } else {
                    $response = array("message" => "Orders not available", "status" => false);
                }
            } else {
                $response = array("message" => "Unauthorized access", "status" => false);
            }
        } else {
            $response = array("message" => "Token not available", "status" => false);
        }
        echo json_encode($response);
    }
}
