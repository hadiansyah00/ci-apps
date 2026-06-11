<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-truck-ramp-box me-2" style="color: var(--primary-light)"></i>Alokasi Armada &amp; Driver</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/orders') ?>">Order Logistik</a>
        <span class="separator">/</span>
        <span>Assign Armada</span>
    </div>
</div>

<div class="row g-4" data-aos="fade-up">
    <!-- Left: Order Details Summary -->
    <div class="col-md-5 col-lg-4">
        <div class="content-card" style="height: 100%;">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-file-lines" style="color: var(--warning)"></i> Rincian Pengiriman
                </div>
            </div>
            <div class="content-card-body">
                <table style="width: 100%; font-size: 13.5px; border-collapse: separate; border-spacing: 0 12px;">
                    <tr>
                        <td style="color: var(--text-muted); width: 100px;">ID Order</td>
                        <td style="font-weight: 700; color: #a5b4fc;">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Pelanggan</td>
                        <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($order['customer_name']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted); vertical-align: top;">Muatan</td>
                        <td style="color: #cbd5e1;"><?= nl2br(htmlspecialchars($order['cargo_description'])) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Tonase / Vol</td>
                        <td><?= number_format($order['weight'], 1) ?> Ton / <?= number_format($order['volume'], 1) ?> CBM</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Rute Asal</td>
                        <td style="font-weight: 600; color: #10b981;"><i class="fas fa-circle-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($order['origin']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Rute Tujuan</td>
                        <td style="font-weight: 600; color: #ef4444;"><i class="fas fa-location-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($order['destination']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Estimasi ETA</td>
                        <td style="color: #cbd5e1;"><?= date('d-m-Y H:i', strtotime($order['eta'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Assignment Form -->
    <div class="col-md-7 col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-users-gear" style="color: var(--primary-light)"></i> Form Penugasan
                </div>
            </div>
            <div class="content-card-body">
                <form method="POST" action="<?= base_url('admin/orders/assign/' . $order['id']) ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <!-- Driver Selection -->
                    <div class="form-group">
                        <label class="form-label-custom" for="driver_id">Pilih Driver Tersedia (*Driver Roster)</label>
                        <?php if (!empty($drivers)): ?>
                        <select name="driver_id" id="driver_id" class="form-control-custom" style="background: #1e293b;" required>
                            <option value="">-- Pilih Driver yang Sedang Standby --</option>
                            <?php foreach ($drivers as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?> (username: <?= htmlspecialchars($d['username']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <?php else: ?>
                        <div class="alert-custom alert-danger" style="margin-bottom: 0; padding: 10px 14px; font-size: 13px;">
                            <i class="fas fa-triangle-exclamation"></i>
                            <div>Tidak ada driver yang sedang standby saat ini. Seluruh driver sedang bertugas di lapangan.</div>
                        </div>
                        <select name="driver_id" id="driver_id" class="d-none" required></select> <!-- hidden but HTML valid -->
                        <?php endif; ?>
                    </div>

                    <!-- Vehicle Selection -->
                    <div class="form-group">
                        <label class="form-label-custom" for="vehicle_id">Pilih Kendaraan Truk Tersedia (*Fleet Roster)</label>
                        <?php if (!empty($vehicles)): ?>
                        <select name="vehicle_id" id="vehicle_id" class="form-control-custom" style="background: #1e293b;" required>
                            <option value="">-- Pilih Armada yang Berstatus Available --</option>
                            <?php foreach ($vehicles as $v): ?>
                            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['plate_number']) ?> &mdash; <?= htmlspecialchars($v['type']) ?> (KIR exp: <?= $v['kir_expiry'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <?php else: ?>
                        <div class="alert-custom alert-danger" style="margin-bottom: 0; padding: 10px 14px; font-size: 13px;">
                            <i class="fas fa-triangle-exclamation"></i>
                            <div>Tidak ada truk berstatus Available saat ini di pool.</div>
                        </div>
                        <select name="vehicle_id" id="vehicle_id" class="d-none" required></select>
                        <?php endif; ?>
                    </div>

                    <!-- Uang Jalan -->
                    <div class="form-group">
                        <label class="form-label-custom" for="uang_jalan">Nominal Uang Jalan Driver (Rupiah)</label>
                        <div class="input-wrapper">
                            <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px; font-weight: 700; z-index: 2;">Rp</span>
                            <input type="number" name="uang_jalan" id="uang_jalan" class="form-control-custom" placeholder="Contoh: 1500000" style="padding-left: 42px;" required value="0">
                        </div>
                        <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Masukkan nominal kas awal untuk BBM, Tol, dan parkir driver.</span>
                    </div>

                    <!-- Form Actions -->
                    <div style="margin-top: 32px; display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('admin/orders') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom" 
                                <?= (empty($drivers) || empty($vehicles)) ? 'disabled' : '' ?>>
                            <i class="fas fa-truck-fast me-1"></i> Jalankan Alokasi Armada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
