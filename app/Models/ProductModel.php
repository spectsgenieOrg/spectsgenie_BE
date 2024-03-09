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

    public function allProducts($platform)
    {
        return $this->db->table('sg_product')->select('*')->orderBy('pr_id', 'DESC')->getWhere(['br_id' => session()->get('user_id'), 'platform' => $platform])->getResult();
    }

    public function allContacts()
    {
        return $this->db->table('sg_contactlens')->select('*')->orderBy('id', 'DESC')->getWhere(['br_id' => session()->get('user_id')])->getResult();
    }

    public function fetchAllContacts()
    {
        return $this->db->table('sg_contactlens')->select('*')->orderBy('id', 'DESC')->get()->getResult();
    }

    public function addProduct($data)
    {
        return $this->db->table('sg_product')->insert($data) ? true : false;
    }

    public function addContactLens($data)
    {
        return $this->db->table('sg_contactlens')->insert($data) ? true : false;
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

    public function getContactLensById($productId)
    {
        return $this->db->table('sg_contactlens')->select('*')->where('id', $productId)->get()->getRow();
    }

    public function getProductsByParentId($parentId)
    {
        return $this->db->table('sg_product')->select('*')->where('parent_product_id', $parentId)->get()->getResult();
    }

    public function getProductByParentIdandSlug($parentId, $slug)
    {
        return $this->db->table('sg_product')->select('*')->getWhere(['parent_product_id' => $parentId, 'slug' => $slug])->getRow();
    }

    public function getParentProductByName($name)
    {
        return $this->db->table('sg_parent_product')->select('*')->where('name', $name)->get()->getRow();
    }

    public function getParentProductById($id)
    {
        return $this->db->table('sg_parent_product')->select('*')->where('id', $id)->get()->getRow();
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

    public function updateContactLens($data, $id)
    {
        return $this->db->table('sg_contactlens')->where('id', $id)->update($data) ? true : false;
    }

    public function getGroupedParentProduct($categoryId, $genderId)
    {
        return $this->db->table('sg_product as sp')->join('sg_parent_product as pp', 'sp.parent_product_id = pp.id')->select('pp.id as parent_product_id, pp.name as parent_product_name')->where('sp.ca_id', $categoryId)->like('sp.sg_gender_ids', '%' . $genderId . '%')->groupBy('pp.id, pp.name')->get()->getResult();
    }

    public function getProductByCategoryGenderParent($categoryId, $genderId, $parentId)
    {
        return $this->db->table('sg_product as sp')->select('sp.*')->like('sp.sg_gender_ids', '%' . $genderId . '%')->getWhere(['sp.ca_id' => $categoryId, 'sp.parent_product_id' => $parentId])->getResult();
    }

    public function getCategoryDetail($categoryId)
    {
        return $this->db->table('sg_category')->select('*')->where('ca_id', $categoryId)->get()->getRow();
    }

    public function getGroupedParentByProductID($productId)
    {
        return $this->db->table('sg_product as sp')->join('sg_parent_product as pp', 'sp.parent_product_id = pp.id')->select('pp.id as parent_product_id, pp.name as parent_product_name')->where('sp.pr_id', $productId)->groupBy('pp.id, pp.name')->get()->getRow();
    }
}
