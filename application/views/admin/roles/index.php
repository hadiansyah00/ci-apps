<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-user-shield me-2" style="color: var(--warning)"></i>Manajemen Role</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span><span>Role</span>
    </div>
</div>

<div class="content-card" data-aos="fade-up">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-user-shield" style="color: var(--warning)"></i>
            Daftar Role
        </div>
        <?php if (can('roles.create')): ?>
        <a href="<?= base_url('admin/roles/create') ?>" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Tambah Role
        </a>
        <?php endif; ?>
    </div>
    <div class="content-card-body" style="padding: 0;">
        <?php if (!empty($roles)): ?>
        <div class="table-responsive">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Role</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Pengguna</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($roles as $role): ?>
                    <tr>
                        <td style="color: var(--text-muted);"><?= $no++ ?></td>
                        <td style="font-size: 14px; font-weight: 600; color: #e2e8f0;"><?= htmlspecialchars($role['name']) ?></td>
                        <td><code style="background: rgba(99,102,241,0.1); color: #a5b4fc; padding: 3px 8px; border-radius: 6px; font-size: 12px;"><?= htmlspecialchars($role['slug']) ?></code></td>
                        <td style="font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars($role['description'] ?? '-') ?></td>
                        <td>
                            <span style="background: rgba(34,197,94,0.1); color: #4ade80; padding: 3px 10px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                <?= $role['user_count'] ?> user
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <?php if (can('roles.assign-permissions')): ?>
                                <a href="<?= base_url('admin/roles/permissions/' . $role['id']) ?>" class="btn-sm-icon btn-success-sm" title="Atur Permission">
                                    <i class="fas fa-key"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (can('roles.edit')): ?>
                                <a href="<?= base_url('admin/roles/edit/' . $role['id']) ?>" class="btn-sm-icon btn-edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (can('roles.delete')): ?>
                                <a href="#" class="btn-sm-icon btn-delete" title="Hapus" onclick="return confirmDelete('<?= base_url('admin/roles/delete/' . $role['id']) ?>', '<?= htmlspecialchars($role['name']) ?>')">
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
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-user-shield"></i><p>Belum ada role.</p></div>
        <?php endif; ?>
    </div>
</div>
