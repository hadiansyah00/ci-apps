<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-users me-2" style="color: var(--primary-light)"></i>Manajemen Pengguna</h1>
    <p>Kelola semua pengguna sistem.</p>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <span>Pengguna</span>
    </div>
</div>

<div class="content-card" data-aos="fade-up">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-list" style="color: var(--primary-light)"></i>
            Daftar Pengguna
            <span style="background: rgba(99,102,241,0.15); color: var(--primary-light); padding: 2px 10px; border-radius: 20px; font-size: 13px; font-weight: 600;"><?= $total ?></span>
        </div>
        <?php if (can('users.create')): ?>
        <a href="<?= base_url('admin/users/create') ?>" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
        <?php endif; ?>
    </div>

    <!-- Filter bar -->
    <div style="padding: 16px 24px; border-bottom: 1px solid var(--dark-border);">
        <form method="GET" action="<?= base_url('admin/users') ?>" id="filter-form">
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px;"></i>
                        <input type="text" name="search" class="form-control-custom" placeholder="Cari nama, email, username..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>" style="padding-left: 36px;">
                    </div>
                </div>
                <select name="role_id" class="form-control-custom" style="width: auto; min-width: 150px;">
                    <option value="">Semua Role</option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role['id'] ?>" <?= ($filters['role_id'] == $role['id']) ? 'selected' : '' ?>><?= htmlspecialchars($role['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="is_active" class="form-control-custom" style="width: auto; min-width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="1" <?= ($filters['is_active'] === '1') ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($filters['is_active'] === '0') ? 'selected' : '' ?>>Nonaktif</option>
                </select>
                <button type="submit" class="btn-primary-custom" style="padding: 9px 16px;"><i class="fas fa-filter"></i> Filter</button>
                <a href="<?= base_url('admin/users') ?>" style="padding: 9px 16px; background: rgba(255,255,255,0.05); border: 1px solid var(--dark-border); border-radius: 10px; color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 500;"><i class="fas fa-times"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="content-card-body" style="padding: 0;">
        <?php if (!empty($users)): ?>
        <div class="table-responsive">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login Terakhir</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $user): ?>
                    <tr>
                        <td style="color: var(--text-muted); font-size: 13px;"><?= $no++ ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #0ea5e9); display: flex; align-items: center; justify-content: center; font-size: 14px; color: white; font-weight: 700; flex-shrink: 0;">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-size: 14px; font-weight: 600; color: #e2e8f0;"><?= htmlspecialchars($user['name']) ?></div>
                                    <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size: 14px; color: var(--text-secondary); font-family: monospace;"><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <?php 
                            $role_colors = ['super-admin' => '#6366f1', 'admin' => '#0ea5e9', 'user' => '#22c55e'];
                            $rc = $role_colors[$user['role_slug']] ?? '#94a3b8';
                            ?>
                            <span style="padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; background: <?= $rc ?>22; color: <?= $rc ?>; border: 1px solid <?= $rc ?>44;">
                                <?= htmlspecialchars($user['role_name']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-status <?= $user['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                <i class="fas <?= $user['is_active'] ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                                <?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            <?= $user['last_login'] ? date('d M Y, H:i', strtotime($user['last_login'])) : '<span style="color: #475569;">Belum pernah</span>' ?>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <?php if (can('users.edit')): ?>
                                <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn-sm-icon btn-edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (can('users.toggle')): ?>
                                <a href="<?= base_url('admin/users/toggle/' . $user['id']) ?>" class="btn-sm-icon <?= $user['is_active'] ? 'btn-warning-sm' : 'btn-success-sm' ?>" title="<?= $user['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>" onclick="return confirm('Ubah status pengguna ini?')">
                                    <i class="fas <?= $user['is_active'] ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (can('users.reset-password')): ?>
                                <button class="btn-sm-icon btn-warning-sm" onclick="showResetPassword(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <?php endif; ?>
                                <?php if (can('users.delete') && $user['id'] != auth_user_id()): ?>
                                <a href="#" class="btn-sm-icon btn-delete" title="Hapus" onclick="return confirmDelete('<?= base_url('admin/users/delete/' . $user['id']) ?>', '<?= htmlspecialchars($user['name']) ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total > 10): ?>
        <div style="padding: 20px 24px; border-top: 1px solid var(--dark-border);">
            <?= $pagination ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <p>Tidak ada pengguna ditemukan.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog" style="--bs-modal-width: 400px;">
        <div class="modal-content" style="background: #1e293b; border: 1px solid #2d3f5a; border-radius: 16px;">
            <div class="modal-header" style="border-bottom: 1px solid #2d3f5a; padding: 20px 24px;">
                <h5 class="modal-title" style="color: #e2e8f0; font-size: 16px; font-weight: 700;">
                    <i class="fas fa-key me-2" style="color: var(--warning)"></i>Reset Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="reset-password-form" method="POST" action="">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="modal-body" style="padding: 24px;">
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">Reset password untuk: <strong style="color: #e2e8f0;" id="reset-user-name"></strong></p>
                    <label class="form-label-custom">Password Baru *</label>
                    <input type="password" name="new_password" class="form-control-custom" placeholder="Min. 6 karakter" required minlength="6">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #2d3f5a; padding: 16px 24px; gap: 8px;">
                    <button type="button" class="btn-primary-custom" style="background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-key me-1"></i>Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showResetPassword(userId, userName) {
    document.getElementById('reset-user-name').textContent = userName;
    document.getElementById('reset-password-form').action = '<?= base_url('admin/users/reset-password/') ?>' + userId;
    new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
}
</script>
