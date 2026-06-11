<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public Tracking Controller
 * Allows customers/receivers to track shipments in real-time without logging in.
 */
class Tracking extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Order_model', 'Inspection_model', 'Pod_model']);
        $this->load->helper('url');
    }

    public function index()
    {
        $order_id = $this->input->get('order_id', TRUE);
        $order = null;
        $inspection = null;
        $pod = null;
        $error = null;

        if ($order_id) {
            // Strip any prefix (e.g. TNP-00012 or #00012 -> 12)
            $clean_id = preg_replace('/[^0-9]/', '', $order_id);
            if ($clean_id) {
                // Load detailed order info
                $order = $this->Order_model->get_by_id($clean_id);
                if ($order) {
                    $inspection = $this->Inspection_model->get_by_order_id($clean_id);
                    $pod = $this->Pod_model->get_by_order_id($clean_id);
                } else {
                    $error = "Nomor Order #{$order_id} tidak ditemukan. Mohon periksa kembali nomor Anda.";
                }
            } else {
                $error = "Nomor Order tidak valid. Masukkan angka saja.";
            }
        }

        $data = [
            'title'      => 'Lacak Pengiriman Kargo &mdash; PT Tirta Nusa Persada',
            'order_id'   => $order_id,
            'order'      => $order,
            'inspection' => $inspection,
            'pod'        => $pod,
            'error'      => $error
        ];

        $this->load->view('tracking/index', $data);
    }

    /**
     * Get live location coordinates for an order (AJAX GET)
     */
    public function get_live_location($order_id)
    {
        $clean_id = preg_replace('/[^0-9]/', '', $order_id);
        $order = $this->Order_model->get_by_id($clean_id);

        if (!$order) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Order not found']));
            return;
        }

        $latest_loc = $this->Order_model->get_latest_location($clean_id);
        $breadcrumbs = $this->Order_model->get_route_breadcrumbs($clean_id);

        $response = [
            'status'                => $order['status'],
            'origin_latitude'       => $order['origin_latitude'] !== null ? (float) $order['origin_latitude'] : null,
            'origin_longitude'      => $order['origin_longitude'] !== null ? (float) $order['origin_longitude'] : null,
            'destination_latitude'  => $order['destination_latitude'] !== null ? (float) $order['destination_latitude'] : null,
            'destination_longitude' => $order['destination_longitude'] !== null ? (float) $order['destination_longitude'] : null,
            'current_latitude'      => $latest_loc ? (float)$latest_loc['latitude'] : null,
            'current_longitude'     => $latest_loc ? (float)$latest_loc['longitude'] : null,
            'recorded_at'           => $latest_loc ? $latest_loc['recorded_at'] : null,
            'breadcrumbs'           => array_map(function($row) {
                return [
                    'latitude'  => (float)$row['latitude'],
                    'longitude' => (float)$row['longitude'],
                    'recorded_at' => $row['recorded_at']
                ];
            }, $breadcrumbs)
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
