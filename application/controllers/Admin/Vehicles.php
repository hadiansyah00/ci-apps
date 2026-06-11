<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vehicles Controller
 * Handles CRUD operations for fleet management.
 */
class Vehicles extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('fleets.view');
        $this->load->model('Vehicle_model');
    }

    public function index()
    {
        $data = [
            'title'    => 'Manajemen Armada',
            'page'     => 'admin/vehicles/index',
            'vehicles' => $this->Vehicle_model->get_all(),
            'toast'    => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function create()
    {
        $this->auth_lib->require_permission('fleets.manage');
        $data = [
            'title'    => 'Tambah Armada',
            'page'     => 'admin/vehicles/form',
            'vehicle'  => null,
            'is_edit'  => false,
            'toast'    => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function store()
    {
        $this->auth_lib->require_permission('fleets.manage');

        $this->form_validation->set_rules([
            ['field' => 'plate_number',    'label' => 'No. Plat',         'rules' => 'required|trim|max_length[20]'],
            ['field' => 'type',            'label' => 'Tipe Kendaraan',   'rules' => 'required|trim|max_length[50]'],
            ['field' => 'capacity_weight',  'label' => 'Kapasitas Berat',  'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'capacity_volume',  'label' => 'Kapasitas Volume', 'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'kir_expiry',      'label' => 'Masa Berlaku KIR', 'rules' => 'required|trim'],
            ['field' => 'tax_expiry',      'label' => 'Masa Berlaku Pajak','rules' => 'required|trim'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/vehicles/create');
            return;
        }

        $plate_number = strtoupper(trim($this->input->post('plate_number', TRUE)));

        if (!$this->Vehicle_model->is_plate_number_unique($plate_number)) {
            $this->auth_lib->set_toast('error', 'No. Plat sudah digunakan.');
            redirect('admin/vehicles/create');
            return;
        }

        $data = [
            'plate_number'    => $plate_number,
            'type'            => $this->input->post('type', TRUE),
            'capacity_weight' => $this->input->post('capacity_weight', TRUE),
            'capacity_volume' => $this->input->post('capacity_volume', TRUE),
            'kir_expiry'      => $this->input->post('kir_expiry', TRUE),
            'tax_expiry'      => $this->input->post('tax_expiry', TRUE),
            'status'          => $this->input->post('status', TRUE) ?? 'available',
        ];

        if ($this->Vehicle_model->create($data)) {
            $this->auth_lib->set_toast('success', 'Armada baru berhasil ditambahkan.');
            redirect('admin/vehicles');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal menambahkan armada.');
            redirect('admin/vehicles/create');
        }
    }

    public function edit($id)
    {
        $this->auth_lib->require_permission('fleets.manage');

        $vehicle = $this->Vehicle_model->get_by_id($id);
        if (!$vehicle) {
            $this->auth_lib->set_toast('error', 'Armada tidak ditemukan.');
            redirect('admin/vehicles');
            return;
        }

        $data = [
            'title'    => 'Edit Armada',
            'page'     => 'admin/vehicles/form',
            'vehicle'  => $vehicle,
            'is_edit'  => true,
            'toast'    => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function update($id)
    {
        $this->auth_lib->require_permission('fleets.manage');

        $vehicle = $this->Vehicle_model->get_by_id($id);
        if (!$vehicle) {
            $this->auth_lib->set_toast('error', 'Armada tidak ditemukan.');
            redirect('admin/vehicles');
            return;
        }

        $this->form_validation->set_rules([
            ['field' => 'plate_number',    'label' => 'No. Plat',         'rules' => 'required|trim|max_length[20]'],
            ['field' => 'type',            'label' => 'Tipe Kendaraan',   'rules' => 'required|trim|max_length[50]'],
            ['field' => 'capacity_weight',  'label' => 'Kapasitas Berat',  'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'capacity_volume',  'label' => 'Kapasitas Volume', 'rules' => 'required|numeric|greater_than_equal_to[0]'],
            ['field' => 'kir_expiry',      'label' => 'Masa Berlaku KIR', 'rules' => 'required|trim'],
            ['field' => 'tax_expiry',      'label' => 'Masa Berlaku Pajak','rules' => 'required|trim'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/vehicles/edit/' . $id);
            return;
        }

        $plate_number = strtoupper(trim($this->input->post('plate_number', TRUE)));

        if (!$this->Vehicle_model->is_plate_number_unique($plate_number, $id)) {
            $this->auth_lib->set_toast('error', 'No. Plat sudah digunakan armada lain.');
            redirect('admin/vehicles/edit/' . $id);
            return;
        }

        $data = [
            'plate_number'    => $plate_number,
            'type'            => $this->input->post('type', TRUE),
            'capacity_weight' => $this->input->post('capacity_weight', TRUE),
            'capacity_volume' => $this->input->post('capacity_volume', TRUE),
            'kir_expiry'      => $this->input->post('kir_expiry', TRUE),
            'tax_expiry'      => $this->input->post('tax_expiry', TRUE),
            'status'          => $this->input->post('status', TRUE),
        ];

        $this->Vehicle_model->update($id, $data);
        $this->auth_lib->set_toast('success', 'Data armada berhasil diperbarui.');
        redirect('admin/vehicles');
    }

    public function delete($id)
    {
        $this->auth_lib->require_permission('fleets.manage');

        $result = $this->Vehicle_model->delete($id);
        if ($result === false) {
            $this->auth_lib->set_toast('error', 'Armada tidak dapat dihapus karena masih terhubung dengan log pengiriman.');
        } else {
            $this->auth_lib->set_toast('success', 'Armada berhasil dihapus.');
        }
        redirect('admin/vehicles');
    }
}
