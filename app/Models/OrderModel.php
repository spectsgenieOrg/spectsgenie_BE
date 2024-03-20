<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $db;

    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function addOrder($data)
    {
        return $this->db->table('sg_orders_online')->insert($data) ? true : false;
    }

    public function addOrderDetail($data)
    {
        return $this->db->table('sg_order_detail')->insert($data) ? true : false;
    }
}
