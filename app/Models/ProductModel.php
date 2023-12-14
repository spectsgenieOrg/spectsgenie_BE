<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function allProducts()
    {
        return $this->db->table('sg_product')->select('*')->where('br_id', session()->get('user_id'))->get()->getResult();
    }

    public function addProduct($data)
    {
        return $this->db->table('sg_product')->insert($data) ? true : false;
    }

    public function addParentProduct($data)
    {
        return $this->db->table('sg_parent_product')->insert($data) ? true : false;
    }

    public function getBrands()
    {
        return $this->db->table('sg_brand')->select('bd_id, bd_name')->get()->getResult();
    }

    public function getCategories()
    {
        return $this->db->table('sg_category')->select('ca_id, ca_name')->get()->getResult();
    }

    public function getProduct($productId)
    {
        return $this->db->table('sg_product')->select('*')->where('pr_id', $productId)->get()->getRow();
    }

    public function getParentProducts()
    {
        return $this->db->table('sg_parent_product')->select('id, name')->get()->getResult();
    }

    public function getGenders()
    {
        return $this->db->table('sg_gender')->select('id, name')->get()->getResult();
    }

    public function updateProduct($data, $id)
    {
        return $this->db->table('sg_product')->where('pr_id', $id)->update($data) ? true : false;
    }
}
