<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 * Handles login, logout, and authentication flows.
 */
class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('Auth_lib');
    }

    // -----------------------------------------------------------------------
    // Default redirect
    // -----------------------------------------------------------------------

    public function index()
    {
        if ($this->auth_lib->is_logged_in()) {
            $user = $this->auth_lib->get_user();
            $redirect_url = ($user['role_slug'] === 'driver') ? 'driver/tasks' : 'admin/dashboard';
            redirect($redirect_url);
        } else {
            redirect('login');
        }
    }

    // -----------------------------------------------------------------------
    // Login
    // -----------------------------------------------------------------------

    /**
     * Show login page
     */
    public function login()
    {
        // Already logged in? Go to dashboard
        $this->auth_lib->require_guest();

        // Check remember me cookie
        $remember_token = $this->input->cookie('remember_me');
        if ($remember_token) {
            $user = $this->Auth_model->get_by_remember_token($remember_token);
            if ($user && $user['is_active']) {
                $this->auth_lib->create_session($user);
                $this->Auth_model->update_last_login($user['id']);
                $redirect_url = ($user['role_slug'] === 'driver') ? 'driver/tasks' : 'admin/dashboard';
                redirect($redirect_url);
                return;
            }
        }

        $data = [
            'title'    => 'Login — CI3 Auth & RBAC',
            'toast'    => $this->auth_lib->get_toast(),
        ];
        $this->load->view('auth/login', $data);
    }

    /**
     * Process login form submission
     */
    public function do_login()
    {
        if (!$this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('login');
            return;
        }

        // CSRF check is handled by CI automatically if enabled
        $identity = trim($this->input->post('identity', TRUE));
        $password  = $this->input->post('password'); // Don't XSS filter password!
        $remember  = (bool) $this->input->post('remember_me');

        // Basic validation
        if (empty($identity) || empty($password)) {
            $this->_json_or_redirect(['success' => false, 'message' => 'Email/username dan password wajib diisi.']);
            return;
        }

        // Find user
        $user = $this->Auth_model->get_user_by_identity($identity);

        // User not found
        if (!$user) {
            $this->Auth_model->log_login_attempt(null, $identity, 'failed');
            $this->_json_or_redirect(['success' => false, 'message' => 'Email/username atau password salah.']);
            return;
        }

        // Check if account is locked
        if ($this->Auth_model->is_account_locked($user)) {
            $remaining = $this->Auth_model->get_lockout_remaining($user);
            $this->Auth_model->log_login_attempt($user['id'], $identity, 'locked');
            $this->_json_or_redirect([
                'success' => false,
                'message' => "Akun dikunci karena terlalu banyak percobaan gagal. Coba lagi dalam {$remaining} menit."
            ]);
            return;
        }

        // Check if user is active
        if (!$user['is_active']) {
            $this->_json_or_redirect(['success' => false, 'message' => 'Akun Anda tidak aktif. Hubungi administrator.']);
            return;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $attempts = $this->Auth_model->increment_login_attempts($identity);
            $remaining_attempts = Auth_model::MAX_LOGIN_ATTEMPTS - $attempts;
            $this->Auth_model->log_login_attempt($user['id'], $identity, 'failed');

            $msg = 'Email/username atau password salah.';
            if ($remaining_attempts > 0 && $remaining_attempts <= 2) {
                $msg .= " Sisa percobaan: {$remaining_attempts}x.";
            } elseif ($remaining_attempts <= 0) {
                $msg = 'Akun dikunci karena terlalu banyak percobaan gagal. Coba lagi dalam ' . Auth_model::LOCKOUT_MINUTES . ' menit.';
            }

            $this->_json_or_redirect(['success' => false, 'message' => $msg]);
            return;
        }

        // SUCCESS: Create session
        $this->session->sess_regenerate(TRUE); // Regenerate session ID for security
        $this->auth_lib->create_session($user);
        $this->Auth_model->update_last_login($user['id']);
        $this->Auth_model->log_login_attempt($user['id'], $identity, 'success');

        // Remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->Auth_model->set_remember_token($user['id'], $token);
            $cookie_data = [
                'name'     => 'remember_me',
                'value'    => $token,
                'expire'   => 60 * 60 * 24 * 30, // 30 days
                'secure'   => FALSE,
                'httponly' => TRUE,
            ];
            $this->input->set_cookie($cookie_data);
        }

        $redirect_url = ($user['role_slug'] === 'driver') ? base_url('driver/tasks') : base_url('admin/dashboard');

        $this->_json_or_redirect([
            'success'  => true,
            'message'  => 'Login berhasil! Selamat datang, ' . $user['name'] . '.',
            'redirect' => $redirect_url
        ]);
    }

    // -----------------------------------------------------------------------
    // Logout
    // -----------------------------------------------------------------------

    public function logout()
    {
        $user_id = $this->auth_lib->get_user_id();

        // Clear remember me
        if ($user_id) {
            $this->Auth_model->clear_remember_token($user_id);
        }
        delete_cookie('remember_me');

        // Destroy session
        $this->auth_lib->destroy_session();
        $this->auth_lib->set_toast('success', 'Anda berhasil logout.');

        redirect('login');
    }

    // -----------------------------------------------------------------------
    // Private Helpers
    // -----------------------------------------------------------------------

    /**
     * Return JSON response for AJAX or redirect with flash for normal POST
     */
    private function _json_or_redirect($data)
    {
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        } else {
            if (!$data['success']) {
                $this->auth_lib->set_toast('error', $data['message']);
            } else {
                $this->auth_lib->set_toast('success', $data['message']);
            }
            redirect(isset($data['redirect']) ? $data['redirect'] : 'login');
        }
    }
}
