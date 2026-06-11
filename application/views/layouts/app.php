<?php defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$auth = $CI->auth_lib;
$current_user = $auth->get_user();
$menu_items = $auth->get_menu_items();
$current_uri = $CI->uri->uri_string();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard') ?> &mdash; CI3 Auth System</title>
    <meta name="description" content="CI3 Auth & RBAC Management System">
    
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Toastr.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #0f172a;
            --dark-surface: #1e293b;
            --dark-card: #243048;
            --dark-border: #2d3f5a;
            --text-primary: #e2e8f0;
            --text-muted: #64748b;
            --text-secondary: #94a3b8;
            --sidebar-width: 270px;
            --navbar-height: 65px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* =============================================
           SIDEBAR
        ============================================= */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a1f35 0%, #111827 100%);
            border-right: 1px solid rgba(99,102,241,0.15);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease, width 0.3s ease;
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
        }

        .sidebar-brand-text .app-name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .sidebar-brand-text .app-version {
            font-size: 11px;
            color: var(--text-muted);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(99,102,241,0.3) transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 2px; }

        .nav-section-title {
            padding: 12px 24px 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .nav-item { padding: 2px 12px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(99,102,241,0.1);
            color: #c7d2fe;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(99,102,241,0.1));
            color: #fff;
            border-left: 3px solid var(--primary);
        }

        .nav-link .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .nav-link .nav-arrow {
            margin-left: auto;
            font-size: 11px;
            transition: transform 0.3s;
        }

        .nav-link[aria-expanded="true"] .nav-arrow {
            transform: rotate(90deg);
        }

        /* Submenu */
        .nav-submenu {
            padding-left: 12px;
        }

        .nav-submenu .nav-link {
            padding: 8px 14px;
            font-size: 13px;
            color: #64748b;
        }

        .nav-submenu .nav-link:hover { color: #94a3b8; }
        .nav-submenu .nav-link.active { color: var(--primary-light); }

        .nav-submenu .nav-link::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.5;
            flex-shrink: 0;
        }

        .nav-submenu .nav-link.active::before {
            opacity: 1;
            background: var(--primary-light);
        }

        /* User card at bottom of sidebar */
        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-info .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
            line-height: 1.3;
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info .user-role {
            font-size: 11px;
            color: var(--primary-light);
            font-weight: 500;
        }

        .sidebar-logout {
            margin-left: auto;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .sidebar-logout:hover {
            background: rgba(239,68,68,0.2);
            color: #f87171;
        }

        /* =============================================
           MAIN CONTENT
        ============================================= */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Navbar */
        .topbar {
            height: var(--navbar-height);
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(99,102,241,0.1);
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 1030;
            gap: 16px;
        }

        .topbar-toggle {
            background: none;
            border: none;
            color: #64748b;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
            display: none; /* shown on mobile */
        }

        .topbar-toggle:hover { background: rgba(99,102,241,0.1); color: var(--primary-light); }

        .topbar-title {
            font-size: 17px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 8px;
            background: rgba(99,102,241,0.08);
            border: 1px solid rgba(99,102,241,0.15);
            border-radius: 12px;
            color: #e2e8f0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .topbar-user-btn:hover {
            background: rgba(99,102,241,0.15);
            color: #fff;
        }

        .topbar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: white;
            font-weight: 700;
        }

        .role-badge {
            padding: 3px 10px;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--primary-light);
        }

        /* Page content */
        .page-content {
            flex: 1;
            padding: 28px;
        }

        /* =============================================
           COMMON COMPONENTS
        ============================================= */

        /* Stats Cards */
        .stat-card {
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
        }

        .stat-card.primary::before { background: linear-gradient(90deg, var(--primary), var(--secondary)); }
        .stat-card.success::before { background: linear-gradient(90deg, var(--success), #16a34a); }
        .stat-card.warning::before { background: linear-gradient(90deg, var(--warning), #d97706); }
        .stat-card.danger::before  { background: linear-gradient(90deg, var(--danger), #dc2626); }
        .stat-card.info::before    { background: linear-gradient(90deg, var(--info), var(--secondary)); }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
            border-color: rgba(99,102,241,0.3);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-icon.primary { background: rgba(99,102,241,0.15); color: var(--primary-light); }
        .stat-icon.success { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .stat-icon.warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .stat-icon.danger  { background: rgba(239,68,68,0.15);  color: #f87171; }
        .stat-icon.info    { background: rgba(6,182,212,0.15);  color: #22d3ee; }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Content Card */
        .content-card {
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            border-radius: 16px;
            overflow: hidden;
        }

        .content-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--dark-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-card-body { padding: 24px; }

        /* Table */
        .table-dark-custom {
            width: 100%;
            border-collapse: collapse;
        }

        .table-dark-custom thead th {
            padding: 12px 16px;
            background: rgba(15,23,42,0.5);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--dark-border);
        }

        .table-dark-custom tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(45,63,90,0.5);
            font-size: 14px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .table-dark-custom tbody tr:hover {
            background: rgba(99,102,241,0.05);
        }

        .table-dark-custom tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .badge-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(34,197,94,0.15);
            color: #4ade80;
            border: 1px solid rgba(34,197,94,0.25);
        }

        .badge-inactive {
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
        }

        .badge-role {
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Buttons */
        .btn-primary-custom {
            padding: 8px 18px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(99,102,241,0.4);
            color: white;
        }

        .btn-sm-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-edit       { background: rgba(6,182,212,0.1);  color: #22d3ee; border: 1px solid rgba(6,182,212,0.2); }
        .btn-edit:hover { background: rgba(6,182,212,0.2);  color: #67e8f9; }
        .btn-delete       { background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.2); }
        .btn-delete:hover { background: rgba(239,68,68,0.2); color: #fca5a5; }
        .btn-success-sm       { background: rgba(34,197,94,0.1);  color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .btn-success-sm:hover { background: rgba(34,197,94,0.2); }
        .btn-warning-sm       { background: rgba(245,158,11,0.1); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
        .btn-warning-sm:hover { background: rgba(245,158,11,0.2); }

        /* Page Header */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .breadcrumb-custom a { color: var(--primary-light); text-decoration: none; }
        .breadcrumb-custom a:hover { color: #c7d2fe; }
        .breadcrumb-custom .separator { color: #334155; }

        /* Form styles */
        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 11px 14px;
            background: rgba(15,23,42,0.5);
            border: 1px solid var(--dark-border);
            border-radius: 10px;
            color: #e2e8f0;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            background: rgba(15,23,42,0.7);
        }

        .form-control-custom::placeholder { color: #475569; }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .topbar-toggle { display: block; }

            .page-content { padding: 20px 16px; }
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
            display: none;
        }

        .sidebar-overlay.active { display: block; }

        /* Pagination */
        .pagination-custom {
            display: flex;
            gap: 4px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination-custom a,
        .pagination-custom span {
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination-custom a {
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            color: var(--text-secondary);
        }

        .pagination-custom a:hover {
            background: rgba(99,102,241,0.1);
            border-color: var(--primary);
            color: var(--primary-light);
        }

        .pagination-custom .current-page {
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }

        /* Alert boxes */
        .alert-custom {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .alert-custom.alert-danger {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #fca5a5;
        }

        .alert-custom.alert-warning {
            background: rgba(245,158,11,0.1);
            border: 1px solid rgba(245,158,11,0.25);
            color: #fde68a;
        }

        .alert-custom.alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.25);
            color: #bbf7d0;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.4;
        }

        .empty-state p { font-size: 15px; }
    </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <div class="sidebar-brand-text">
            <div class="app-name">CI3 Auth</div>
            <div class="app-version">RBAC v1.0</div>
        </div>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section-title">Navigasi Utama</div>

        <?php foreach ($menu_items as $item): ?>

        <?php if (empty($item['submenu'])): ?>
        <!-- Single nav item -->
        <div class="nav-item">
            <a href="<?= base_url($item['url']) ?>"
               class="nav-link <?= (strpos($current_uri, $item['url']) === 0) ? 'active' : '' ?>">
                <span class="nav-icon"><i class="<?= $item['icon'] ?>"></i></span>
                <?= htmlspecialchars($item['title']) ?>
            </a>
        </div>
        <?php else: ?>
        <!-- Collapsible nav item -->
        <?php
        $is_open = false;
        foreach ($item['submenu'] as $sub) {
            if (strpos($current_uri, $sub['url']) === 0) { $is_open = true; break; }
        }
        $collapse_id = 'nav-' . str_replace([' ', '/'], '-', strtolower($item['title']));
        ?>
        <div class="nav-item">
            <a href="#" class="nav-link <?= $is_open ? 'active' : '' ?>"
               data-bs-toggle="collapse" data-bs-target="#<?= $collapse_id ?>"
               aria-expanded="<?= $is_open ? 'true' : 'false' ?>">
                <span class="nav-icon"><i class="<?= $item['icon'] ?>"></i></span>
                <?= htmlspecialchars($item['title']) ?>
                <i class="fas fa-chevron-right nav-arrow"></i>
            </a>
            <div id="<?= $collapse_id ?>" class="collapse <?= $is_open ? 'show' : '' ?>">
                <div class="nav-submenu">
                    <?php foreach ($item['submenu'] as $sub): ?>
                    <div class="nav-item">
                        <a href="<?= base_url($sub['url']) ?>"
                           class="nav-link <?= (strpos($current_uri, $sub['url']) === 0) ? 'active' : '' ?>">
                            <?= htmlspecialchars($sub['title']) ?>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php endforeach; ?>
    </nav>

    <!-- User Card at bottom -->
    <div class="sidebar-user">
        <div class="user-avatar">
            <?= strtoupper(substr($current_user['name'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($current_user['name'] ?? '') ?></div>
            <div class="user-role"><?= htmlspecialchars($current_user['role_name'] ?? '') ?></div>
        </div>
        <a href="<?= base_url('logout') ?>" class="sidebar-logout" id="btn-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Topbar -->
    <header class="topbar">
        <button class="topbar-toggle" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></div>

        <div class="topbar-right">
            <span class="role-badge">
                <i class="fas fa-user-shield me-1"></i>
                <?= htmlspecialchars($current_user['role_name'] ?? '') ?>
            </span>
            <div class="dropdown">
                <a href="#" class="topbar-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="topbar-avatar">
                        <?= strtoupper(substr($current_user['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <span><?= htmlspecialchars(explode(' ', $current_user['name'] ?? 'User')[0]) ?></span>
                    <i class="fas fa-chevron-down" style="font-size: 11px; opacity: 0.6"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end"
                    style="background: #1e293b; border: 1px solid #2d3f5a; border-radius: 12px; min-width: 180px;">
                    <li>
                        <a class="dropdown-item" href="#"
                           style="color: #e2e8f0; font-size: 14px; padding: 10px 16px;">
                            <i class="fas fa-user me-2 text-primary"></i>Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider" style="border-color: #2d3f5a;"></li>
                    <li>
                        <a class="dropdown-item" href="<?= base_url('logout') ?>" id="topbar-logout"
                           style="color: #f87171; font-size: 14px; padding: 10px 16px;">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        <?php $this->load->view($page, $data ?? []); ?>
    </main>

    <!-- Footer -->
    <footer style="padding: 16px 28px; border-top: 1px solid rgba(45,63,90,0.5);
                   display: flex; align-items: center; justify-content: space-between;">
        <div style="font-size: 13px; color: #475569;">
            &copy; <?= date('Y') ?> CI3 Auth &amp; RBAC System
        </div>
        <div style="font-size: 12px; color: #334155;">
            CodeIgniter 3 &mdash; PHP 7.4
        </div>
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Initialize AOS animations
AOS.init({ duration: 400, once: true, offset: 20 });

// Toastr global config
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 4000,
    newestOnTop: true,
};

// Show toast from PHP session flashdata
<?php
$CI2 =& get_instance();
$toast_type = $CI2->session->flashdata('toast_type');
$toast_msg  = $CI2->session->flashdata('toast_message');
if ($toast_type && $toast_msg):
?>
$(document).ready(function() {
    toastr['<?= $toast_type ?>']('<?= addslashes($toast_msg) ?>');
});
<?php endif; ?>

// Sidebar toggle (mobile)
$('#sidebar-toggle').on('click', function() {
    $('#sidebar').toggleClass('sidebar-open');
    $('#sidebar-overlay').toggleClass('active');
});

$('#sidebar-overlay').on('click', function() {
    $('#sidebar').removeClass('sidebar-open');
    $(this).removeClass('active');
});

// Logout confirmation via SweetAlert2
$(document).on('click', '#btn-logout, #topbar-logout', function(e) {
    e.preventDefault();
    var href = '<?= base_url('logout') ?>';
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar dari sistem?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6366f1',
        cancelButtonColor: '#475569',
        confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i> Ya, Logout',
        cancelButtonText: 'Batal',
        background: '#1e293b',
        color: '#e2e8f0',
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
});

/**
 * Global delete confirmation helper.
 * Usage: onclick="return confirmDelete('<?= base_url('admin/users/delete/1') ?>', 'John Doe')"
 *
 * @param {string} url   - The delete URL to redirect to on confirm
 * @param {string} name  - Human-readable name of the item being deleted
 */
function confirmDelete(url, name) {
    Swal.fire({
        title: 'Hapus Data?',
        html: 'Data <strong>' + name + '</strong> akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#475569',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        background: '#1e293b',
        color: '#e2e8f0',
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    return false;
}
</script>
</body>
</html>
