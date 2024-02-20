<?php

namespace App\Controllers;

use App\Models\Authentication;

class Customer extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    public function crypt($string, $action)
    {
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public function signup()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        $post = json_decode($this->request->getBody());

        if ($auth->checkIfCustomerAlreadyExist($post->email)) {
            $response = array("status" => false, "message" => "Customer already exists");
        } else {
            $post->password = $this->crypt($post->password, 'e');
            $isRegistered = $auth->registercustomer($post);

            $response = array("status" => $isRegistered, "message" => $isRegistered ? "Customer added successfully" : "Error occurred while signing up, please retry");
        }

        echo json_encode($response);
    }

    public function login()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        $post = json_decode($this->request->getBody());
        $post->password = $this->crypt($post->password, 'e');
        $data = $auth->customerlogin($post->email, $post->password);

        if ($data) {
            echo json_encode(['status' => true, 'customer_data' => $data, 'message' => 'Successful Login']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Unsuccessful Login']);
        }
    }

    public function updateprofile($id)
    {
        $db = db_connect();

        $auth = new Authentication($db);

        $post = json_decode($this->request->getBody());

        $isSaved = $auth->updateProfile($post, $id);

        if ($isSaved) {
            $response = array("status" => true, "message" => "Profile successfully updated", "data" => $post);
        } else {
            $response = array("status" => false, "message" => "Error occurred while updating profile, please try again", "data" => []);
        }

        echo json_encode($response);
    }
}
