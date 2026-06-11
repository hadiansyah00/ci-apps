<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Users Controller
 * Full CRUD for User Management.
 */
class Users extends CI_Controller {

    protected $per_page = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('users.view');
        $this->load->model(['User_model', 'Role_model']);
    }

    // -----------------------------------------------------------------------
    // Index / List
    // -----------------------------------------------------------------------

    public function index()
    {
        $filters = [
            'search'    => $this->input->get('search', TRUE),
            'role_id'   => $this->input->get('role_id', TRUE),
            'is_active' => $this->input->get('is_active', TRUE),
        ];

        $total = $this->User_model->count_all($filters);
        
        // CI3 Pagination
        $this->load->library('pagination');
        $config = [
            'base_url'         => base_url('admin/users') . '?',
            'total_rows'       => $total,
            'per_page'         => $this->per_page,
            'page_query_string' => TRUE,
            'query_string_segment' => 'page',
            'use_page_numbers'  => TRUE,
            'first_link'        => '&laquo;',
            'last_link'         => '&raquo;',
            'next_link'         => '&rsaquo;',
            'prev_link'         => '&lsaquo;',
            'full_tag_open'     => '<div class="pagination-custom">',
            'full_tag_close'    => '</div>',
            'num_tag_open'      => '',
            'num_tag_close'     => '',
            'cur_tag_open'      => '<span class="current-page">',
            'cur_tag_close'     => '</span>',
            'next_tag_open'     => '',
            'next_tag_close'    => '',
            'prev_tag_open'     => '',
            'prev_tag_close'    => '',
            'first_tag_open'    => '',
            'first_tag_close'   => '',
            'last_tag_open'     => '',
            'last_tag_close'    => '',
        ];
        $this->pagination->initialize($config);

        $page   = max(1, (int)$this->input->get('page'));
        $offset = ($page - 1) * $this->per_page;

        $data = [
            'title'      => 'Manajemen Pengguna',
            'page'       => 'admin/users/index',
            'users'      => $this->User_model->get_paginated($this->per_page, $offset, $filters),
            'total'      => $total,
            'roles'      => $this->Role_model->get_all(),
            'filters'    => $filters,
            'pagination' => $this->pagination->create_links(),
            'toast'      => $this->auth_lib->get_toast(),
        ];

        $this->load->view('layouts/app', $data);
    }

    // -----------------------------------------------------------------------
    // Create
    // -----------------------------------------------------------------------

    public function create()
    {
        $this->auth_lib->require_permission('users.create');

        $data = [
            'title'   => 'Tambah Pengguna',
            'page'    => 'admin/users/form',
            'roles'   => $this->Role_model->get_all(),
            'user'    => null,
            'is_edit' => false,
            'toast'   => $this->auth_lib->get_toast(),
        ];

        $this->load->view('layouts/app', $data);
    }

    public function store()
    {
        $this->auth_lib->require_permission('users.create');

        $this->form_validation->set_rules([
            ['field' => 'name',     'label' => 'Nama',     'rules' => 'required|trim|min_length[3]|max_length[150]'],
            ['field' => 'email',    'label' => 'Email',    'rules' => 'required|trim|valid_email|max_length[191]'],
            ['field' => 'username', 'label' => 'Username', 'rules' => 'required|trim|alpha_dash|min_length[3]|max_length[100]'],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]'],
            ['field' => 'role_id',  'label' => 'Role',     'rules' => 'required|integer|greater_than[0]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/users/create');
            return;
        }

        $email    = $this->input->post('email', TRUE);
        $username = $this->input->post('username', TRUE);

        if (!$this->User_model->is_email_unique($email)) {
            $this->auth_lib->set_toast('error', 'Email sudah digunakan.');
            redirect('admin/users/create');
            return;
        }

        if (!$this->User_model->is_username_unique($username)) {
            $this->auth_lib->set_toast('error', 'Username sudah digunakan.');
            redirect('admin/users/create');
            return;
        }

        $data = [
            'name'      => $this->input->post('name', TRUE),
            'email'     => $email,
            'username'  => $username,
            'password'  => $this->input->post('password'), // unhashed - model will hash
            'role_id'   => $this->input->post('role_id', TRUE),
            'is_active' => (int)$this->input->post('is_active', TRUE),
        ];

        $id = $this->User_model->create($data);
        if ($id) {
            $this->auth_lib->set_toast('success', 'Pengguna berhasil ditambahkan.');
            redirect('admin/users');
        } else {
            $this->auth_lib->set_toast('error', 'Gagal menambahkan pengguna.');
            redirect('admin/users/create');
        }
    }

    // -----------------------------------------------------------------------
    // Edit / Update
    // -----------------------------------------------------------------------

    public function edit($id)
    {
        $this->auth_lib->require_permission('users.edit');

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->auth_lib->set_toast('error', 'Pengguna tidak ditemukan.');
            redirect('admin/users');
            return;
        }

        $data = [
            'title'   => 'Edit Pengguna',
            'page'    => 'admin/users/form',
            'roles'   => $this->Role_model->get_all(),
            'user'    => $user,
            'is_edit' => true,
            'toast'   => $this->auth_lib->get_toast(),
        ];

        $this->load->view('layouts/app', $data);
    }

    public function update($id)
    {
        $this->auth_lib->require_permission('users.edit');

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->auth_lib->set_toast('error', 'Pengguna tidak ditemukan.');
            redirect('admin/users');
            return;
        }

        $this->form_validation->set_rules([
            ['field' => 'name',    'label' => 'Nama',  'rules' => 'required|trim|min_length[3]|max_length[150]'],
            ['field' => 'email',   'label' => 'Email', 'rules' => 'required|trim|valid_email|max_length[191]'],
            ['field' => 'username','label' => 'Username', 'rules' => 'required|trim|alpha_dash|min_length[3]|max_length[100]'],
            ['field' => 'role_id', 'label' => 'Role',  'rules' => 'required|integer|greater_than[0]'],
        ]);

        if (!$this->form_validation->run()) {
            $this->auth_lib->set_toast('error', 'Validasi gagal: ' . strip_tags(validation_errors()));
            redirect('admin/users/edit/' . $id);
            return;
        }

        $email    = $this->input->post('email', TRUE);
        $username = $this->input->post('username', TRUE);

        if (!$this->User_model->is_email_unique($email, $id)) {
            $this->auth_lib->set_toast('error', 'Email sudah digunakan oleh pengguna lain.');
            redirect('admin/users/edit/' . $id);
            return;
        }

        if (!$this->User_model->is_username_unique($username, $id)) {
            $this->auth_lib->set_toast('error', 'Username sudah digunakan oleh pengguna lain.');
            redirect('admin/users/edit/' . $id);
            return;
        }

        $data = [
            'name'      => $this->input->post('name', TRUE),
            'email'     => $email,
            'username'  => $username,
            'password'  => $this->input->post('password'), // empty = keep old
            'role_id'   => $this->input->post('role_id', TRUE),
            'is_active' => (int)$this->input->post('is_active', TRUE),
        ];

        $this->User_model->update($id, $data);
        $this->auth_lib->set_toast('success', 'Data pengguna berhasil diperbarui.');
        redirect('admin/users');
    }

    // -----------------------------------------------------------------------
    // Delete
    // -----------------------------------------------------------------------

    public function delete($id)
    {
        $this->auth_lib->require_permission('users.delete');

        // Prevent deleting yourself
        if ($id == $this->auth_lib->get_user_id()) {
            $this->auth_lib->set_toast('error', 'Anda tidak dapat menghapus akun sendiri.');
            redirect('admin/users');
            return;
        }

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->auth_lib->set_toast('error', 'Pengguna tidak ditemukan.');
            redirect('admin/users');
            return;
        }

        $this->User_model->delete($id);
        $this->auth_lib->set_toast('success', 'Pengguna berhasil dihapus.');
        redirect('admin/users');
    }

    // -----------------------------------------------------------------------
    // Toggle Status
    // -----------------------------------------------------------------------

    public function toggle($id)
    {
        $this->auth_lib->require_permission('users.toggle');

        if ($id == $this->auth_lib->get_user_id()) {
            $this->auth_lib->set_toast('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            redirect('admin/users');
            return;
        }

        $new_status = $this->User_model->toggle_status($id);
        $msg = $new_status ? 'Pengguna berhasil diaktifkan.' : 'Pengguna berhasil dinonaktifkan.';
        $this->auth_lib->set_toast('success', $msg);
        redirect('admin/users');
    }

    // -----------------------------------------------------------------------
    // Reset Password
    // -----------------------------------------------------------------------

    public function reset_password($id)
    {
        $this->auth_lib->require_permission('users.reset-password');

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('admin/users');
            return;
        }

        $new_password = $this->input->post('new_password');
        if (strlen($new_password) < 6) {
            $this->auth_lib->set_toast('error', 'Password baru minimal 6 karakter.');
            redirect('admin/users');
            return;
        }

        $this->User_model->reset_password($id, $new_password);
        $this->auth_lib->set_toast('success', 'Password pengguna berhasil direset.');
        redirect('admin/users');
    }
}
