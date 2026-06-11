<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Permission Model
 * Handles CRUD operations for permissions.
 */
class Permission_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all permissions
     */
    public function get_all()
    {
        return $this->db
            ->order_by('module', 'ASC')
            ->order_by('name', 'ASC')
            ->get('permissions')
            ->result_array();
    }

    /**
     * Get permissions grouped by module
     */
    public function get_grouped()
    {
        $all = $this->get_all();
        $grouped = [];
        foreach ($all as $perm) {
            $grouped[$perm['module']][] = $perm;
        }
        return $grouped;
    }

    /**
     * Get permission by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where('permissions', ['id' => $id])->row_array();
    }

    /**
     * Get permission by slug
     */
    public function get_by_slug($slug)
    {
        return $this->db->get_where('permissions', ['slug' => $slug])->row_array();
    }

    /**
     * Create permission
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('permissions', $data);
        return $this->db->insert_id();
    }

    /**
     * Update permission
     */
    public function update($id, $data)
    {
        $this->db->update('permissions', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Delete permission
     */
    public function delete($id)
    {
        // Remove from role_permissions first
        $this->db->delete('role_permissions', ['permission_id' => $id]);
        $this->db->delete('permissions', ['id' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Get all permission slugs for a given role
     * @param int $role_id
     * @return array
     */
    public function get_role_permission_slugs($role_id)
    {
        $result = $this->db
            ->select('p.slug')
            ->from('permissions p')
            ->join('role_permissions rp', 'rp.permission_id = p.id')
            ->where('rp.role_id', $role_id)
            ->get()
            ->result_array();
        return array_column($result, 'slug');
    }

    /**
     * Count total permissions
     */
    public function count_all()
    {
        return $this->db->count_all('permissions');
    }

    /**
     * Check if slug is unique
     */
    public function is_slug_unique($slug, $exclude_id = null)
    {
        $this->db->where('slug', $slug);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results('permissions') === 0;
    }

    /**
     * Get available modules
     */
    public function get_modules()
    {
        $result = $this->db->select('module')->distinct()->get('permissions')->result_array();
        return array_column($result, 'module');
    }
}
