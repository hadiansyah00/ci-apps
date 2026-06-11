<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Orders Controller
 * Handles logistics orders, assignment, SJ printing, and POD verification.
 */
class Orders extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('orders.view');
        $this->load->model(['Order_model', 'Vehicle_model', 'Inspection_model', 'Pod_model']);
    }

    public function index()
    {
        $filters = [
            'status'    => $this->input->get('status', TRUE),
            'search'    => $this->input->get('search', TRUE),
        ];

        $total = $this->Order_model->count_all($filters);
        $per_page = 5;
        
        // CI3 Pagination Setup
        $this->load->library('pagination');
        $config = [
            'base_url'             => base_url('admin/orders') . '?',
            'total_rows'           => $total,
            'per_page'             => $per_page,
            'page_query_string'    => TRUE,
            'query_string_segment' => 'page',
            'use_page_numbers'     => TRUE,
            'first_link'           => '&laquo;',
            'last_link'            => '&raquo;',
            'next_link'            => '&rsaquo;',
            'prev_link'            => '&lsaquo;',
            'full_tag_open'        => '<div class="pagination-custom">',
            'full_tag_close'       => '</div>',
            'cur_tag_open'         => '<span class="current-page">',
            'cur_tag_close'        => '</span>',
        ];
        $this->pagination->initialize($config);

        $page   = max(1, (int)$this->input->get('page'));
        $offset = ($page - 1) * $per_page;

        $orders = $this->Order_model->get_paginated($per_page, $offset, $filters);

        $data = [
            'title'      => 'Order Logistik',
            'orders'     => $orders,
            'total'      => $total,
            'filters'    => $filters,
            'pagination' => $this->pagination->create_links(),
            'toast'      => $this->auth_lib->get_toast(),
        ];

        if ($this->input->is_ajax_request()) {
            $this->load->view('admin/orders/table', $data);
        } else {
            $data['page'] = 'admin/orders/index';
            $this->load->view('layouts/app', $data);
        }
    }

    public function create()
    {
        $this->auth_lib->require_permission('orders.create');
        $data = [
            'title'   => 'Tambah Order Baru',
            'page'    => 'admin/orders/form',
            'order'   => null,
            'is_edit' => false,
            'toast'   => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function store()
    {
        $this->auth_lib->require_permission('orders.create');

        $this->form_validation->set_rules([
            ['field' => 'customer_name',     'label' => 'Nama Pelanggan',  'rules' => 'required|trim|max_length[150]'],
            ['field' => 'cargo_description', 'label' => 'Deskripsi Kargo', 'rules' => 'required|trim'],
            ['field' => 'weight',            'label' => 'Berat (Ton)',     'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'volume',            'label' => 'Volume (CBM)',    'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'origin',            'label' => 'Kota Asal',       'rules' => 'required|trim|max_length[255]'],
            ['field' => 'destination',       'label' => 'Kota Tujuan',     'rules' => 'required|trim|max_length[255]'],
            ['field' => 'eta',               'label' => 'Estimasi Tiba',   'rules' => 'required|trim'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/orders/create');
            return;
        }

        $data = [
            'customer_name'         => $this->input->post('customer_name', TRUE),
            'cargo_description'     => $this->input->post('cargo_description', TRUE),
            'weight'                => $this->input->post('weight', TRUE),
            'volume'                => $this->input->post('volume', TRUE),
            'origin'                => $this->input->post('origin', TRUE),
            'destination'           => $this->input->post('destination', TRUE),
            'eta'                   => $this->input->post('eta', TRUE),
            'origin_latitude'       => $this->input->post('origin_latitude') ? (float)$this->input->post('origin_latitude') : NULL,
            'origin_longitude'      => $this->input->post('origin_longitude') ? (float)$this->input->post('origin_longitude') : NULL,
            'destination_latitude'  => $this->input->post('destination_latitude') ? (float)$this->input->post('destination_latitude') : NULL,
            'destination_longitude' => $this->input->post('destination_longitude') ? (float)$this->input->post('destination_longitude') : NULL,
        ];

        if ($this->Order_model->create($data)) {
            $this->auth_lib->set_toast('success', 'Order kargo baru berhasil dibuat.');
            redirect('admin/orders');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal membuat order kargo.');
            redirect('admin/orders/create');
        }
    }

    public function edit($id)
    {
        $this->auth_lib->require_permission('orders.edit');

        $order = $this->Order_model->get_by_id($id);
        if (!$order) {
            $this->auth_lib->set_toast('error', 'Order tidak ditemukan.');
            redirect('admin/orders');
            return;
        }

        // Only editable if status is pending
        if ($order['status'] !== 'pending') {
            $this->auth_lib->set_toast('error', 'Hanya order berstatus Pending yang dapat diubah.');
            redirect('admin/orders');
            return;
        }

        $data = [
            'title'   => 'Edit Order',
            'page'    => 'admin/orders/form',
            'order'   => $order,
            'is_edit' => true,
            'toast'   => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function update($id)
    {
        $this->auth_lib->require_permission('orders.edit');

        $order = $this->Order_model->get_by_id($id);
        if (!$order || $order['status'] !== 'pending') {
            $this->auth_lib->set_toast('error', 'Order tidak valid untuk diedit.');
            redirect('admin/orders');
            return;
        }

        $this->form_validation->set_rules([
            ['field' => 'customer_name',     'label' => 'Nama Pelanggan',  'rules' => 'required|trim|max_length[150]'],
            ['field' => 'cargo_description', 'label' => 'Deskripsi Kargo', 'rules' => 'required|trim'],
            ['field' => 'weight',            'label' => 'Berat (Ton)',     'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'volume',            'label' => 'Volume (CBM)',    'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'origin',            'label' => 'Kota Asal',       'rules' => 'required|trim|max_length[255]'],
            ['field' => 'destination',       'label' => 'Kota Tujuan',     'rules' => 'required|trim|max_length[255]'],
            ['field' => 'eta',               'label' => 'Estimasi Tiba',   'rules' => 'required|trim'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/orders/edit/' . $id);
            return;
        }

        $data = [
            'customer_name'         => $this->input->post('customer_name', TRUE),
            'cargo_description'     => $this->input->post('cargo_description', TRUE),
            'weight'                => $this->input->post('weight', TRUE),
            'volume'                => $this->input->post('volume', TRUE),
            'origin'                => $this->input->post('origin', TRUE),
            'destination'           => $this->input->post('destination', TRUE),
            'eta'                   => $this->input->post('eta', TRUE),
            'origin_latitude'       => $this->input->post('origin_latitude') ? (float)$this->input->post('origin_latitude') : NULL,
            'origin_longitude'      => $this->input->post('origin_longitude') ? (float)$this->input->post('origin_longitude') : NULL,
            'destination_latitude'  => $this->input->post('destination_latitude') ? (float)$this->input->post('destination_latitude') : NULL,
            'destination_longitude' => $this->input->post('destination_longitude') ? (float)$this->input->post('destination_longitude') : NULL,
        ];

        $this->Order_model->update($id, $data);
        $this->auth_lib->set_toast('success', 'Order kargo berhasil diperbarui.');
        redirect('admin/orders');
    }

    public function delete($id)
    {
        $this->auth_lib->require_permission('orders.delete');

        $order = $this->Order_model->get_by_id($id);
        if (!$order) {
            $this->auth_lib->set_toast('error', 'Order tidak ditemukan.');
            redirect('admin/orders');
            return;
        }

        if (!in_array($order['status'], ['pending', 'completed', 'canceled'])) {
            $this->auth_lib->set_toast('error', 'Tidak dapat menghapus order yang sedang berjalan rutenya.');
            redirect('admin/orders');
            return;
        }

        $this->Order_model->delete($id);
        $this->auth_lib->set_toast('success', 'Order berhasil dihapus.');
        redirect('admin/orders');
    }

    /**
     * View details, pre-trip checklist, and POD submission
     */
    public function view($id)
    {
        $order = $this->Order_model->get_by_id($id);
        if (!$order) {
            $this->auth_lib->set_toast('error', 'Order tidak ditemukan.');
            redirect('admin/orders');
            return;
        }

        $data = [
            'title'      => 'Detail Order #' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
            'page'       => 'admin/orders/view',
            'order'      => $order,
            'inspection' => $this->Inspection_model->get_by_order_id($id),
            'pod'        => $this->Pod_model->get_by_order_id($id),
            'latest_location' => $this->Order_model->get_latest_location($id),
            'route_breadcrumbs' => $this->Order_model->get_route_breadcrumbs($id),
            'toast'      => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    /**
     * Dispatching (Show Form)
     */
    public function dispatch($id)
    {
        $this->auth_lib->require_permission('dispatch.assign');

        $order = $this->Order_model->get_by_id($id);
        if (!$order || $order['status'] !== 'pending') {
            $this->auth_lib->set_toast('error', 'Halaman tidak dapat diakses. Status order harus Pending.');
            redirect('admin/orders');
            return;
        }

        $data = [
            'title'    => 'Alokasi Armada & Driver',
            'page'     => 'admin/orders/dispatch',
            'order'    => $order,
            'drivers'  => $this->Order_model->get_available_drivers(),
            'vehicles' => $this->Vehicle_model->get_available_vehicles(),
            'toast'    => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    /**
     * Assign Driver, Vehicle, and Uang Jalan
     */
    public function assign($id)
    {
        $this->auth_lib->require_permission('dispatch.assign');

        $order = $this->Order_model->get_by_id($id);
        if (!$order || $order['status'] !== 'pending') {
            $this->auth_lib->set_toast('error', 'Order tidak valid untuk diproses.');
            redirect('admin/orders');
            return;
        }

        $this->form_validation->set_rules([
            ['field' => 'driver_id',  'label' => 'Driver',      'rules' => 'required|integer'],
            ['field' => 'vehicle_id', 'label' => 'Armada Truk',  'rules' => 'required|integer'],
            ['field' => 'uang_jalan', 'label' => 'Uang Jalan',  'rules' => 'required|numeric|greater_than_equal_to[0]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/orders/dispatch/' . $id);
            return;
        }

        $driver_id  = $this->input->post('driver_id', TRUE);
        $vehicle_id = $this->input->post('vehicle_id', TRUE);
        $uang_jalan = $this->input->post('uang_jalan', TRUE);

        // Backend validation: check if driver is actually available (not in active transit/allocation)
        $available_drivers = $this->Order_model->get_available_drivers();
        $driver_ids = array_column($available_drivers, 'id');
        if (!in_array($driver_id, $driver_ids)) {
            $this->auth_lib->set_toast('error', 'Gagal: Driver tidak tersedia atau sedang dalam penugasan aktif.');
            redirect('admin/orders/dispatch/' . $id);
            return;
        }

        // Backend validation: check if vehicle is actually available
        $vehicle = $this->Vehicle_model->get_by_id($vehicle_id);
        if (!$vehicle || $vehicle['status'] !== 'available') {
            $this->auth_lib->set_toast('error', 'Gagal: Armada kendaraan tidak tersedia (sedang aktif atau dalam perawatan).');
            redirect('admin/orders/dispatch/' . $id);
            return;
        }

        if ($this->Order_model->assign_fleet($id, $driver_id, $vehicle_id, $uang_jalan)) {
            $this->auth_lib->set_toast('success', 'Driver dan armada berhasil dialokasikan. Menunggu uji kelayakan jalan.');
            redirect('admin/orders');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal menugaskan driver dan armada.');
            redirect('admin/orders/dispatch/' . $id);
        }
    }

    /**
     * Print Surat Jalan Page
     */
    public function print_sj($id)
    {
        $this->auth_lib->require_permission('dispatch.print-sj');

        $order = $this->Order_model->get_by_id($id);
        if (!$order) {
            show_404();
            return;
        }

        // Allowed to print only if status is READY or further in flow (meaning checklist passed)
        if (in_array($order['status'], ['pending', 'allocated', 'inspect_failed'])) {
            $this->auth_lib->set_toast('error', 'Surat Jalan hanya dapat dicetak setelah armada dinyatakan lolos kelayakan jalan.');
            redirect('admin/orders');
            return;
        }

        $data = [
            'order' => $order
        ];
        $this->load->view('admin/orders/print_sj', $data);
    }

    /**
     * Verify Proof of Delivery (POD)
     */
    public function verify_pod($id)
    {
        $this->auth_lib->require_permission('pod.verify');

        $order = $this->Order_model->get_by_id($id);
        if (!$order || $order['status'] !== 'pod_submitted') {
            $this->auth_lib->set_toast('error', 'Tidak ada berkas POD untuk diverifikasi pada order ini.');
            redirect('admin/orders');
            return;
        }

        $verifier_id = $this->auth_lib->get_user_id();

        if ($this->Pod_model->verify_pod($id, $verifier_id)) {
            $this->auth_lib->set_toast('success', 'POD berhasil disetujui. Pengiriman selesai & armada kembali bersiap.');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal menyetujui berkas POD.');
        }
        redirect('admin/orders/view/' . $id);
    }
}
