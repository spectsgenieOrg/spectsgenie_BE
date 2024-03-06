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

    public function getWishlistById($wishlistId) {
        return $this->db->table('sg_wishlist')->select('*')->where('id', $wishlistId)->get()->getrow();
    }

    public function deleteWishlistByIdAndCustomerId($wishlistId, $customerId, $db)
    {
        $this->db->table('sg_wishlist')->where(['id' => $wishlistId, 'customer_id' => $customerId])->delete();
        return $db->affectedRows();
    }
}
