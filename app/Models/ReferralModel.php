<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ReferralModel extends Model
{
    protected $db;
    public function __construct(ConnectionInterface &$db)
    {
        $this->db = &$db;
    }

    public function addReferralRecord($data)
    {
        return $this->db->table('sg_referral')->insert($data) ? true : false;
    }

    public function addReferralTransactionRecord($data)
    {
        return $this->db->table('sg_referral_transactions')->insert($data) ? true : false;
    }

    public function checkIfReferralCodeExists($code)
    {
        return $this->db->table('sg_referral')->select('*')->where('referral_code', $code)->get()->getNumRows() > 0 ? true : false;
    }

    public function updateReferralData($data, $code)
    {
        return $this->db->table('sg_referral')->where('referral_code', $code)->update($data) ? true : false;
    }

    public function getReferralDetailByCode($code)
    {
        return $this->db->table('sg_referral')->select('total_points')->where('referral_code', $code)->get()->getRow();
    }
}
