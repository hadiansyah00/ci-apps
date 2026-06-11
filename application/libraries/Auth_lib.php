<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Library
 * Handles authentication state, role checking, and permission verification.
 * 
 * @package  CI3 Auth & RBAC
 * @author   System
 * @version  1.0.0
 */
class Auth_lib {

    protected $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        log_message('debug', 'Auth Library Loaded');
    }

    // -----------------------------------------------------------------------
    // Authentication Methods
    // -----------------------------------------------------------------------

    /**
     * Check if user is logged in
     */
    public function is_logged_in()
    {
        return (bool) $this->CI->session->userdata('logged_in');
    }

    /**
     * Get current logged-in user data
     */
    public function get_user()
    {
        if (!$this->is_logged_in()) {
            return null;
        }
        return $this->CI->session->userdata('auth_user');
    }

    /**
     * Get current user ID
     */
    public function get_user_id()
    {
        $user = $this->get_user();
        return $user ? $user['id'] : null;
    }

    /**
     * Get current user name
     */
    public function get_user_name()
    {
        $user = $this->get_user();
        return $user ? $user['name'] : null;
    }

    /**
     * Get current user email
     */
    public function get_user_email()
    {
        $user = $this->get_user();
        return $user ? $user['email'] : null;
    }

    /**
     * Get current user role slug
     */
    public function get_role()
    {
        $user = $this->get_user();
        return $user ? $user['role_slug'] : null;
    }

    /**
     * Get current user role name
     */
    public function get_role_name()
    {
        $user = $this->get_user();
        return $user ? $user['role_name'] : null;
    }

    // -----------------------------------------------------------------------
    // Role & Permission Methods
    // -----------------------------------------------------------------------

    /**
     * Check if user has a specific role
     * @param string|array $role_slug
     */
    public function has_role($role_slug)
    {
        $user = $this->get_user();
        if (!$user) return false;

        if (is_array($role_slug)) {
            return in_array($user['role_slug'], $role_slug);
        }
        return $user['role_slug'] === $role_slug;
    }

    /**
     * Check if user is Super Admin
     */
    public function is_super_admin()
    {
        return $this->has_role('super-admin');
    }

    /**
     * Check if user is Admin or above
     */
    public function is_admin()
    {
        return $this->has_role(['super-admin', 'admin']);
    }

    /**
     * Check if user has a specific permission
     * Super Admin always has all permissions.
     * @param string $permission_slug
     */
    public function can($permission_slug)
    {
        if (!$this->is_logged_in()) return false;
        
        // Super admin has all permissions
        if ($this->is_super_admin()) return true;

        $permissions = $this->get_permissions();
        return in_array($permission_slug, $permissions);
    }

    /**
     * Get all permission slugs for current user (cached in session)
     */
    public function get_permissions()
    {
        if (!$this->is_logged_in()) return [];

        // Check session cache first
        $cached = $this->CI->session->userdata('user_permissions');
        if (is_array($cached)) {
            return $cached;
        }

        // Load from DB
        $user = $this->get_user();
        if (!$user) return [];

        $this->CI->load->model('Permission_model');
        $permissions = $this->CI->Permission_model->get_role_permission_slugs($user['role_id']);
        
        // Cache in session
        $this->CI->session->set_userdata('user_permissions', $permissions);
        
        return $permissions;
    }

    /**
     * Get sidebar menu items filtered by permission
     */
    public function get_menu_items()
    {
        $items = [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'url' => 'admin/dashboard',
                'permission' => 'dashboard.view',
                'submenu' => []
            ],
            [
                'title' => 'User Management',
                'icon' => 'fas fa-users',
                'url' => '#',
                'permission' => 'users.view',
                'submenu' => [
                    ['title' => 'All Users', 'url' => 'admin/users', 'permission' => 'users.view'],
                    ['title' => 'Add User', 'url' => 'admin/users/create', 'permission' => 'users.create'],
                ]
            ],
            [
                'title' => 'Role Management',
                'icon' => 'fas fa-user-shield',
                'url' => '#',
                'permission' => 'roles.view',
                'submenu' => [
                    ['title' => 'All Roles', 'url' => 'admin/roles', 'permission' => 'roles.view'],
                    ['title' => 'Add Role', 'url' => 'admin/roles/create', 'permission' => 'roles.create'],
                ]
            ],
            [
                'title' => 'Permissions',
                'icon' => 'fas fa-key',
                'url' => '#',
                'permission' => 'permissions.view',
                'submenu' => [
                    ['title' => 'All Permissions', 'url' => 'admin/permissions', 'permission' => 'permissions.view'],
                    ['title' => 'Add Permission', 'url' => 'admin/permissions/create', 'permission' => 'permissions.create'],
                ]
            ],
        ];

        // Filter by permission
        $filtered = [];
        foreach ($items as $item) {
            if ($this->can($item['permission'])) {
                // Filter submenu
                if (!empty($item['submenu'])) {
                    $item['submenu'] = array_values(array_filter($item['submenu'], function($sub) {
                        return $this->can($sub['permission']);
                    }));
                }
                $filtered[] = $item;
            }
        }
        return $filtered;
    }

    // -----------------------------------------------------------------------
    // Guard Methods (Redirect if not authorized)
    // -----------------------------------------------------------------------

    /**
     * Require user to be logged in, otherwise redirect to login
     */
    public function require_login()
    {
        if (!$this->is_logged_in()) {
            $this->set_toast('warning', 'Silahkan login terlebih dahulu.');
            redirect('login');
            exit;
        }
    }

    /**
     * Require specific permission, otherwise redirect to 403
     * @param string $permission_slug
     */
    public function require_permission($permission_slug)
    {
        $this->require_login();
        if (!$this->can($permission_slug)) {
            $this->set_toast('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
            redirect('errors/forbidden');
            exit;
        }
    }

    /**
     * Require user NOT to be logged in (for login page)
     */
    public function require_guest()
    {
        if ($this->is_logged_in()) {
            redirect('admin/dashboard');
            exit;
        }
    }

    // -----------------------------------------------------------------------
    // Flash/Toast Notification Methods
    // -----------------------------------------------------------------------

    /**
     * Set a toast notification via CI flashdata
     * @param string $type  success|error|warning|info
     * @param string $message
     */
    public function set_toast($type, $message)
    {
        $this->CI->session->set_flashdata('toast_type', $type);
        $this->CI->session->set_flashdata('toast_message', $message);
    }

    /**
     * Get toast data for current request (used in views)
     */
    public function get_toast()
    {
        return [
            'type'    => $this->CI->session->flashdata('toast_type'),
            'message' => $this->CI->session->flashdata('toast_message')
        ];
    }

    // -----------------------------------------------------------------------
    // Session Management
    // -----------------------------------------------------------------------

    /**
     * Create auth session after successful login
     * @param array $user_data  User + role data from DB
     */
    public function create_session($user_data)
    {
        $session_data = [
            'logged_in'  => TRUE,
            'auth_user'  => [
                'id'        => $user_data['id'],
                'name'      => $user_data['name'],
                'email'     => $user_data['email'],
                'username'  => $user_data['username'],
                'role_id'   => $user_data['role_id'],
                'role_name' => $user_data['role_name'],
                'role_slug' => $user_data['role_slug'],
                'is_active' => $user_data['is_active'],
                'avatar'    => $user_data['avatar'],
            ]
        ];
        $this->CI->session->set_userdata($session_data);
    }

    /**
     * Destroy auth session (logout)
     */
    public function destroy_session()
    {
        $this->CI->session->unset_userdata('logged_in');
        $this->CI->session->unset_userdata('auth_user');
        $this->CI->session->unset_userdata('user_permissions');
    }
}
