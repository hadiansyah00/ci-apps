<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Model
 * CRUD operations for user management.
 */
class User_model extends CI_Model {

    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all users with role info, with optional filters
     * @param array $filters  Keys: role_id, is_active, search
     * @return array
     */
    public function get_all($filters = [])
    {
        $this->db->select('u.*, r.name as role_name, r.slug as role_slug');
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');

        if (!empty($filters['role_id'])) {
            $this->db->where('u.role_id', $filters['role_id']);
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $this->db->where('u.is_active', $filters['is_active']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('u.name', $search);
            $this->db->or_like('u.email', $search);
            $this->db->or_like('u.username', $search);
            $this->db->group_end();
        }

        $this->db->order_by('u.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Get paginated users
     */
    public function get_paginated($limit, $offset, $filters = [])
    {
        $this->db->select('u.*, r.name as role_name, r.slug as role_slug');
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->_apply_filters($filters);
        $this->db->order_by('u.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    /**
     * Count users with optional filters
     */
    public function count_all($filters = [])
    {
        $this->db->from('users u');
        $this->_apply_filters($filters);
        return $this->db->count_all_results();
    }

    protected function _apply_filters($filters)
    {
        if (!empty($filters['role_id'])) {
            $this->db->where('u.role_id', $filters['role_id']);
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $this->db->where('u.is_active', $filters['is_active']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('u.name', $search);
            $this->db->or_like('u.email', $search);
            $this->db->or_like('u.username', $search);
            $this->db->group_end();
        }
    }

    /**
     * Find user by ID with role info
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
     * Create a new user
     * @param array $data
     * @return int|bool  Insert ID or false
     */
    public function create($data)
    {
        $data['password']   = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    /**
     * Update user data
     * @param int   $id
     * @param array $data
     */
    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update('users', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $this->db->delete('users', ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Toggle user active status
     */
    public function toggle_status($id)
    {
        $user = $this->get_by_id($id);
        if (!$user) return false;

        $new_status = $user['is_active'] ? 0 : 1;
        $this->db->update('users', [
            'is_active'  => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        return $new_status;
    }

    /**
     * Reset user password
     */
    public function reset_password($id, $new_password)
    {
        $this->db->update('users', [
            'password'   => password_hash($new_password, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Count statistics
     */
    public function count_active()
    {
        return $this->db->where('is_active', 1)->count_all_results('users');
    }

    public function count_inactive()
    {
        return $this->db->where('is_active', 0)->count_all_results('users');
    }

    /**
     * Get recent users with last login
     */
    public function get_recently_logged_in($limit = 5)
    {
        return $this->db
            ->select('u.name, u.email, u.username, u.last_login, u.avatar, r.name as role_name')
            ->from('users u')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.last_login IS NOT NULL')
            ->order_by('u.last_login', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    /**
     * Check if email is unique (optionally excluding a user ID for update)
     */
    public function is_email_unique($email, $exclude_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results('users') === 0;
    }

    /**
     * Check if username is unique
     */
    public function is_username_unique($username, $exclude_id = null)
    {
        $this->db->where('username', $username);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results('users') === 0;
    }
}
