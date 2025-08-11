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
    public function createCoupon($data)
    {
        return $this->db->table('sg_coupons')->insert($data);
    }

    public function updateCoupon($couponCode, $data)
    {
        return $this->db->table('sg_coupons')->where('coupon_code', $couponCode)->update($data);
    }

    public function getAllCoupons()
    {
        return $this->db->table('sg_coupons')->select('*')->get()->getResult();
    }

    public function deleteCoupon($couponCode)
    {
        return $this->db->table('sg_coupons')->where('coupon_code', $couponCode)->delete();
    }
    public function getCouponsByCategory($categoryId)
    {
        return $this->db->table('sg_coupons')->select('*')->where('ca_id', $categoryId)->get()->getResult();
    }
}