<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class LenstypeModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function allLensTypes()
    {
        return $this->db->table('sg_lens_type')->select('*')->get()->getResult();
    }

    public function addLensType($data)
    {
        return $this->db->table('sg_lens_type')->insert($data) ? true : false;
    }

    public function getLensTypeById($lensTypeId)
    {
        return $this->db->table('sg_lens_type')->select('*')->where('id', $lensTypeId)->get()->getRow();
    }
}
