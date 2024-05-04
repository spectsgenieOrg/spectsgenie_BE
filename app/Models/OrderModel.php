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

    public function addOrderTransactionDetail($data)
    {
        return $this->db->table('sg_order_transactions')->insert($data) ? true : false;
    }

    public function addOrderDetail($data)
    {
        return $this->db->table('sg_order_detail')->insert($data) ? true : false;
    }

    public function addShipRocketOrderDetail($data)
    {
        return $this->db->table('sg_shiprocket')->insert($data) ? true : false;
    }

    public function getOrdersByCustomerId($customerId)
    {
        $query = $this->db->query('select o.id as order_id, o.created_at as order_placed_on, o.total_amount, o.actual_total_amount, o.discount, o.order_status, o.address_id, o.order_id as order_number, o.customer_id, c.name as customer_name, group_concat(od.id) as order_detail_id from sg_orders_online o inner join sg_customer_online c on o.customer_id = c.id inner join sg_order_detail od on o.order_id = od.order_id where o.customer_id = ' . $customerId . ' group by o.id, o.order_id, o.customer_id order by o.id desc');
        return $query->getResultArray();
    }

    public function getOrderDetailById($orderDetailId)
    {
        $query = $this->db->query('SELECT
        od.id AS order_detail_id,
        p.pr_name as product_name,
        p.pr_sku as product_sku,
        p.pr_sprice as product_price,
        p.pr_image as product_images,
        pp.name as parent_product_name,
        lt.name as lens_type_name,
        lp.name as lens_package_name,
        lp.price as lens_package_price,
        lp.lens_material as lens_package_material,
        ct.ca_name as category_name
    FROM
        sg_order_detail od
    LEFT JOIN
        sg_product p ON od.product_id = p.pr_id
    LEFT JOIN
        sg_parent_product pp ON od.parent_product_id = pp.id
    LEFT JOIN
        sg_lens_type lt ON od.lens_type_id = lt.id
    LEFT JOIN
        sg_lens_package lp ON od.lens_package_id = lp.id
    LEFT JOIN
        sg_category ct ON od.category_id = ct.ca_id
    WHERE 
        od.id = ' . $orderDetailId . '');

        return $query->getRow();
    }
}
