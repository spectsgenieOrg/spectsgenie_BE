<?php

namespace App\Controllers;

use App\Models\WishlistModel;
use App\Models\Authentication;
use App\Libraries\ImplementJWT as GlobalImplementJWT;
use App\Models\CartModel;
use App\Models\ReferralModel;

class Customer extends BaseController
{
    protected $objOfJwt;

    public function __construct()
    {
        $this->objOfJwt = new GlobalImplementJWT();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
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
        $referralModel = new ReferralModel($db);

        $post = json_decode($this->request->getBody());

        if ($auth->checkIfCustomerAlreadyExist($post->email)) {
            $response = array("status" => false, "message" => "Customer already exists");
        } else {
            $referralCode = $post->referral_code;
            unset($post->referral_code);
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $post->referral_code = "SG-" . substr(str_shuffle($permitted_chars), 0, 8); // This is going to be assigned to the new user
            $post->password = $this->crypt($post->password, 'e');
            $isRegistered = $auth->registercustomer($post);


            // If no referral code, then make points as 0
            if ($referralCode === "") {
                $referralData = (object) array("referral_code" => $post->referral_code, "total_points" => 0);
                $referralModel->addReferralRecord($referralData);

                $response = array("status" => $isRegistered, "message" => $isRegistered ? "Customer added successfully" : "Error occurred while signing up, please retry", "is_referred" => false);
            }

            if ($referralCode !== "") {
                if ($referralModel->checkIfReferralCodeExists($referralCode)) {
                    //add new user's referral points
                    $referralData = (object) array("referral_code" => $post->referral_code, "total_points" => 250);
                    $referralModel->addReferralRecord($referralData);

                    //Update the referrer's user points
                    $referrerUser = $referralModel->getReferralDetailByCode($referralCode);
                    $totalPoints = (int) $referrerUser->total_points + 250;

                    $existingUserUpdatedReferralData = (object) array("total_points" => $totalPoints);
                    $referralModel->updateReferralData($existingUserUpdatedReferralData, $referralCode);

                    //Adding transactions of referral just to keep in our records
                    $referralTransactionData = (object) array("referred_by" => $referralCode, "referred_to" => $post->referral_code);
                    $referralModel->addReferralTransactionRecord($referralTransactionData);

                    $response = array("status" => $isRegistered, "message" => $isRegistered ? "Customer added successfully" : "Error occurred while signing up, please retry", "is_referred" => true);
                } else {
                    // In case of invalid referral code
                    $referralData = (object) array("referral_code" => $post->referral_code, "total_points" => 0);
                    $referralModel->addReferralRecord($referralData);
                    $response = array("status" => $isRegistered, "message" => $isRegistered ? "Customer added successfully, but the provided referral code was invalid" : "Error occurred while signing up, please retry", "is_referred" => false);
                }
            }
        }

        echo json_encode($response);
    }

    public function address()
    {
        $db = db_connect();

        $auth = new Authentication($db);

        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();

            $data = $this->objOfJwt->DecodeToken($token);

            $post = json_decode($this->request->getBody());

            $post->customer_id = $data['id'];

            $isAddressSaved = $auth->addCustomerAddress($post);
            if ($isAddressSaved) {
                $response = array("status" => true, "message" => "Address added successfully", "data" => $post);
            } else {
                $response = array("status" => false, "message" => "Some error occurred while adding address, please try again");
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }

        echo json_encode($response);
    }

    public function getAllAddress()
    {
        $db = db_connect();

        $auth = new Authentication($db);


        if ($this->request->hasHeader('Authorization')) {
            $token = $this->request->header('Authorization')->getValue();

            $data = $this->objOfJwt->DecodeToken($token);

            $customerAddress = $auth->fetchCustomerAddressess($data['id']);
            if ($customerAddress) {
                $response = array("status" => true, "message" => "List of customer's addresses", "data" => $customerAddress);
            } else {
                $response = array("status" => false, "message" => "Some error occurred while fetching the addressess, please try again");
            }
        } else {
            $response = array("message" => "Unauthorized access", "status" => false);
        }

        echo json_encode($response);
    }

    public function login()
    {
        $db = db_connect();

        $auth = new Authentication($db);
        $wishlistModel = new WishlistModel($db);
        $cart = new CartModel($db);

        $post = json_decode($this->request->getBody());

        $post->password = $this->crypt($post->password, 'e');

        $data = $auth->customerlogin($post->email, $post->password);

        if ($data) {
            $token = $this->objOfJwt->GenerateToken($data);
            $wishlists = $wishlistModel->getWishlistsByCustomerId($data->id);
            $itemsInCart = $cart->getCartByCustomerID($data->id);
            echo json_encode(['status' => true, 'auth_token' => $token, "wishlist_count" => count($wishlists), "cart_items_count" => count($itemsInCart), "customer_data" => $data, 'message' => 'Successful Login']);
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

    public function fetchfile()
    {
        $urlParam = $this->request->getUri()->getQuery();

        $url = explode("%2F", explode("=", $urlParam)[1]);
        $finalPath = FCPATH . $url[3] . "/". $url[4];
        $type = pathinfo($finalPath, PATHINFO_EXTENSION);
        $data = file_get_contents($finalPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        echo $base64;
    }
}
