<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order Model
 * Handles logistics order lifecycle and state transitions.
 */
class Order_model extends CI_Model {

    protected $table = 'orders';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all($filters = [])
    {
        $this->db->select('o.*, u.name as driver_name, v.plate_number, v.type as vehicle_type');
        $this->db->from('orders o');
        $this->db->join('users u', 'u.id = o.driver_id', 'left');
        $this->db->join('vehicles v', 'v.id = o.vehicle_id', 'left');
        $this->_apply_filters($filters);
        return $this->db->order_by('o.created_at', 'DESC')->get()->result_array();
    }

    public function get_paginated($limit, $offset, $filters = [])
    {
        $this->db->select('o.*, u.name as driver_name, v.plate_number, v.type as vehicle_type');
        $this->db->from('orders o');
        $this->db->join('users u', 'u.id = o.driver_id', 'left');
        $this->db->join('vehicles v', 'v.id = o.vehicle_id', 'left');
        $this->_apply_filters($filters);
        $this->db->order_by('o.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function count_all($filters = [])
    {
        $this->db->from('orders o');
        $this->_apply_filters($filters);
        return $this->db->count_all_results();
    }

    private function _apply_filters($filters = [])
    {
        if (!empty($filters['status'])) {
            $this->db->where('o.status', $filters['status']);
        }
        if (!empty($filters['driver_id'])) {
            $this->db->where('o.driver_id', $filters['driver_id']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('o.customer_name', $search);
            $this->db->or_like('o.origin', $search);
            $this->db->or_like('o.destination', $search);
            $this->db->group_end();
        }
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('o.*, u.name as driver_name, u.username as driver_username, v.plate_number, v.type as vehicle_type, v.capacity_weight, v.capacity_volume')
            ->from('orders o')
            ->join('users u', 'u.id = o.driver_id', 'left')
            ->join('vehicles v', 'v.id = o.vehicle_id', 'left')
            ->where('o.id', $id)
            ->get()
            ->row_array();
    }

    /**
     * Get active task for a driver
     * Driver can only have one active task at a time.
     */
    public function get_active_driver_task($driver_id)
    {
        return $this->db
            ->select('o.*, v.plate_number, v.type as vehicle_type, v.kir_expiry, v.tax_expiry, u_load.name as loading_verifier_name')
            ->from('orders o')
            ->join('vehicles v', 'v.id = o.vehicle_id', 'left')
            ->join('users u_load', 'u_load.id = o.loading_verified_by', 'left')
            ->where('o.driver_id', $driver_id)
            ->where_in('o.status', ['allocated', 'inspect_failed', 'ready', 'loading', 'in_transit', 'arrived', 'pod_submitted'])
            ->order_by('o.updated_at', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function create($data)
    {
        $data['status'] = 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update($this->table, $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Assign Driver and Vehicle to an order.
     * Also updates vehicle status to 'active'.
     */
    public function assign_fleet($order_id, $driver_id, $vehicle_id, $uang_jalan = 0)
    {
        $this->db->trans_start();

        // 1. Update Order Status & Assign driver/vehicle
        $this->db->update($this->table, [
            'driver_id'  => $driver_id,
            'vehicle_id' => $vehicle_id,
            'uang_jalan' => $uang_jalan,
            'status'     => 'allocated',
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $order_id]);

        // 2. Update Vehicle status to active
        $this->db->update('vehicles', ['status' => 'active'], ['id' => $vehicle_id]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Get available drivers (drivers who don't have active shipments)
     */
    public function get_available_drivers()
    {
        // Subquery for busy drivers
        $busy_drivers_query = $this->db
            ->select('driver_id')
            ->from('orders')
            ->where_in('status', ['allocated', 'inspect_failed', 'ready', 'loading', 'in_transit', 'arrived', 'pod_submitted'])
            ->where('driver_id IS NOT NULL')
            ->get_compiled_select();

        // Get all users with Driver role (role_id=5) who are not in busy list
        return $this->db
            ->select('id, name, username')
            ->from('users')
            ->where('role_id', 5)
            ->where('is_active', 1)
            ->where("id NOT IN ($busy_drivers_query)", NULL, FALSE)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
    }

    public function update_status($id, $status)
    {
        $this->db->update($this->table, [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Get CSS badge class and labels for order status
     */
    public function get_status_label($status)
    {
        $labels = [
            'pending'        => ['label' => 'Pending', 'class' => 'badge-inactive', 'color' => '#64748b'],
            'allocated'      => ['label' => 'Allocated (Assigned)', 'class' => 'badge-role', 'color' => '#6366f1'],
            'inspect_failed' => ['label' => 'Uji Kelayakan Gagal', 'class' => 'badge-inactive', 'color' => '#ef4444'],
            'ready'          => ['label' => 'Siap Jalan', 'class' => 'badge-active', 'color' => '#10b981'],
            'loading'        => ['label' => 'Sedang Muat', 'class' => 'badge-role', 'color' => '#8b5cf6'],
            'in_transit'     => ['label' => 'Dalam Perjalanan', 'class' => 'badge-active', 'color' => '#0ea5e9'],
            'arrived'        => ['label' => 'Tiba di Tujuan', 'class' => 'badge-active', 'color' => '#06b6d4'],
            'pod_submitted'  => ['label' => 'POD Dikirim (Menunggu Verifikasi)', 'class' => 'badge-role', 'color' => '#f59e0b'],
            'completed'      => ['label' => 'Selesai (POD Verified)', 'class' => 'badge-active', 'color' => '#22c55e'],
            'canceled'       => ['label' => 'Dibatalkan', 'class' => 'badge-inactive', 'color' => '#ef4444']
        ];

        return isset($labels[$status]) ? $labels[$status] : ['label' => 'Unknown', 'class' => 'badge-inactive', 'color' => '#64748b'];
    }

    /**
     * Get orders waiting for physical check (allocated or inspect_failed)
     */
    public function get_pending_inspections()
    {
        return $this->db
            ->select('o.*, u.name as driver_name, v.plate_number, v.type as vehicle_type')
            ->from('orders o')
            ->join('users u', 'u.id = o.driver_id', 'left')
            ->join('vehicles v', 'v.id = o.vehicle_id', 'left')
            ->where_in('o.status', ['allocated', 'inspect_failed'])
            ->order_by('o.updated_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Get orders currently loading and waiting for loading verification
     */
    public function get_pending_loadings()
    {
        return $this->db
            ->select('o.*, u.name as driver_name, v.plate_number, v.type as vehicle_type')
            ->from('orders o')
            ->join('users u', 'u.id = o.driver_id', 'left')
            ->join('vehicles v', 'v.id = o.vehicle_id', 'left')
            ->where('o.status', 'loading')
            ->order_by('o.updated_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Get recent inspections conducted by a checker
     */
    public function get_recent_inspections_by_checker($checker_id, $limit = 5)
    {
        return $this->db
            ->select('pti.*, o.customer_name, v.plate_number')
            ->from('pre_trip_inspections pti')
            ->join('orders o', 'o.id = pti.order_id', 'left')
            ->join('vehicles v', 'v.id = pti.vehicle_id', 'left')
            ->where('pti.checked_by', $checker_id)
            ->order_by('pti.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    /**
     * Get statistics for a specific driver (Completed trips, total money, current vehicle)
     */
    public function get_driver_stats($driver_id)
    {
        // 1. Total Completed Trips
        $trips = $this->db
            ->where('driver_id', $driver_id)
            ->where('status', 'completed')
            ->count_all_results('orders');

        // 2. Sum of Uang Jalan for Completed Trips
        $uang_jalan = $this->db
            ->select_sum('uang_jalan')
            ->where('driver_id', $driver_id)
            ->where('status', 'completed')
            ->get('orders')
            ->row_array();

        // 3. Current Assigned Vehicle (if any active shipment)
        $active_vehicle = $this->db
            ->select('v.plate_number')
            ->from('orders o')
            ->join('vehicles v', 'v.id = o.vehicle_id', 'left')
            ->where('o.driver_id', $driver_id)
            ->where_in('o.status', ['allocated', 'inspect_failed', 'ready', 'loading', 'in_transit', 'arrived', 'pod_submitted'])
            ->limit(1)
            ->get()
            ->row_array();

        return [
            'total_trips'      => $trips,
            'total_income'     => $uang_jalan['uang_jalan'] ?? 0,
            'assigned_vehicle' => $active_vehicle['plate_number'] ?? 'N/A'
        ];
    }

    /**
     * Get shipment history for a driver
     */
    public function get_driver_history($driver_id)
    {
        return $this->db
            ->select('o.*, v.plate_number')
            ->from('orders o')
            ->join('vehicles v', 'v.id = o.vehicle_id', 'left')
            ->where('o.driver_id', $driver_id)
            ->where('o.status', 'completed')
            ->order_by('o.updated_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Save coordinate log from driver
     */
    public function save_location_log($data)
    {
        return $this->db->insert('vehicle_location_logs', $data);
    }

    /**
     * Get latest recorded coordinate for an order
     */
    public function get_latest_location($order_id)
    {
        return $this->db
            ->where('order_id', $order_id)
            ->order_by('recorded_at', 'DESC')
            ->limit(1)
            ->get('vehicle_location_logs')
            ->row_array();
    }

    /**
     * Get all location crumbs for an order (to draw path)
     */
    public function get_route_breadcrumbs($order_id)
    {
        return $this->db
            ->where('order_id', $order_id)
            ->order_by('recorded_at', 'ASC')
            ->get('vehicle_location_logs')
            ->result_array();
    }
}
