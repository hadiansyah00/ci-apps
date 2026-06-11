<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Page Header -->
<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-tachometer-alt me-2" style="color: var(--primary-light)"></i>Dashboard</h1>
    <p>Selamat datang kembali, <strong style="color: var(--primary-light)"><?= htmlspecialchars(auth_user()['name'] ?? '') ?></strong>! Berikut ringkasan sistem Anda.</p>
    <div class="breadcrumb-custom">
        <i class="fas fa-home"></i>
        <span class="separator">/</span>
        <span>Dashboard</span>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?= $total_users ?></div>
            <div class="stat-label">Total Pengguna</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="80">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-user-check"></i></div>
            <div class="stat-value"><?= $active_users ?></div>
            <div class="stat-label">Pengguna Aktif</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="160">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fas fa-user-shield"></i></div>
            <div class="stat-value"><?= $total_roles ?></div>
            <div class="stat-label">Total Role</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="240">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fas fa-key"></i></div>
            <div class="stat-value"><?= $total_permissions ?></div>
            <div class="stat-label">Total Permission</div>
        </div>
    </div>
</div>

<!-- Second row -->
<div class="row g-4 mb-4">
    <!-- Roles with user count -->
    <div class="col-xl-5" data-aos="fade-up" data-aos-delay="0">
        <div class="content-card h-100">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-user-shield" style="color: var(--warning)"></i>
                    Distribusi Role
                </div>
            </div>
            <div class="content-card-body">
                <?php if (!empty($roles_with_count)): ?>
                <?php foreach ($roles_with_count as $role): ?>
                <?php 
                    $pct = $total_users > 0 ? round(($role['user_count'] / $total_users) * 100) : 0;
                    $role_color_map = ['super-admin' => '#6366f1', 'admin' => '#0ea5e9', 'user' => '#22c55e'];
                    $color = isset($role_color_map[$role['slug']]) ? $role_color_map[$role['slug']] : '#94a3b8';
                ?>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span style="font-size: 14px; font-weight: 600; color: #e2e8f0;"><?= htmlspecialchars($role['name']) ?></span>
                            <span class="badge-status ms-2" style="background: <?= $color ?>22; color: <?= $color ?>; border: 1px solid <?= $color ?>44; font-size: 11px; padding: 2px 8px; border-radius: 20px;"><?= $role['user_count'] ?> user</span>
                        </div>
                        <span style="font-size: 13px; color: var(--text-muted);"><?= $pct ?>%</span>
                    </div>
                    <div style="height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: <?= $pct ?>%; background: linear-gradient(90deg, <?= $color ?>, <?= $color ?>88); border-radius: 4px; transition: width 1s ease;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state"><p>Tidak ada data role.</p></div>
                <?php endif; ?>

                <div class="mt-3 pt-3" style="border-top: 1px solid var(--dark-border);">
                    <div class="d-flex justify-content-between" style="font-size: 13px; color: var(--text-muted);">
                        <span><i class="fas fa-user-times me-1" style="color: #f87171;"></i>Nonaktif: <?= $inactive_users ?></span>
                        <span><i class="fas fa-user-check me-1" style="color: #4ade80;"></i>Aktif: <?= $active_users ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent logins -->
    <div class="col-xl-7" data-aos="fade-up" data-aos-delay="100">
        <div class="content-card h-100">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-clock" style="color: var(--info)"></i>
                    Login Terakhir
                </div>
                <?php if (can('users.view')): ?>
                <a href="<?= base_url('admin/users') ?>" class="btn-primary-custom" style="padding: 6px 14px; font-size: 13px;">
                    <i class="fas fa-arrow-right"></i> Semua
                </a>
                <?php endif; ?>
            </div>
            <div class="content-card-body" style="padding: 0;">
                <?php if (!empty($recent_logins)): ?>
                <div class="table-responsive">
                    <table class="table-dark-custom" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Role</th>
                                <th>Login Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_logins as $u): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #6366f1, #0ea5e9); display: flex; align-items: center; justify-content: center; font-size: 13px; color: white; font-weight: 600; flex-shrink: 0;">
                                            <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: #e2e8f0;"><?= htmlspecialchars($u['name']) ?></div>
                                            <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($u['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-role" style="background: rgba(99,102,241,0.15); color: #a5b4fc; font-size: 12px;">
                                        <?= htmlspecialchars($u['role_name']) ?>
                                    </span>
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    <?= $u['last_login'] ? date('d M Y, H:i', strtotime($u['last_login'])) : '-' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clock-rotate-left"></i>
                    <p>Belum ada data login.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- System Info Cards -->
<div class="row g-4" data-aos="fade-up">
    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-body">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; background: rgba(34,197,94,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #4ade80;">
                        <i class="fas fa-server"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">PHP Version</div>
                        <div style="font-size: 16px; font-weight: 700; color: #e2e8f0;"><?= phpversion() ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-body">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; background: rgba(99,102,241,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--primary-light);">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">CodeIgniter</div>
                        <div style="font-size: 16px; font-weight: 700; color: #e2e8f0;">v<?= CI_VERSION ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <div class="content-card-body">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; background: rgba(14,165,233,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #38bdf8;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">Tanggal Server</div>
                        <div style="font-size: 15px; font-weight: 700; color: #e2e8f0;"><?= date('d M Y, H:i') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
