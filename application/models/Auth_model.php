<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Model
 * Handles all database operations for authentication.
 */
class Auth_model extends CI_Model {

    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_MINUTES    = 15;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find user by email or username for login
     * @param string $identity - email or username
     * @return object|null
     */
    public function get_user_by_identity($identity)
    {
        return $this->db
            ->select('u.*, r.name as role_name, r.slug as role_slug')
            ->from('users u')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.email', $identity)
            ->or_where('u.username', $identity)
            ->get()
            ->row_array();
    }

    /**
     * Find user by email
     */
    public function get_by_email($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }

    /**
     * Find user by username
     */
    public function get_by_username($username)
    {
        return $this->db->get_where('users', ['username' => $username])->row_array();
    }

    /**
     * Find user by ID
     */
    public function get_by_id($id)
    {
        return $this->db
            ->select('u.*, r.name as role_name, r.slug as role_slug')
            ->from('users u')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.id', $id)
            ->get()
            ->row_array();
    }

    /**
     * Update last_login timestamp
     * @param int $user_id
     */
    public function update_last_login($user_id)
    {
        $this->db->update('users', [
            'last_login'     => date('Y-m-d H:i:s'),
            'login_attempts' => 0,
            'locked_until'   => NULL
        ], ['id' => $user_id]);
    }

    /**
     * Increment failed login attempts
     * @param string $identity
     * @return int  number of attempts after increment
     */
    public function increment_login_attempts($identity)
    {
        // Get current attempts
        $user = $this->db->select('id, login_attempts')
            ->where('email', $identity)
            ->or_where('username', $identity)
            ->get('users')
            ->row_array();

        if (!$user) return 0;

        $attempts = (int)$user['login_attempts'] + 1;
        $data = ['login_attempts' => $attempts];

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $data['locked_until'] = date('Y-m-d H:i:s', strtotime('+' . self::LOCKOUT_MINUTES . ' minutes'));
        }

        $this->db->update('users', $data, ['id' => $user['id']]);
        return $attempts;
    }

    /**
     * Check if account is locked
     * @param array $user
     * @return bool
     */
    public function is_account_locked($user)
    {
        if (empty($user['locked_until'])) return false;
        return strtotime($user['locked_until']) > time();
    }

    /**
     * Get remaining lockout time in minutes
     */
    public function get_lockout_remaining($user)
    {
        if (empty($user['locked_until'])) return 0;
        $remaining = strtotime($user['locked_until']) - time();
        return max(0, ceil($remaining / 60));
    }

    /**
     * Reset login attempts
     * @param int $user_id
     */
    public function reset_login_attempts($user_id)
    {
        $this->db->update('users', [
            'login_attempts' => 0,
            'locked_until'   => NULL
        ], ['id' => $user_id]);
    }

    /**
     * Set remember me token
     * @param int    $user_id
     * @param string $token
     */
    public function set_remember_token($user_id, $token)
    {
        $this->db->update('users', ['remember_token' => $token], ['id' => $user_id]);
    }

    /**
     * Get user by remember token
     */
    public function get_by_remember_token($token)
    {
        if (empty($token)) return null;
        return $this->db
            ->select('u.*, r.name as role_name, r.slug as role_slug')
            ->from('users u')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.remember_token', $token)
            ->where('u.is_active', 1)
            ->get()
            ->row_array();
    }

    /**
     * Clear remember token on logout
     */
    public function clear_remember_token($user_id)
    {
        $this->db->update('users', ['remember_token' => NULL], ['id' => $user_id]);
    }

    /**
     * Log login attempt
     */
    public function log_login_attempt($user_id, $email, $status)
    {
        $this->db->insert('login_logs', [
            'user_id'    => $user_id,
            'email'      => $email,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'status'     => $status,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
