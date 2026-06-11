<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Default route - redirect to login
$route['default_controller'] = 'Auth';
$route['404_override'] = 'errors/Page_404';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['login'] = 'Auth/login';
$route['logout'] = 'Auth/logout';
$route['auth/login'] = 'Auth/login';

// Admin routes
$route['admin'] = 'Admin/Dashboard';
$route['admin/dashboard'] = 'Admin/Dashboard/index';

// User Management routes
$route['admin/users'] = 'Admin/Users/index';
$route['admin/users/create'] = 'Admin/Users/create';
$route['admin/users/store'] = 'Admin/Users/store';
$route['admin/users/edit/(:num)'] = 'Admin/Users/edit/$1';
$route['admin/users/update/(:num)'] = 'Admin/Users/update/$1';
$route['admin/users/delete/(:num)'] = 'Admin/Users/delete/$1';
$route['admin/users/toggle/(:num)'] = 'Admin/Users/toggle/$1';
$route['admin/users/reset-password/(:num)'] = 'Admin/Users/reset_password/$1';

// Role Management routes
$route['admin/roles'] = 'Admin/Roles/index';
$route['admin/roles/create'] = 'Admin/Roles/create';
$route['admin/roles/store'] = 'Admin/Roles/store';
$route['admin/roles/edit/(:num)'] = 'Admin/Roles/edit/$1';
$route['admin/roles/update/(:num)'] = 'Admin/Roles/update/$1';
$route['admin/roles/delete/(:num)'] = 'Admin/Roles/delete/$1';
$route['admin/roles/permissions/(:num)'] = 'Admin/Roles/permissions/$1';
$route['admin/roles/assign-permissions/(:num)'] = 'Admin/Roles/assign_permissions/$1';

// Permission Management routes
$route['admin/permissions'] = 'Admin/Permissions/index';
$route['admin/permissions/create'] = 'Admin/Permissions/create';
$route['admin/permissions/store'] = 'Admin/Permissions/store';
$route['admin/permissions/edit/(:num)'] = 'Admin/Permissions/edit/$1';
$route['admin/permissions/update/(:num)'] = 'Admin/Permissions/update/$1';
$route['admin/permissions/delete/(:num)'] = 'Admin/Permissions/delete/$1';
