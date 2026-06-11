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
        $user = $this->auth_lib->get_user();

        // If user is Checker, redirect to custom Checker Dashboard
        if ($user['role_slug'] === 'checker') {
            $this->load->model('Order_model');
            
            $pending_inspections = $this->Order_model->get_pending_inspections();
            $pending_loadings    = $this->Order_model->get_pending_loadings();
            $recent_inspections  = $this->Order_model->get_recent_inspections_by_checker($user['id'], 5);

            $data = [
                'title'               => 'Portal Checker Operasional',
                'page'                => 'admin/dashboard_checker',
                'pending_inspections' => $pending_inspections,
                'pending_loadings'    => $pending_loadings,
                'recent_inspections'  => $recent_inspections,
                'toast'               => $this->auth_lib->get_toast(),
            ];

            $this->load->view('layouts/app', $data);
            return;
        }

        // Default Admin Dashboard
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
