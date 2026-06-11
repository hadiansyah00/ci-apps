<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Helper
 * Global helper functions for authentication and authorization.
 * Provides shorthand access to the Auth_lib library.
 */

if (!function_exists('auth')) {
    /**
     * Get the Auth library instance
     * @return Auth_lib
     */
    function auth()
    {
        $CI =& get_instance();
        if (!isset($CI->auth_lib)) {
            $CI->load->library('auth_lib');
        }
        return $CI->auth_lib;
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     * @return bool
     */
    function is_logged_in()
    {
        return auth()->is_logged_in();
    }
}

if (!function_exists('auth_user')) {
    /**
     * Get current authenticated user data
     * @return array|null
     */
    function auth_user()
    {
        return auth()->get_user();
    }
}

if (!function_exists('auth_user_id')) {
    /**
     * Get current user ID
     * @return int|null
     */
    function auth_user_id()
    {
        return auth()->get_user_id();
    }
}

if (!function_exists('can')) {
    /**
     * Check if current user has a permission
     * @param string $permission_slug
     * @return bool
     */
    function can($permission_slug)
    {
        return auth()->can($permission_slug);
    }
}

if (!function_exists('cannot')) {
    /**
     * Check if current user does NOT have a permission
     * @param string $permission_slug
     * @return bool
     */
    function cannot($permission_slug)
    {
        return !auth()->can($permission_slug);
    }
}

if (!function_exists('has_role')) {
    /**
     * Check if current user has a role
     * @param string|array $role_slug
     * @return bool
     */
    function has_role($role_slug)
    {
        return auth()->has_role($role_slug);
    }
}

if (!function_exists('is_super_admin')) {
    /**
     * Check if current user is Super Admin
     * @return bool
     */
    function is_super_admin()
    {
        return auth()->is_super_admin();
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is Admin or Super Admin
     * @return bool
     */
    function is_admin()
    {
        return auth()->is_admin();
    }
}

if (!function_exists('set_toast')) {
    /**
     * Set a toast notification
     * @param string $type    success|error|warning|info
     * @param string $message
     */
    function set_toast($type, $message)
    {
        auth()->set_toast($type, $message);
    }
}

if (!function_exists('current_role')) {
    /**
     * Get current user role slug
     * @return string|null
     */
    function current_role()
    {
        return auth()->get_role();
    }
}

if (!function_exists('require_login')) {
    /**
     * Redirect to login if not authenticated
     */
    function require_login()
    {
        return auth()->require_login();
    }
}

if (!function_exists('require_permission')) {
    /**
     * Redirect to 403 if user lacks permission
     * @param string $permission_slug
     */
    function require_permission($permission_slug)
    {
        return auth()->require_permission($permission_slug);
    }
}

if (!function_exists('base_url_asset')) {
    /**
     * Get asset URL
     * @param string $path
     * @return string
     */
    function base_url_asset($path = '')
    {
        $CI =& get_instance();
        return base_url('assets/' . $path);
    }
}
