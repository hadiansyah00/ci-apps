<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-key me-2" style="color: var(--info)"></i>Manajemen Permission</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span><span>Permission</span>
    </div>
</div>

<div class="content-card" data-aos="fade-up">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-key" style="color: var(--info)"></i>Daftar Permission
            <span style="background: rgba(6,182,212,0.15); color: #22d3ee; padding: 2px 10px; border-radius: 20px; font-size: 13px;"><?= $total ?></span>
        </div>
        <?php if (can('permissions.create')): ?>
        <a href="<?= base_url('admin/permissions/create') ?>" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Tambah Permission
        </a>
        <?php endif; ?>
    </div>
    <div class="content-card-body">
        <?php if (!empty($permissions)): ?>
        <?php foreach ($permissions as $module => $perms): ?>
        <div class="mb-4">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--dark-border);">
                <span style="padding: 4px 12px; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.25); border-radius: 6px; font-size: 12px; font-weight: 700; color: var(--primary-light); text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-layer-group me-1"></i><?= htmlspecialchars($module) ?>
                </span>
                <span style="font-size: 12px; color: var(--text-muted);"><?= count($perms) ?> permission</span>
            </div>
            <div class="row g-2">
                <?php foreach ($perms as $perm): ?>
                <div class="col-md-6 col-xl-4">
                    <div style="background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <div style="min-width: 0;">
                            <div style="font-size: 13px; font-weight: 600; color: #e2e8f0; margin-bottom: 3px;"><?= htmlspecialchars($perm['name']) ?></div>
                            <code style="font-size: 11px; color: #64748b; word-break: break-all;"><?= htmlspecialchars($perm['slug']) ?></code>
                        </div>
                        <div style="display: flex; gap: 5px; flex-shrink: 0;">
                            <?php if (can('permissions.edit')): ?>
                            <a href="<?= base_url('admin/permissions/edit/' . $perm['id']) ?>" class="btn-sm-icon btn-edit" style="width: 30px; height: 30px;" title="Edit"><i class="fas fa-pen" style="font-size: 12px;"></i></a>
                            <?php endif; ?>
                            <?php if (can('permissions.delete')): ?>
                            <a href="#" class="btn-sm-icon btn-delete" style="width: 30px; height: 30px;" title="Hapus" onclick="return confirmDelete('<?= base_url('admin/permissions/delete/' . $perm['id']) ?>', '<?= htmlspecialchars($perm['name']) ?>')"><i class="fas fa-trash" style="font-size: 12px;"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-key"></i><p>Belum ada permission.</p></div>
        <?php endif; ?>
    </div>
</div>
