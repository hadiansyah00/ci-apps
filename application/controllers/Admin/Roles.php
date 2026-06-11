<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Roles Controller
 * CRUD for Role Management + Permission assignment.
 */
class Roles extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('roles.view');
        $this->load->model(['Role_model', 'Permission_model']);
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Role',
            'page'  => 'admin/roles/index',
            'roles' => $this->Role_model->get_with_user_count(),
            'toast' => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function create()
    {
        $this->auth_lib->require_permission('roles.create');
        $data = [
            'title'   => 'Tambah Role',
            'page'    => 'admin/roles/form',
            'role'    => null,
            'is_edit' => false,
            'toast'   => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function store()
    {
        $this->auth_lib->require_permission('roles.create');

        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Nama', 'rules' => 'required|trim|min_length[2]|max_length[100]'],
            ['field' => 'slug', 'label' => 'Slug', 'rules' => 'required|trim|min_length[2]|max_length[100]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', strip_tags(validation_errors()));
            redirect('admin/roles/create');
            return;
        }

        $slug = url_title($this->input->post('slug', TRUE), '-', TRUE);

        if (!$this->Role_model->is_slug_unique($slug)) {
            $this->auth_lib->set_toast('error', 'Slug role sudah digunakan.');
            redirect('admin/roles/create');
            return;
        }

        $data = [
            'name'        => $this->input->post('name', TRUE),
            'slug'        => $slug,
            'description' => $this->input->post('description', TRUE),
        ];

        if ($this->Role_model->create($data)) {
            $this->auth_lib->set_toast('success', 'Role berhasil ditambahkan.');
            redirect('admin/roles');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal menambahkan role.');
            redirect('admin/roles/create');
        }
    }

    public function edit($id)
    {
        $this->auth_lib->require_permission('roles.edit');

        $role = $this->Role_model->get_by_id($id);
        if (!$role) {
            $this->auth_lib->set_toast('error', 'Role tidak ditemukan.');
            redirect('admin/roles');
            return;
        }

        $data = [
            'title'   => 'Edit Role',
            'page'    => 'admin/roles/form',
            'role'    => $role,
            'is_edit' => true,
            'toast'   => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    public function update($id)
    {
        $this->auth_lib->require_permission('roles.edit');

        $role = $this->Role_model->get_by_id($id);
        if (!$role) {
            $this->auth_lib->set_toast('error', 'Role tidak ditemukan.');
            redirect('admin/roles');
            return;
        }

        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Nama', 'rules' => 'required|trim|min_length[2]|max_length[100]'],
            ['field' => 'slug', 'label' => 'Slug', 'rules' => 'required|trim|min_length[2]|max_length[100]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', strip_tags(validation_errors()));
            redirect('admin/roles/edit/' . $id);
            return;
        }

        $slug = url_title($this->input->post('slug', TRUE), '-', TRUE);

        if (!$this->Role_model->is_slug_unique($slug, $id)) {
            $this->auth_lib->set_toast('error', 'Slug sudah digunakan oleh role lain.');
            redirect('admin/roles/edit/' . $id);
            return;
        }

        $data = [
            'name'        => $this->input->post('name', TRUE),
            'slug'        => $slug,
            'description' => $this->input->post('description', TRUE),
        ];

        $this->Role_model->update($id, $data);
        $this->auth_lib->set_toast('success', 'Role berhasil diperbarui.');
        redirect('admin/roles');
    }

    public function delete($id)
    {
        $this->auth_lib->require_permission('roles.delete');

        $result = $this->Role_model->delete($id);
        if ($result === false) {
            $this->auth_lib->set_toast('error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna.');
        } else {
            $this->auth_lib->set_toast('success', 'Role berhasil dihapus.');
        }
        redirect('admin/roles');
    }

    /**
     * Show permission assignment page
     */
    public function permissions($id)
    {
        $this->auth_lib->require_permission('roles.assign-permissions');

        $role = $this->Role_model->get_by_id($id);
        if (!$role) {
            $this->auth_lib->set_toast('error', 'Role tidak ditemukan.');
            redirect('admin/roles');
            return;
        }

        $permissions       = $this->Role_model->get_permissions($id);
        $assigned_ids      = $this->Role_model->get_permission_ids($id);
        $perms_by_module   = [];
        foreach ($permissions as $p) {
            $perms_by_module[$p['module']][] = $p;
        }

        $data = [
            'title'           => 'Atur Permission: ' . $role['name'],
            'page'            => 'admin/roles/permissions',
            'role'            => $role,
            'perms_by_module' => $perms_by_module,
            'assigned_ids'    => $assigned_ids,
            'toast'           => $this->auth_lib->get_toast(),
        ];
        $this->load->view('layouts/app', $data);
    }

    /**
     * Save permission assignment
     */
    public function assign_permissions($id)
    {
        $this->auth_lib->require_permission('roles.assign-permissions');

        $role = $this->Role_model->get_by_id($id);
        if (!$role) {
            $this->auth_lib->set_toast('error', 'Role tidak ditemukan.');
            redirect('admin/roles');
            return;
        }

        $permission_ids = $this->input->post('permissions') ?? [];
        $this->Role_model->sync_permissions($id, $permission_ids);

        // Clear cached permissions from session if current user's role changed
        $current_user = $this->auth_lib->get_user();
        if ($current_user && $current_user['role_id'] == $id) {
            $this->session->unset_userdata('user_permissions');
        }

        $this->auth_lib->set_toast('success', 'Permission role berhasil diperbarui.');
        redirect('admin/roles');
    }
}
