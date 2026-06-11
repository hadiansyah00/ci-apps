<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Driver Tasks Controller
 * Handles mobile-friendly workflow for drivers in the field.
 */
class Tasks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_permission('driver.tasks');

        $this->load->model(['Order_model', 'Vehicle_model', 'Inspection_model', 'Pod_model']);
        $this->load->helper('form');
    }

    public function index()
    {
        $driver_id = $this->auth_lib->get_user_id();
        $task = $this->Order_model->get_active_driver_task($driver_id);

        $stats = $this->Order_model->get_driver_stats($driver_id);
        $history = $this->Order_model->get_driver_history($driver_id);

        $inspection = null;
        if ($task) {
            $inspection = $this->Inspection_model->get_by_order_id($task['id']);
        }

        $data = [
            'title'      => 'Tugas Pengiriman Saya',
            'task'       => $task,
            'stats'      => $stats,
            'history'    => $history,
            'inspection' => $inspection,
            'toast'      => $this->auth_lib->get_toast(),
        ];
        
        // Note: Driver page uses a clean layout, but for MVC simplicity, 
        // we can reuse layouts/app.php or a simple standalone view.
        // Let's use layouts/app but with specific mobile CSS or layout.
        $data['page'] = 'driver/tasks/index';
        $this->load->view('layouts/app', $data);
    }

    /**
     * Update transit status (READY -> LOADING or IN_TRANSIT -> ARRIVED)
     */
    public function update_status($order_id, $new_status)
    {
        $driver_id = $this->auth_lib->get_user_id();
        $task = $this->Order_model->get_by_id($order_id);

        if (!$task || $task['driver_id'] != $driver_id) {
            $this->auth_lib->set_toast('error', 'Tugas tidak valid.');
            redirect('driver/tasks');
            return;
        }

        // Validate transitions
        if ($new_status === 'loading' && $task['status'] === 'ready') {
            $this->Order_model->update_status($order_id, 'loading');
            $this->auth_lib->set_toast('success', 'Proses pemuatan kargo telah dimulai.');
        } elseif ($new_status === 'arrived' && $task['status'] === 'in_transit') {
            $this->Order_model->update_status($order_id, 'arrived');
            $this->auth_lib->set_toast('success', 'Status diperbarui: Tiba di tujuan. Silahkan bongkar muatan.');
        } else {
            $this->auth_lib->set_toast('error', 'Transisi status tidak diperbolehkan.');
        }

        redirect('driver/tasks');
    }

    /**
     * Show upload POD form
     */
    public function pod($order_id)
    {
        $driver_id = $this->auth_lib->get_user_id();
        $task = $this->Order_model->get_by_id($order_id);

        if (!$task || $task['driver_id'] != $driver_id || $task['status'] !== 'arrived') {
            $this->auth_lib->set_toast('error', 'Tidak dapat mengunggah bukti terima.');
            redirect('driver/tasks');
            return;
        }

        $data = [
            'title' => 'Unggah Bukti Penerimaan (POD)',
            'page'  => 'driver/tasks/upload_pod',
            'task'  => $task,
            'toast' => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    /**
     * Process POD Upload
     */
    public function upload_pod($order_id)
    {
        $driver_id = $this->auth_lib->get_user_id();
        $task = $this->Order_model->get_by_id($order_id);

        if (!$task || $task['driver_id'] != $driver_id || $task['status'] !== 'arrived') {
            $this->auth_lib->set_toast('error', 'Upload POD ditolak.');
            redirect('driver/tasks');
            return;
        }

        $this->form_validation->set_rules('receiver_name', 'Nama Penerima', 'required|trim|max_length[150]');

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Silahkan isi nama penerima kargo.');
            redirect('driver/tasks/pod/' . $order_id);
            return;
        }

        // Create upload directory if it does not exist
        $upload_path = './assets/uploads/pod/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => 5120, // 5MB limit
            'encrypt_name'  => TRUE
        ];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('pod_image')) {
            $error = $this->upload->display_errors('', '');
            $this->auth_lib->set_toast('error', 'Gagal mengunggah foto: ' . $error);
            redirect('driver/tasks/pod/' . $order_id);
            return;
        }

        $upload_data = $this->upload->data();
        $file_name   = $upload_data['file_name'];

        $pod_data = [
            'order_id'      => $order_id,
            'uploaded_by'   => $driver_id,
            'receiver_name' => $this->input->post('receiver_name', TRUE),
            'file_path'     => 'assets/uploads/pod/' . $file_name,
            'notes'         => $this->input->post('notes', TRUE),
        ];

        $this->db->trans_start();

        // 1. Save POD Submission
        $this->Pod_model->save_pod($pod_data);

        // 2. Transition order status to 'pod_submitted'
        $this->Order_model->update_status($order_id, 'pod_submitted');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Delete uploaded file if DB failed
            @unlink($upload_path . $file_name);
            $this->auth_lib->set_toast('error', 'Gagal memproses pengiriman POD ke server.');
            redirect('driver/tasks/pod/' . $order_id);
        } else {
            $this->auth_lib->set_toast('success', 'Bukti pengiriman (POD) berhasil dikirim. Terima kasih!');
            redirect('driver/tasks');
        }
    }

    /**
     * Log driver coordinates in real-time (AJAX POST)
     */
    public function log_location($order_id)
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid request method']));
            return;
        }

        $driver_id = $this->auth_lib->get_user_id();
        $task = $this->Order_model->get_by_id($order_id);

        if (!$task || $task['driver_id'] != $driver_id || $task['status'] !== 'in_transit') {
            $this->output
                ->set_status_header(403)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Unauthorized or invalid order status']));
            return;
        }

        $latitude  = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');

        if (empty($latitude) || empty($longitude)) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Latitude and longitude are required']));
            return;
        }

        $log_data = [
            'order_id'  => $order_id,
            'driver_id' => $driver_id,
            'latitude'  => (float) $latitude,
            'longitude' => (float) $longitude,
        ];

        $this->Order_model->save_location_log($log_data);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'message' => 'Location logged successfully']));
    }
}
