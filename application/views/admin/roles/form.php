<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-user-shield me-2" style="color: var(--warning)"></i><?= $is_edit ? 'Edit Role' : 'Tambah Role' ?></h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/roles') ?>">Role</a>
        <span class="separator">/</span>
        <span><?= $is_edit ? 'Edit' : 'Tambah' ?></span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8">
        <div class="content-card" data-aos="fade-up">
            <div class="content-card-header">
                <div class="content-card-title"><i class="fas fa-user-shield" style="color: var(--warning)"></i><?= $is_edit ? 'Edit Role' : 'Role Baru' ?></div>
            </div>
            <div class="content-card-body">
                <form method="POST" action="<?= base_url($is_edit ? 'admin/roles/update/' . $role['id'] : 'admin/roles/store') ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Nama Role *</label>
                            <input type="text" name="name" class="form-control-custom" value="<?= htmlspecialchars($role['name'] ?? '') ?>" placeholder="Contoh: Super Admin" required id="role-name">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Slug * <small style="color: var(--text-muted); font-weight: 400;">(unik, huruf kecil, tanda hubung)</small></label>
                            <input type="text" name="slug" class="form-control-custom" value="<?= htmlspecialchars($role['slug'] ?? '') ?>" placeholder="super-admin" required id="role-slug">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi</label>
                            <textarea name="description" class="form-control-custom" rows="3" placeholder="Deskripsi role..." style="resize: vertical;"><?= htmlspecialchars($role['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--dark-border); display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('admin/roles') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i> <?= $is_edit ? 'Simpan' : 'Tambah Role' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Auto-generate slug from name (only on create, not edit)
$('#role-name').on('input', function() {
    if (!<?= $is_edit ? 'true' : 'false' ?>) {
        var slug = $(this).val().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim('-');
        $('#role-slug').val(slug);
    }
});
</script>
