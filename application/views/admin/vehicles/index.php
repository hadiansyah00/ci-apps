<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-truck me-2" style="color: var(--primary-light)"></i>Manajemen Armada</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span><span>Armada</span>
    </div>
</div>

<div class="content-card" data-aos="fade-up">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-truck" style="color: var(--primary-light)"></i>
            Daftar Unit Kendaraan
        </div>
        <?php if (can('fleets.manage')): ?>
        <a href="<?= base_url('admin/vehicles/create') ?>" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Tambah Armada
        </a>
        <?php endif; ?>
    </div>
    <div class="content-card-body" style="padding: 0;">
        <?php if (!empty($vehicles)): ?>
        <div class="table-responsive">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Plat</th>
                        <th>Tipe</th>
                        <th>Kapasitas Berat</th>
                        <th>Kapasitas Volume</th>
                        <th>Masa KIR</th>
                        <th>Masa Pajak</th>
                        <th>Status</th>
                        <?php if (can('fleets.manage')): ?>
                        <th style="text-align: right;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    foreach ($vehicles as $v): 
                        // KIR expiration warning calculation
                        $today = time();
                        $kir_exp_time = strtotime($v['kir_expiry']);
                        $kir_diff_days = round(($kir_exp_time - $today) / (60 * 60 * 24));

                        if ($kir_diff_days < 0) {
                            $kir_class = 'badge-inactive';
                            $kir_text = 'EXPIRED (' . $v['kir_expiry'] . ')';
                        } elseif ($kir_diff_days <= 30) {
                            $kir_class = 'badge-role'; // yellow/orange warning
                            $kir_text = $kir_diff_days . ' hari lagi (' . $v['kir_expiry'] . ')';
                        } else {
                            $kir_class = 'badge-active';
                            $kir_text = date('d-m-Y', $kir_exp_time);
                        }

                        // Tax expiration warning
                        $tax_exp_time = strtotime($v['tax_expiry']);
                        $tax_diff_days = round(($tax_exp_time - $today) / (60 * 60 * 24));
                        if ($tax_diff_days < 0) {
                            $tax_class = 'badge-inactive';
                            $tax_text = 'EXPIRED';
                        } elseif ($tax_diff_days <= 30) {
                            $tax_class = 'badge-role';
                            $tax_text = $tax_diff_days . ' hari lagi';
                        } else {
                            $tax_class = 'badge-active';
                            $tax_text = date('d-m-Y', $tax_exp_time);
                        }

                        // Vehicle status mapping
                        if ($v['status'] === 'available') {
                            $status_class = 'badge-active';
                            $status_label = 'Available';
                        } elseif ($v['status'] === 'active') {
                            $status_class = 'badge-role';
                            $status_label = 'On Trip';
                        } else {
                            $status_class = 'badge-inactive';
                            $status_label = 'Maintenance';
                        }
                    ?>
                    <tr>
                        <td style="color: var(--text-muted);"><?= $no++ ?></td>
                        <td style="font-weight: 700; color: #fff; font-size: 14px;"><?= htmlspecialchars($v['plate_number']) ?></td>
                        <td><?= htmlspecialchars($v['type']) ?></td>
                        <td><?= number_format($v['capacity_weight'], 1) ?> Ton</td>
                        <td><?= number_format($v['capacity_volume'], 1) ?> CBM</td>
                        <td>
                            <span class="badge-status <?= $kir_class ?>" style="font-size: 11px;">
                                <?= $kir_text ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-status <?= $tax_class ?>" style="font-size: 11px;">
                                <?= $tax_text ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-status <?= $status_class ?>" style="font-size: 11px; font-weight: 700;">
                                <?= $status_label ?>
                            </span>
                        </td>
                        <?php if (can('fleets.manage')): ?>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?= base_url('admin/vehicles/edit/' . $v['id']) ?>" class="btn-sm-icon btn-edit" title="Edit Armada">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="#" class="btn-sm-icon btn-delete" title="Hapus" onclick="return confirmDelete('<?= base_url('admin/vehicles/delete/' . $v['id']) ?>', '<?= htmlspecialchars($v['plate_number']) ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <p>Belum ada data armada unit kendaraan.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
