<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-clipboard-check me-2" style="color: var(--primary-light)"></i>Portal Checker Operasional</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span><span>Checker Dashboard</span>
    </div>
</div>

<!-- Stats Counter Grid -->
<div class="row g-4 mb-4" data-aos="fade-up">
    <!-- Stat 1: Pending Inspections -->
    <div class="col-md-4">
        <div class="content-card" style="border-left: 4px solid var(--warning); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(30,41,59,0.9) 100%);">
            <div class="content-card-body" style="padding: 24px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Antrean Cek Fisik</div>
                    <div style="font-size: 28px; font-weight: 800; color: #fff; margin-top: 4px;"><?= count($pending_inspections) ?> <span style="font-size: 14px; font-weight: 400; color: var(--text-muted);">Armada</span></div>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); display: flex; align-items: center; justify-content: center; font-size: 22px; color: var(--warning);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Pending Loadings -->
    <div class="col-md-4">
        <div class="content-card" style="border-left: 4px solid var(--primary-light); background: linear-gradient(135deg, rgba(129,140,248,0.05) 0%, rgba(30,41,59,0.9) 100%);">
            <div class="content-card-body" style="padding: 24px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Sedang Muat Kargo</div>
                    <div style="font-size: 28px; font-weight: 800; color: #fff; margin-top: 4px;"><?= count($pending_loadings) ?> <span style="font-size: 14px; font-weight: 400; color: var(--text-muted);">Truk</span></div>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(129,140,248,0.1); border: 1px solid rgba(129,140,248,0.25); display: flex; align-items: center; justify-content: center; font-size: 22px; color: var(--primary-light);">
                    <i class="fas fa-dolly"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Completed Today -->
    <div class="col-md-4">
        <div class="content-card" style="border-left: 4px solid var(--success); background: linear-gradient(135deg, rgba(34,197,94,0.05) 0%, rgba(30,41,59,0.9) 100%);">
            <div class="content-card-body" style="padding: 24px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Histori Cek Terakhir</div>
                    <div style="font-size: 28px; font-weight: 800; color: #fff; margin-top: 4px;"><?= count($recent_inspections) ?> <span style="font-size: 14px; font-weight: 400; color: var(--text-muted);">Pemeriksaan</span></div>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); display: flex; align-items: center; justify-content: center; font-size: 22px; color: var(--success);">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Queues Section -->
<div class="row g-4 mb-4" data-aos="fade-up" data-aos-delay="100">
    <!-- Queue 1: Pre-Trip Inspection Queue -->
    <div class="col-lg-6">
        <div class="content-card h-100">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-truck-ramp-box" style="color: var(--warning)"></i> Antrean Cek Kelayakan Fisik
                </div>
            </div>
            <div class="content-card-body" style="padding: 0;">
                <?php if (!empty($pending_inspections)): ?>
                <div class="table-responsive">
                    <table class="table-dark-custom" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Truk / Driver</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_inspections as $order): ?>
                            <tr>
                                <td style="font-weight: bold; color: #a5b4fc;">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div style="font-weight: 600; color: #fff;"><?= htmlspecialchars($order['plate_number']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($order['driver_name']) ?></div>
                                </td>
                                <td>
                                    <span class="badge-status <?= ($order['status'] === 'inspect_failed') ? 'badge-inactive' : 'badge-role' ?>" style="font-size: 10px; padding: 3px 8px;">
                                        <?= ($order['status'] === 'inspect_failed') ? 'Gagal Uji' : 'Awaiting Check' ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= base_url('admin/inspections/check/' . $order['id']) ?>" class="btn-primary-custom" style="padding: 6px 12px; font-size: 12px; background: linear-gradient(135deg, var(--warning), #d97706); display: inline-flex; justify-content: center; width: auto; min-width: unset;">
                                        <i class="fas fa-clipboard-check me-1"></i> Periksa
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div style="padding: 40px 24px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-circle-check text-success mb-2" style="font-size: 36px; opacity: 0.6;"></i>
                    <div style="font-size: 13.5px; font-weight: 600; color: #fff;">Antrean Cek Fisik Bersih</div>
                    <div style="font-size: 12px;">Tidak ada armada menunggu pemeriksaan fisik.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Queue 2: Cargo Loading Approval Queue -->
    <div class="col-lg-6">
        <div class="content-card h-100">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-box-open" style="color: var(--primary-light)"></i> Antrean Verifikasi Pemuatan Kargo
                </div>
            </div>
            <div class="content-card-body" style="padding: 0;">
                <?php if (!empty($pending_loadings)): ?>
                <div class="table-responsive">
                    <table class="table-dark-custom" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Truk / Muatan</th>
                                <th>Tujuan</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_loadings as $order): ?>
                            <tr>
                                <td style="font-weight: bold; color: #a5b4fc;">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div style="font-weight: 600; color: #fff;"><?= htmlspecialchars($order['plate_number']) ?></div>
                                    <div style="font-size: 11.5px; color: var(--text-secondary); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($order['cargo_description']) ?>">
                                        <?= htmlspecialchars($order['cargo_description']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500; color: #fff;"><?= htmlspecialchars($order['destination']) ?></div>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= base_url('admin/inspections/verify-loading/' . $order['id']) ?>" class="btn-primary-custom" style="padding: 6px 12px; font-size: 12px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: inline-flex; justify-content: center; width: auto; min-width: unset;">
                                        <i class="fas fa-box-open me-1"></i> Verifikasi Muat
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div style="padding: 40px 24px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-truck-loading mb-2" style="font-size: 36px; opacity: 0.4;"></i>
                    <div style="font-size: 13.5px; font-weight: 600; color: #fff;">Antrean Muat Kosong</div>
                    <div style="font-size: 12px;">Belum ada driver yang memicu status proses muat.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- History Log Section -->
<div class="row g-4 mb-4" data-aos="fade-up" data-aos-delay="200">
    <div class="col-12">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-history" style="color: var(--success)"></i> Riwayat Uji Kelayakan Terakhir Saya
                </div>
            </div>
            <div class="content-card-body" style="padding: 0;">
                <?php if (!empty($recent_inspections)): ?>
                <div class="table-responsive">
                    <table class="table-dark-custom" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Truk</th>
                                <th>Hasil Kelayakan</th>
                                <th>Catatan Pemeriksa</th>
                                <th>Waktu Pengujian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_inspections as $ins): ?>
                            <tr>
                                <td style="font-weight: bold; color: #a5b4fc;">#<?= str_pad($ins['order_id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($ins['plate_number']) ?></td>
                                <td>
                                    <span class="badge-status <?= ($ins['status'] === 'passed') ? 'badge-active' : 'badge-inactive' ?>" style="font-size: 10.5px; font-weight: 700;">
                                        <?= ($ins['status'] === 'passed') ? 'PASSED (Layak)' : 'FAILED (Gagal)' ?>
                                    </span>
                                </td>
                                <td style="font-size: 13px; font-style: italic; color: var(--text-secondary);">
                                    "<?= htmlspecialchars($ins['notes'] ?? 'Tanpa catatan.') ?>"
                                </td>
                                <td style="font-size: 12.5px; color: var(--text-muted);"><?= date('d-m-Y H:i', strtotime($ins['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div style="padding: 40px 24px; text-align: center; color: var(--text-muted);">
                    <i class="fas fa-history mb-2" style="font-size: 36px; opacity: 0.3;"></i>
                    <p style="margin: 0; font-size: 13px;">Belum ada riwayat pengecekan fisik terdaftar atas nama Anda.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
