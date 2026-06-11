<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Inspection Model
 * Handles pre-trip safety checklists.
 */
class Inspection_model extends CI_Model {

    protected $table = 'pre_trip_inspections';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_inspections()
    {
        return $this->db
            ->select('pti.*, o.customer_name, o.origin, o.destination, v.plate_number, u.name as checker_name')
            ->from('pre_trip_inspections pti')
            ->join('orders o', 'o.id = pti.order_id', 'left')
            ->join('vehicles v', 'v.id = pti.vehicle_id', 'left')
            ->join('users u', 'u.id = pti.checked_by', 'left')
            ->order_by('pti.created_at', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_paginated($limit, $offset, $filters = [])
    {
        $this->db
            ->select('pti.*, o.customer_name, o.origin, o.destination, v.plate_number, u.name as checker_name')
            ->from('pre_trip_inspections pti')
            ->join('orders o', 'o.id = pti.order_id', 'left')
            ->join('vehicles v', 'v.id = pti.vehicle_id', 'left')
            ->join('users u', 'u.id = pti.checked_by', 'left');
        
        $this->_apply_filters($filters);

        return $this->db
            ->order_by('pti.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }

    public function count_all($filters = [])
    {
        $this->db
            ->from('pre_trip_inspections pti')
            ->join('orders o', 'o.id = pti.order_id', 'left')
            ->join('vehicles v', 'v.id = pti.vehicle_id', 'left')
            ->join('users u', 'u.id = pti.checked_by', 'left');
        
        $this->_apply_filters($filters);
        
        return $this->db->count_all_results();
    }

    private function _apply_filters($filters = [])
    {
        if (!empty($filters['status'])) {
            $this->db->where('pti.status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('v.plate_number', $search);
            $this->db->or_like('o.customer_name', $search);
            $this->db->or_like('o.origin', $search);
            $this->db->or_like('o.destination', $search);
            $this->db->or_like('u.name', $search);
            $this->db->group_end();
        }
    }

    public function get_by_order_id($order_id)
    {
        return $this->db
            ->select('pti.*, u.name as checker_name, u.role_id')
            ->from('pre_trip_inspections pti')
            ->join('users u', 'u.id = pti.checked_by', 'left')
            ->where('pti.order_id', $order_id)
            ->order_by('pti.created_at', 'DESC')
            ->get()
            ->row_array();
    }

    public function save_inspection($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}
