<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-key me-2" style="color: var(--info)"></i><?= $is_edit ? 'Edit Permission' : 'Tambah Permission' ?></h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/permissions') ?>">Permission</a>
        <span class="separator">/</span>
        <span><?= $is_edit ? 'Edit' : 'Tambah' ?></span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8">
        <div class="content-card" data-aos="fade-up">
            <div class="content-card-header">
                <div class="content-card-title"><i class="fas fa-key" style="color: var(--info)"></i><?= $is_edit ? 'Edit Permission' : 'Permission Baru' ?></div>
            </div>
            <div class="content-card-body">
                <form method="POST" action="<?= base_url($is_edit ? 'admin/permissions/update/' . $permission['id'] : 'admin/permissions/store') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Nama Permission *</label>
                            <input type="text" name="name" class="form-control-custom" value="<?= htmlspecialchars($permission['name'] ?? '') ?>" placeholder="Contoh: View Users" required id="perm-name">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Slug * <small style="color: var(--text-muted); font-weight: 400;">(format: module.action)</small></label>
                            <input type="text" name="slug" class="form-control-custom" value="<?= htmlspecialchars($permission['slug'] ?? '') ?>" placeholder="users.view" required id="perm-slug">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Module *</label>
                            <input type="text" name="module" class="form-control-custom" list="module-list" value="<?= htmlspecialchars($permission['module'] ?? '') ?>" placeholder="users" required>
                            <datalist id="module-list">
                                <?php foreach ($modules as $mod): ?>
                                <option value="<?= htmlspecialchars($mod) ?>">
                                <?php endforeach; ?>
                            </datalist>
                            <small style="color: var(--text-muted); font-size: 12px;">Nama modul untuk pengelompokan (contoh: users, roles, dashboard)</small>
                        </div>
                    </div>
                    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--dark-border); display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('admin/permissions') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i> <?= $is_edit ? 'Simpan' : 'Tambah' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from name (only on create)
$('#perm-name').on('input', function() {
    if (!<?= $is_edit ? 'true' : 'false' ?>) {
        // Just lowercase and clean for slug preview
        // Slug format is module.action, so we leave it for manual input
    }
});
</script>
