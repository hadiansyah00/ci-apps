<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Inspections Controller
 * Handles vehicle checklist records for Checkers.
 */
class Inspections extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('inspections.view');
        $this->load->model(['Inspection_model', 'Order_model', 'Vehicle_model']);
    }

    public function index()
    {
        $filters = [
            'status' => $this->input->get('status', TRUE),
            'search' => $this->input->get('search', TRUE),
        ];

        $total = $this->Inspection_model->count_all($filters);
        $per_page = 5;

        // CI3 Pagination Setup
        $this->load->library('pagination');
        $config = [
            'base_url'             => base_url('admin/inspections') . '?',
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

        $inspections = $this->Inspection_model->get_paginated($per_page, $offset, $filters);

        $data = [
            'title'       => 'Riwayat Cek Fisik',
            'inspections' => $inspections,
            'total'       => $total,
            'filters'     => $filters,
            'pagination'  => $this->pagination->create_links(),
            'toast'       => $this->auth_lib->get_toast(),
        ];

        if ($this->input->is_ajax_request()) {
            $this->load->view('admin/inspections/table', $data);
        } else {
            $data['page'] = 'admin/inspections/index';
            $this->load->view('layouts/app', $data);
        }
    }

    /**
     * Show Checklist Form (for Checker role)
     */
    public function check($order_id)
    {
        $this->auth_lib->require_permission('inspections.create');

        $order = $this->Order_model->get_by_id($order_id);
        if (!$order || !in_array($order['status'], ['allocated', 'inspect_failed'])) {
            $this->auth_lib->set_toast('error', 'Order tidak dalam antrean uji kelayakan jalan.');
            redirect('admin/orders');
            return;
        }

        $data = [
            'title'   => 'Cek Kelayakan Fisik Truk: ' . $order['plate_number'],
            'page'    => 'admin/inspections/form',
            'order'   => $order,
            'toast'   => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    /**
     * Store Checker physical evaluation
     */
    public function store($order_id)
    {
        $this->auth_lib->require_permission('inspections.create');

        $order = $this->Order_model->get_by_id($order_id);
        if (!$order || !in_array($order['status'], ['allocated', 'inspect_failed'])) {
            $this->auth_lib->set_toast('error', 'Order tidak valid untuk diproses.');
            redirect('admin/orders');
            return;
        }

        // Checklist checkboxes
        $tires_ok    = (int)$this->input->post('tires_ok');
        $brakes_ok   = (int)$this->input->post('brakes_ok');
        $lights_ok   = (int)$this->input->post('lights_ok');
        $oil_ok      = (int)$this->input->post('engine_oil_ok');
        $docs_ok     = (int)$this->input->post('documents_ok');
        $notes       = $this->input->post('notes', TRUE);

        // Determine roadworthiness
        $passed = ($tires_ok && $brakes_ok && $lights_ok && $oil_ok && $docs_ok);
        $status = $passed ? 'passed' : 'failed';

        $insert_data = [
            'order_id'      => $order_id,
            'vehicle_id'    => $order['vehicle_id'],
            'checked_by'    => $this->auth_lib->get_user_id(),
            'tires_ok'      => $tires_ok,
            'brakes_ok'     => $brakes_ok,
            'lights_ok'     => $lights_ok,
            'engine_oil_ok' => $oil_ok,
            'documents_ok'  => $docs_ok,
            'status'        => $status,
            'notes'         => $notes
        ];

        $this->db->trans_start();

        // Save inspection record
        $this->Inspection_model->save_inspection($insert_data);

        if ($passed) {
            // Set order to READY
            $this->Order_model->update_status($order_id, 'ready');
            $this->auth_lib->set_toast('success', 'Uji layak jalan BERHASIL. Armada siap diberangkatkan.');
        } else {
            // Set order to INSPECT_FAILED
            $this->Order_model->update_status($order_id, 'inspect_failed');
            // Change vehicle to maintenance
            $this->Vehicle_model->update_status($order['vehicle_id'], 'maintenance');
            $this->auth_lib->set_toast('warning', 'Uji layak jalan GAGAL. Armada dipindahkan ke status Maintenance.');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->auth_lib->set_toast('error', 'Gagal memproses hasil uji kelayakan.');
            redirect('admin/inspections/check/' . $order_id);
        } else {
            redirect('admin/orders');
        }
    }

    /**
     * Show cargo loading verification form (for Checker role)
     */
    public function verify_loading($order_id)
    {
        $this->auth_lib->require_permission('loading.verify');

        $order = $this->Order_model->get_by_id($order_id);
        if (!$order || $order['status'] !== 'loading') {
            $this->auth_lib->set_toast('error', 'Order tidak dalam status sedang memuat kargo.');
            redirect('admin/orders');
            return;
        }

        $data = [
            'title'   => 'Verifikasi Pemuatan Kargo: ' . $order['plate_number'],
            'page'    => 'admin/inspections/verify_loading',
            'order'   => $order,
            'toast'   => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    /**
     * Store Checker loading verification
     */
    public function store_loading($order_id)
    {
        $this->auth_lib->require_permission('loading.verify');

        $order = $this->Order_model->get_by_id($order_id);
        if (!$order || $order['status'] !== 'loading') {
            $this->auth_lib->set_toast('error', 'Order tidak valid untuk diproses.');
            redirect('admin/orders');
            return;
        }

        $this->form_validation->set_rules('seal_number', 'Nomor Segel', 'required|trim|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            $this->auth_lib->set_toast('error', 'Harap isi Nomor Segel kontainer/box.');
            redirect('admin/inspections/verify-loading/' . $order_id);
            return;
        }

        $seal_number  = $this->input->post('seal_number', TRUE);
        $notes        = $this->input->post('notes', TRUE);

        $update_data = [
            'status'              => 'in_transit',
            'seal_number'         => $seal_number,
            'loading_notes'       => $notes,
            'loading_verified_by' => $this->auth_lib->get_user_id(),
            'loading_verified_at' => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s')
        ];

        if ($this->db->update('orders', $update_data, ['id' => $order_id])) {
            $this->auth_lib->set_toast('success', 'Verifikasi pemuatan BERHASIL. Armada resmi berangkat (In-Transit).');
            redirect('admin/orders');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal memproses verifikasi pemuatan.');
            redirect('admin/inspections/verify-loading/' . $order_id);
        }
    }
}
