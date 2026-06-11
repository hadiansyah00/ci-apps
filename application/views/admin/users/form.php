<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1>
        <i class="fas <?= $is_edit ? 'fa-user-pen' : 'fa-user-plus' ?> me-2" style="color: var(--primary-light)"></i>
        <?= $is_edit ? 'Edit Pengguna' : 'Tambah Pengguna' ?>
    </h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/users') ?>">Pengguna</a>
        <span class="separator">/</span>
        <span><?= $is_edit ? 'Edit' : 'Tambah' ?></span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-9">
        <div class="content-card" data-aos="fade-up">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-user" style="color: var(--primary-light)"></i>
                    <?= $is_edit ? 'Edit Data Pengguna' : 'Form Pengguna Baru' ?>
                </div>
            </div>
            <div class="content-card-body">
                <form method="POST" action="<?= base_url($is_edit ? 'admin/users/update/' . $user['id'] : 'admin/users/store') ?>" autocomplete="off">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Lengkap *</label>
                            <input type="text" name="name" class="form-control-custom" value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Email *</label>
                            <input type="email" name="email" class="form-control-custom" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="email@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Username *</label>
                            <input type="text" name="username" class="form-control-custom" value="<?= htmlspecialchars($user['username'] ?? '') ?>" placeholder="username_unik" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Role *</label>
                            <select name="role_id" class="form-control-custom" required>
                                <option value="">-- Pilih Role --</option>
                                <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= (isset($user['role_id']) && $user['role_id'] == $role['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom"><?= $is_edit ? 'Password Baru (kosongkan jika tidak diubah)' : 'Password *' ?></label>
                            <input type="password" name="password" class="form-control-custom" placeholder="<?= $is_edit ? 'Kosongkan jika tidak ingin mengubah' : 'Min. 6 karakter' ?>" <?= $is_edit ? '' : 'required' ?> minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select name="is_active" class="form-control-custom">
                                <option value="1" <?= (!$is_edit || $user['is_active']) ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= ($is_edit && !$user['is_active']) ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--dark-border); display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('admin/users') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas <?= $is_edit ? 'fa-save' : 'fa-plus' ?>"></i>
                            <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
