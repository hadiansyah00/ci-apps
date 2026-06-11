<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Dashboard Controller
 */
class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Auth_lib');
        $this->auth_lib->require_login();
        $this->auth_lib->require_permission('dashboard.view');
        $this->load->model(['User_model', 'Role_model', 'Permission_model']);
    }

    public function index()
    {
        $data = [
            'title'             => 'Dashboard',
            'page'              => 'admin/dashboard',
            'total_users'       => $this->db->count_all('users'),
            'active_users'      => $this->User_model->count_active(),
            'inactive_users'    => $this->User_model->count_inactive(),
            'total_roles'       => $this->db->count_all('roles'),
            'total_permissions' => $this->db->count_all('permissions'),
            'recent_logins'     => $this->User_model->get_recently_logged_in(8),
            'roles_with_count'  => $this->Role_model->get_with_user_count(),
            'toast'             => $this->auth_lib->get_toast(),
        ];

        $this->load->view('layouts/app', $data);
    }
}
