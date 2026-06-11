<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-key me-2" style="color: var(--success)"></i>Atur Permission: <?= htmlspecialchars($role['name']) ?></h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/roles') ?>">Role</a>
        <span class="separator">/</span>
        <span>Permission</span>
    </div>
</div>

<div class="content-card" data-aos="fade-up">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-key" style="color: var(--success)"></i>
            Pilih Permission untuk <strong style="color: var(--primary-light);"><?= htmlspecialchars($role['name']) ?></strong>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="selectAll()" class="btn-primary-custom" style="padding: 6px 14px; font-size: 13px; background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80;"><i class="fas fa-check-double"></i> Semua</button>
            <button onclick="deselectAll()" class="btn-primary-custom" style="padding: 6px 14px; font-size: 13px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #f87171;"><i class="fas fa-times"></i> Hapus Semua</button>
        </div>
    </div>
    <div class="content-card-body">
        <form method="POST" action="<?= base_url('admin/roles/assign-permissions/' . $role['id']) ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <?php if (!empty($perms_by_module)): ?>
            <div class="row g-4">
                <?php foreach ($perms_by_module as $module => $perms): ?>
                <div class="col-md-6 col-xl-4">
                    <div style="background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 12px; overflow: hidden;">
                        <div style="padding: 14px 18px; border-bottom: 1px solid var(--dark-border); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 13px; font-weight: 700; color: #e2e8f0; text-transform: capitalize;">
                                <i class="fas fa-layer-group me-2" style="color: var(--primary-light);"></i><?= htmlspecialchars($module) ?>
                            </span>
                            <button type="button" onclick="toggleModule('<?= $module ?>')" style="background: none; border: none; color: var(--text-muted); font-size: 12px; cursor: pointer;">Toggle</button>
                        </div>
                        <div style="padding: 12px 18px;">
                            <?php foreach ($perms as $p): ?>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 8px 0; cursor: pointer; border-bottom: 1px solid rgba(45,63,90,0.3);">
                                <input type="checkbox" name="permissions[]" value="<?= $p['id'] ?>"
                                    <?= in_array($p['id'], $assigned_ids) ? 'checked' : '' ?>
                                    class="perm-checkbox module-<?= htmlspecialchars($module) ?>"
                                    style="width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 500; color: #cbd5e1;"><?= htmlspecialchars($p['name']) ?></div>
                                    <code style="font-size: 11px; color: #64748b;"><?= htmlspecialchars($p['slug']) ?></code>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-key"></i><p>Tidak ada permission tersedia.</p></div>
            <?php endif; ?>

            <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--dark-border); display: flex; gap: 12px; justify-content: flex-end;">
                <a href="<?= base_url('admin/roles') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-save"></i> Simpan Permission
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function selectAll() { document.querySelectorAll('.perm-checkbox').forEach(function(cb) { cb.checked = true; }); }
function deselectAll() { document.querySelectorAll('.perm-checkbox').forEach(function(cb) { cb.checked = false; }); }
function toggleModule(module) {
    var cbs = document.querySelectorAll('.module-' + module);
    var allChecked = Array.from(cbs).every(function(cb) { return cb.checked; });
    cbs.forEach(function(cb) { cb.checked = !allChecked; });
}
</script>
