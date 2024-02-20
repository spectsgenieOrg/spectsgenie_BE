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


    // Customer Queries
    public function registercustomer($data)
    {
        return $this->db->table('sg_customer_online')->insert($data) ? true : false;
    }

    public function checkIfCustomerAlreadyExist($email)
    {
        return $this->db->table('sg_customer_online')->select('*')->where('email', $email)->get()->getNumRows() > 0 ? true : false;
    }

    public function customerlogin($email, $password)
    {
        return $this->db->table('sg_customer_online')->select('id, email, mobile')->getWhere(["email" => $email, "password" => $password])->getRow();
    }

    public function updateProfile($data, $id)
    {
        return $this->db->table('sg_customer_online')->where('id', $id)->update($data) ? true : false;
    }
}
