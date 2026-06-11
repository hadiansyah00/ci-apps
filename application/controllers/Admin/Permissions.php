<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Permissions Controller
 * CRUD for Permission Management.
 */
class Permissions extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('permissions.view');
        $this->load->model('Permission_model');
    }

    public function index()
    {
        $data = [
            'title'       => 'Manajemen Permission',
            'page'        => 'admin/permissions/index',
            'permissions' => $this->Permission_model->get_grouped(),
            'total'       => $this->Permission_model->count_all(),
            'toast'       => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function create()
    {
        $this->auth_lib->require_permission('permissions.create');
        $data = [
            'title'      => 'Tambah Permission',
            'page'       => 'admin/permissions/form',
            'permission' => null,
            'is_edit'    => false,
            'modules'    => $this->Permission_model->get_modules(),
            'toast'      => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function store()
    {
        $this->auth_lib->require_permission('permissions.create');

        $this->form_validation->set_rules([
            ['field' => 'name',   'label' => 'Nama',   'rules' => 'required|trim|min_length[3]|max_length[150]'],
            ['field' => 'slug',   'label' => 'Slug',   'rules' => 'required|trim|min_length[3]|max_length[150]'],
            ['field' => 'module', 'label' => 'Module', 'rules' => 'required|trim|max_length[100]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', strip_tags(validation_errors()));
            redirect('admin/permissions/create');
            return;
        }

        $slug = strtolower(trim($this->input->post('slug', TRUE)));

        if (!$this->Permission_model->is_slug_unique($slug)) {
            $this->auth_lib->set_toast('error', 'Slug permission sudah digunakan.');
            redirect('admin/permissions/create');
            return;
        }

        $data = [
            'name'   => $this->input->post('name', TRUE),
            'slug'   => $slug,
            'module' => strtolower(trim($this->input->post('module', TRUE))),
        ];

        if ($this->Permission_model->create($data)) {
            $this->auth_lib->set_toast('success', 'Permission berhasil ditambahkan.');
            redirect('admin/permissions');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal menambahkan permission.');
            redirect('admin/permissions/create');
        }
    }

    public function edit($id)
    {
        $this->auth_lib->require_permission('permissions.edit');

        $permission = $this->Permission_model->get_by_id($id);
        if (!$permission) {
            $this->auth_lib->set_toast('error', 'Permission tidak ditemukan.');
            redirect('admin/permissions');
            return;
        }

        $data = [
            'title'      => 'Edit Permission',
            'page'       => 'admin/permissions/form',
            'permission' => $permission,
            'is_edit'    => true,
            'modules'    => $this->Permission_model->get_modules(),
            'toast'      => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function update($id)
    {
        $this->auth_lib->require_permission('permissions.edit');

        $this->form_validation->set_rules([
            ['field' => 'name',   'label' => 'Nama',   'rules' => 'required|trim|min_length[3]|max_length[150]'],
            ['field' => 'slug',   'label' => 'Slug',   'rules' => 'required|trim|min_length[3]|max_length[150]'],
            ['field' => 'module', 'label' => 'Module', 'rules' => 'required|trim|max_length[100]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', strip_tags(validation_errors()));
            redirect('admin/permissions/edit/' . $id);
            return;
        }

        $slug = strtolower(trim($this->input->post('slug', TRUE)));

        if (!$this->Permission_model->is_slug_unique($slug, $id)) {
            $this->auth_lib->set_toast('error', 'Slug sudah digunakan.');
            redirect('admin/permissions/edit/' . $id);
            return;
        }

        $data = [
            'name'   => $this->input->post('name', TRUE),
            'slug'   => $slug,
            'module' => strtolower(trim($this->input->post('module', TRUE))),
        ];

        $this->Permission_model->update($id, $data);
        $this->auth_lib->set_toast('success', 'Permission berhasil diperbarui.');
        redirect('admin/permissions');
    }

    public function delete($id)
    {
        $this->auth_lib->require_permission('permissions.delete');
        $this->Permission_model->delete($id);
        $this->auth_lib->set_toast('success', 'Permission berhasil dihapus.');
        redirect('admin/permissions');
    }
}
