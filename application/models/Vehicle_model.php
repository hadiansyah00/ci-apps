<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vehicle Model
 * Handles CRUD and operational data for vehicles.
 */
class Vehicle_model extends CI_Model {

    protected $table = 'vehicles';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->order_by('plate_number', 'ASC')->get($this->table)->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function get_available_vehicles()
    {
        return $this->db->get_where($this->table, ['status' => 'available'])->result_array();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->update($this->table, $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        // Check if vehicle is linked to any orders
        $order_count = $this->db->where('vehicle_id', $id)->count_all_results('orders');
        if ($order_count > 0) {
            return false; // Cannot delete
        }
        $this->db->delete($this->table, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function is_plate_number_unique($plate_number, $exclude_id = null)
    {
        $this->db->where('plate_number', $plate_number);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) === 0;
    }

    public function count_active()
    {
        return $this->db->where('status', 'active')->count_all_results($this->table);
    }

    public function count_maintenance()
    {
        return $this->db->where('status', 'maintenance')->count_all_results($this->table);
    }

    public function count_available()
    {
        return $this->db->where('status', 'available')->count_all_results($this->table);
    }

    public function update_status($id, $status)
    {
        $this->db->update($this->table, ['status' => $status], ['id' => $id]);
        return $this->db->affected_rows();
    }
}
