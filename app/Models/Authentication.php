<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class Authentication extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
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

    public function login($email, $password)
    {
        return $this->db->table('sg_branch')->select('br_email, br_id')->getWhere(["br_email" => $email, "br_password" => $password])->getRow();
    }

    public function register($data)
    {
        return $this->db->table('admin')->insert($data) ? true : false;
    }

    public function checkIfAdminAlreadyExist($email)
    {
        return $this->db->table('sg_branch')->select('*')->where('br_email', $email)->get()->getNumRows() > 0 ? true : false;
    }
}
