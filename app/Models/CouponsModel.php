<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class CouponsModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function checkIfCouponIsValid($couponCode)
    {
        return $this->db->table('sg_coupons')->select('*')->where(['coupon_code' => $couponCode, 'status' => '1'])->get()->getNumRows() > 0 ? true : false;
    }

    public function getCouponByCode($couponCode)
    {
        return $this->db->table('sg_coupons')->select('*')->where('coupon_code', $couponCode)->get()->getRow();
    }
}
