<?php

namespace App\Controllers;

use App\Models\Authentication;

class Admin extends BaseController
{
    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access_token, Cache-Control, Content-Type');
        header('Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE');
    }

    public function register()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        $post = json_decode($this->request->getBody());

        if ($auth->checkIfAdminAlreadyExist($post->email)) {
            $response = array("status" => false, "message" => "Admin already exists");
        } else {
            $post->password = $auth->crypt($post->password, 'e');
            $isRegistered = $auth->register($post);

            $response = array("status" => $isRegistered, "message" => $isRegistered ? "Admin created successfully" : "Error occurred while creating");
        }

        echo json_encode($response);
    }

    public function login()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        $post = json_decode($this->request->getBody());
        $post->password = md5($post->password);
        $data = $auth->login($post->email, $post->password);

        if ($data) {

            $newdata = array(
                'user_id'     => $data->br_id,
                'email' => $data->br_email,
            );

            session()->set($newdata);
            echo json_encode(['status' => true, 'message' => 'Successful Login']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Unsuccessful Login']);
        }
    }
}
