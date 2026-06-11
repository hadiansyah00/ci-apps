<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Default route - redirect to landing page
$route['default_controller'] = 'Tracking';
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

// Vehicles CRUD routes
$route['admin/vehicles'] = 'Admin/Vehicles/index';
$route['admin/vehicles/create'] = 'Admin/Vehicles/create';
$route['admin/vehicles/store'] = 'Admin/Vehicles/store';
$route['admin/vehicles/edit/(:num)'] = 'Admin/Vehicles/edit/$1';
$route['admin/vehicles/update/(:num)'] = 'Admin/Vehicles/update/$1';
$route['admin/vehicles/delete/(:num)'] = 'Admin/Vehicles/delete/$1';

// Orders CRUD & Dispatch routes
$route['admin/orders'] = 'Admin/Orders/index';
$route['admin/orders/create'] = 'Admin/Orders/create';
$route['admin/orders/store'] = 'Admin/Orders/store';
$route['admin/orders/edit/(:num)'] = 'Admin/Orders/edit/$1';
$route['admin/orders/update/(:num)'] = 'Admin/Orders/update/$1';
$route['admin/orders/delete/(:num)'] = 'Admin/Orders/delete/$1';
$route['admin/orders/dispatch/(:num)'] = 'Admin/Orders/dispatch/$1';
$route['admin/orders/assign/(:num)'] = 'Admin/Orders/assign/$1';
$route['admin/orders/print-sj/(:num)'] = 'Admin/Orders/print_sj/$1';
$route['admin/orders/verify-pod/(:num)'] = 'Admin/Orders/verify_pod/$1';

// Inspection routes (Admin/Checker side)
$route['admin/inspections'] = 'Admin/Inspections/index';
$route['admin/inspections/check/(:num)'] = 'Admin/Inspections/check/$1';
$route['admin/inspections/store/(:num)'] = 'Admin/Inspections/store/$1';
$route['admin/inspections/verify-loading/(:num)'] = 'Admin/Inspections/verify_loading/$1';
$route['admin/inspections/store-loading/(:num)'] = 'Admin/Inspections/store_loading/$1';

// Driver Mobile routes
$route['driver/tasks'] = 'Driver/Tasks/index';
$route['driver/tasks/update-status/(:num)/(:any)'] = 'Driver/Tasks/update_status/$1/$2';
$route['driver/tasks/pod/(:num)'] = 'Driver/Tasks/pod/$1';
$route['driver/tasks/upload-pod/(:num)'] = 'Driver/Tasks/upload_pod/$1';
$route['driver/tasks/log-location/(:num)'] = 'Driver/Tasks/log_location/$1';

// Public Tracking route
$route['track'] = 'Tracking/index';
$route['tracking/get-live-location/(:num)'] = 'Tracking/get_live_location/$1';

