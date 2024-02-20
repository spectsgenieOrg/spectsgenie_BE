<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function addWishlist($data)
    {
        return $this->db->table('sg_wishlist')->insert($data) ? true : false;
    }

    public function getWishlistsByCustomerId($customerId)
    {
        return $this->db->table('sg_wishlist')->select('*')->getWhere(['customer_id' => $customerId, 'is_active' => 'true'])->getResult();
    }

    public function getWishlistByCustomerAndProductId($customerId, $productId)
    {
        return $this->db->table('sg_wishlist')->select('*')->getWhere(['customer_id' => $customerId, 'product_id' => $productId, 'is_active' => 'true'])->getRow();
    }
}
