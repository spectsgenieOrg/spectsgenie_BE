<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function checkIfCategoryAlreadyExist($categoryName)
    {
        return $this->db->table('sg_category')->select('*')->where(['ca_name' => $categoryName, 'ca_status' => '1'])->get()->getNumRows() > 0 ? true : false;
    }

    public function addCategory($data)
    {
        return $this->db->table('sg_category')->insert($data);
    }

    public function getCategoryById($id)
    {
        return $this->db->table('sg_category')->select('*')->where(['ca_id' => $id])->get()->getRow();
    }
    
    public function updateCategory($data, $id)
    {
        return $this->db->table('sg_category')->update($data, ['ca_id' => $id]);
    }

    public function allCategories()
    {
        return $this->db->table('sg_category')->select('*')->where(['ca_status' => '1'])->get()->getResult();
    }
}
