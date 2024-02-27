<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class CartModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function addCart($data)
    {
        return $this->db->table('sg_cart')->insert($data) ? true : false;
    }

    public function getCartByMultipleKeys($productId, $lensPackageId, $customerId, $lensTypeId)
    {
        return $this->db->table('sg_cart')->select('*')->getWhere(['product_id' => $productId, 'lens_package_id' => $lensPackageId, 'lens_type_id' => $lensTypeId, 'customer_id' => $customerId])->getRow();
    }

    public function getCartByID($cartId)
    {
        return $this->db->table('sg_cart')->select('*')->where('id', $cartId)->get()->getRow();
    }

    public function getCartByCustomerID($customerId)
    {
        return $this->db->table('sg_cart')->select('*')->where('customer_id', $customerId)->get()->getResult();
    }
}
