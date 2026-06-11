<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * POD Model
 * Handles Proof of Delivery uploads and admin verifications.
 */
class Pod_model extends CI_Model {

    protected $table = 'pod_submissions';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_order_id($order_id)
    {
        return $this->db
            ->select('pod.*, u.name as uploader_name, v.name as verifier_name')
            ->from('pod_submissions pod')
            ->join('users u', 'u.id = pod.uploaded_by', 'left')
            ->join('users v', 'v.id = pod.verified_by', 'left')
            ->where('pod.order_id', $order_id)
            ->get()
            ->row_array();
    }

    public function save_pod($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Verify Proof of Delivery.
     * Sets order status to 'completed' and sets vehicle status back to 'available'.
     */
    public function verify_pod($order_id, $verifier_id)
    {
        // Get vehicle ID associated with the order
        $order = $this->db->select('vehicle_id')->get_where('orders', ['id' => $order_id])->row_array();
        if (!$order) {
            return false;
        }

        $this->db->trans_start();

        // 1. Update POD submission
        $this->db->update($this->table, [
            'verified_by' => $verifier_id,
            'verified_at' => date('Y-m-d H:i:s')
        ], ['order_id' => $order_id]);

        // 2. Update Order Status to 'completed'
        $this->db->update('orders', [
            'status' => 'completed',
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $order_id]);

        // 3. Update vehicle status to 'available'
        if (!empty($order['vehicle_id'])) {
            $this->db->update('vehicles', ['status' => 'available'], ['id' => $order['vehicle_id']]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
