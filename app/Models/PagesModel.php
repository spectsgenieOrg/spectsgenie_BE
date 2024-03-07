<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class PagesModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function addPageContent($data)
    {
        return $this->db->table('sg_static_pages')->insert($data) ? true : false;
    }

    public function updateContent($data, $type)
    {
        return $this->db->table('sg_static_pages')->where('type', $type)->update($data);
    }

    public function getPageContentByType($type)
    {
        return $this->db->table('sg_static_pages')->select('paragraph')->where('type', $type)->get()->getRow();
    }
}
