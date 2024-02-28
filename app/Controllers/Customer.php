<?php

namespace App\Controllers;

use App\Models\Authentication;
use App\Libraries\ImplementJWT as GlobalImplementJWT;

class Customer extends BaseController
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
            $token = $this->objOfJwt->GenerateToken($data);
            echo json_encode(['status' => true, 'auth_token' => $token, 'message' => 'Successful Login']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Unsuccessful Login']);
        }
    }

    public function profile()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            $profile = $auth->getCustomerById($data['id']);

            if ($profile) {
                $response = array("status" => true, "message" => "Customer details", "data" => $profile);
            } else {
                $response = array("status" => false, "message" => "Customer doesn't exist");
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }

        echo json_encode($response);
    }

    public function updateprofile()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();
            $data = $this->objOfJwt->DecodeToken($token);

            $post = json_decode($this->request->getBody());

            $isSaved = $auth->updateProfile($post, $data['id']);

            if ($isSaved) {
                $response = array("status" => true, "message" => "Profile successfully updated", "data" => $post);
            } else {
                $response = array("status" => false, "message" => "Error occurred while updating profile, please try again", "data" => []);
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }

        echo json_encode($response);
    }
}
