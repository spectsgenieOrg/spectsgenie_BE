<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class LenspackageModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function allLensPackages()
    {
        return $this->db->table('sg_lens_package')->select('*')->get()->getResult();
    }

    public function addLensPackage($data)
    {
        return $this->db->table('sg_lens_package')->insert($data) ? true : false;
    }

    public function getLensPackageById($lensPackageId)
    {
        return $this->db->table('sg_lens_package')->select('*')->where('id', $lensPackageId)->get()->getRow();
    }

    public function updateLensPackage($data, $id)
    {
        return $this->db->table('sg_lens_package')->where('id', $id)->update($data) ? true : false;
    }

    public function getLensPackageByLensTypeID($lensTypeID) {
        return $this->db->table('sg_lens_package')->like('lens_type_ids', $lensTypeID)->get()->getResult();
    }
}
