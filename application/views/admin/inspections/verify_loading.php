<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-box-open me-2" style="color: var(--primary-light)"></i>Verifikasi Pemuatan &amp; Segel</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/orders') ?>">Order Logistik</a>
        <span class="separator">/</span>
        <span>Verifikasi Pemuatan</span>
    </div>
</div>

<div class="row g-4 justify-content-center" data-aos="fade-up">
    <!-- Left: Order Summary details -->
    <div class="col-md-5">
        <div class="content-card h-100">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-info-circle text-primary-light"></i> Informasi Order &amp; Armada
                </div>
            </div>
            <div class="content-card-body" style="padding: 24px; font-size: 13.5px;">
                <div style="border-bottom: 1px solid var(--dark-border); padding-bottom: 12px; margin-bottom: 16px;">
                    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">NOMOR ORDER</div>
                    <div style="font-size: 20px; font-weight: 800; color: #a5b4fc;">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></div>
                </div>

                <div class="mb-3">
                    <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 2px;">CUSTOMER / PELANGGAN</div>
                    <div style="font-weight: 700; color: #fff; font-size: 15px;"><?= htmlspecialchars($order['customer_name']) ?></div>
                </div>

                <div class="mb-3">
                    <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 2px;">DESKRIPSI KARGO MUATAN</div>
                    <div style="font-weight: 600; color: #cbd5e1;"><?= htmlspecialchars($order['cargo_description']) ?></div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 1px;"><?= number_format($order['weight'], 1) ?> Ton / <?= number_format($order['volume'], 1) ?> CBM</div>
                </div>

                <div class="row g-3 mb-3" style="background: rgba(30,41,59,0.3); border: 1px solid var(--dark-border); border-radius: 8px; padding: 12px; margin: 0;">
                    <div class="col-6" style="padding: 0;">
                        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 2px;">ASAL</div>
                        <div style="font-weight: bold; color: #10b981;"><i class="fas fa-circle-dot me-1" style="font-size: 9px;"></i> <?= htmlspecialchars($order['origin']) ?></div>
                    </div>
                    <div class="col-6" style="padding: 0;">
                        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 2px;">TUJUAN</div>
                        <div style="font-weight: bold; color: #ef4444;"><i class="fas fa-location-dot me-1" style="font-size: 9px;"></i> <?= htmlspecialchars($order['destination']) ?></div>
                    </div>
                </div>

                <div class="mb-3" style="border-top: 1px solid var(--dark-border); padding-top: 16px;">
                    <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">ARMADA TRUK YANG DIGUNAKAN</div>
                    <div style="font-weight: 700; color: #fff;"><i class="fas fa-truck me-2" style="color: var(--primary-light)"></i><?= htmlspecialchars($order['plate_number']) ?></div>
                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($order['vehicle_type']) ?></div>
                </div>

                <div>
                    <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">PENGEMUDI (DRIVER)</div>
                    <div style="font-weight: bold; color: #fff;"><i class="fas fa-user-tie me-2" style="color: var(--secondary)"></i><?= htmlspecialchars($order['driver_name']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Loading Checklist form -->
    <div class="col-md-7">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-tasks text-success"></i> Pengisian Checklist &amp; Segel
                </div>
            </div>
            <div class="content-card-body" style="padding: 24px;">
                <?= form_open('admin/inspections/store-loading/' . $order['id'], ['id' => 'loading-form']) ?>

                <!-- Checklist -->
                <div style="margin-bottom: 24px;">
                    <label style="font-weight: 600; color: #fff; font-size: 14.5px; margin-bottom: 12px; display: block;">
                        Persyaratan Kelayakan Pemuatan Kargo:
                    </label>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <!-- Check 1 -->
                        <label class="d-flex align-items-start gap-3" style="cursor: pointer; padding: 12px 14px; background: rgba(30,41,59,0.3); border: 1px solid var(--dark-border); border-radius: 8px;">
                            <input type="checkbox" name="cargo_secured" value="1" required style="width: 18px; height: 18px; margin-top: 2px; accent-color: var(--success);">
                            <div>
                                <span style="font-weight: 600; color: #fff; font-size: 13.5px; display: block;">Tata Kargo Aman &amp; Terikat</span>
                                <span style="font-size: 12px; color: var(--text-muted);">Muatan ditata dengan seimbang, dipasang palet/pengganjal, dan diikat kencang (lashing).</span>
                            </div>
                        </label>

                        <!-- Check 2 -->
                        <label class="d-flex align-items-start gap-3" style="cursor: pointer; padding: 12px 14px; background: rgba(30,41,59,0.3); border: 1px solid var(--dark-border); border-radius: 8px;">
                            <input type="checkbox" name="door_locked" value="1" required style="width: 18px; height: 18px; margin-top: 2px; accent-color: var(--success);">
                            <div>
                                <span style="font-weight: 600; color: #fff; font-size: 13.5px; display: block;">Pintu Boks / Pengaman Terkunci</span>
                                <span style="font-size: 12px; color: var(--text-muted);">Pintu kontainer/boks ditutup rapat, engsel pengunci utama berfungsi normal.</span>
                            </div>
                        </label>

                        <!-- Check 3 -->
                        <label class="d-flex align-items-start gap-3" style="cursor: pointer; padding: 12px 14px; background: rgba(30,41,59,0.3); border: 1px solid var(--dark-border); border-radius: 8px;">
                            <input type="checkbox" name="weight_compliant" value="1" required style="width: 18px; height: 18px; margin-top: 2px; accent-color: var(--success);">
                            <div>
                                <span style="font-weight: 600; color: #fff; font-size: 13.5px; display: block;">Berat Muatan Sesuai Kapasitas</span>
                                <span style="font-size: 12px; color: var(--text-muted);">Muatan tidak melebihi batas muat aman truk (tonase armada: <?= number_format($order['capacity_weight'], 1) ?> Ton).</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Input: Seal Number -->
                <div class="form-group mb-4">
                    <label for="seal_number" style="font-weight: 600; color: #fff; margin-bottom: 6px; font-size: 14px;">
                        Nomor Segel Pengaman (Container/Box Seal) <span style="color: var(--danger)">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-key input-icon" style="top: 50%;"></i>
                        <input type="text" name="seal_number" id="seal_number" class="form-control-custom" required 
                               placeholder="Contoh: TNP-SEAL-0129A" style="padding-left: 42px;" 
                               value="<?= set_value('seal_number') ?>">
                    </div>
                    <span style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px; display: block;">Nomor segel fisik yang ditempelkan pada pintu boks/tali pengaman.</span>
                </div>

                <!-- Input: Notes -->
                <div class="form-group mb-4">
                    <label for="notes" style="font-weight: 600; color: #fff; margin-bottom: 6px; font-size: 14px;">Catatan Pemuatan (Opsional)</label>
                    <textarea name="notes" id="notes" class="form-control-custom" rows="3" 
                              placeholder="Masukkan catatan khusus pemuatan, misal: 'Ditata 3 baris, segel aman'..." style="resize: none;"></textarea>
                </div>

                <!-- Submit and Cancel buttons -->
                <div style="border-top: 1px solid var(--dark-border); padding-top: 20px; display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc; width: auto;">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary-custom" style="width: auto; background: linear-gradient(135deg, var(--success), #16a34a); box-shadow: 0 4px 15px rgba(34,197,94,0.3);">
                        <i class="fas fa-truck-fast me-1"></i> Verifikasi &amp; Berangkatkan Armada
                    </button>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
