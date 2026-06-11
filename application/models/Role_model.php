<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Role Model
 * Handles CRUD operations for roles.
 */
class Role_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all roles
     */
    public function get_all()
    {
        return $this->db->order_by('id', 'ASC')->get('roles')->result_array();
    }

    /**
     * Get role by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where('roles', ['id' => $id])->row_array();
    }

    /**
     * Get role by slug
     */
    public function get_by_slug($slug)
    {
        return $this->db->get_where('roles', ['slug' => $slug])->row_array();
    }

    /**
     * Create role
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('roles', $data);
        return $this->db->insert_id();
    }

    /**
     * Update role
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update('roles', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Delete role (only if no users assigned)
     */
    public function delete($id)
    {
        $user_count = $this->db->where('role_id', $id)->count_all_results('users');
        if ($user_count > 0) {
            return false; // Cannot delete role with users
        }
        $this->db->delete('roles', ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Get permissions assigned to a role
     */
    public function get_permissions($role_id)
    {
        return $this->db
            ->select('p.*, rp.id as assigned_id')
            ->from('permissions p')
            ->join('role_permissions rp', "rp.permission_id = p.id AND rp.role_id = {$role_id}", 'left')
            ->order_by('p.module', 'ASC')
            ->order_by('p.name', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Get assigned permission IDs for a role
     */
    public function get_permission_ids($role_id)
    {
        $result = $this->db
            ->select('permission_id')
            ->where('role_id', $role_id)
            ->get('role_permissions')
            ->result_array();
        return array_column($result, 'permission_id');
    }

    /**
     * Sync permissions for a role
     * @param int   $role_id
     * @param array $permission_ids
     */
    public function sync_permissions($role_id, $permission_ids = [])
    {
        // Remove all existing
        $this->db->delete('role_permissions', ['role_id' => $role_id]);

        // Insert new
        if (!empty($permission_ids)) {
            $insert_data = [];
            foreach ($permission_ids as $perm_id) {
                $insert_data[] = [
                    'role_id'       => $role_id,
                    'permission_id' => (int)$perm_id
                ];
            }
            $this->db->insert_batch('role_permissions', $insert_data);
        }
        return true;
    }

    /**
     * Get roles with user count
     */
    public function get_with_user_count()
    {
        return $this->db
            ->select('r.*, COUNT(u.id) as user_count')
            ->from('roles r')
            ->join('users u', 'u.role_id = r.id', 'left')
            ->group_by('r.id')
            ->order_by('r.id', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Check if slug is unique
     */
    public function is_slug_unique($slug, $exclude_id = null)
    {
        $this->db->where('slug', $slug);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results('roles') === 0;
    }
}
