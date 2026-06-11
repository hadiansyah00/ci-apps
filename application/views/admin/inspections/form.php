<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-clipboard-check me-2" style="color: var(--success)"></i>Uji Kelayakan Fisik Truk</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/orders') ?>">Order Logistik</a>
        <span class="separator">/</span>
        <span>Uji Kelayakan</span>
    </div>
</div>

<div class="row" data-aos="fade-up">
    <div class="col-md-9 col-lg-7">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-list-check" style="color: var(--success)"></i>
                    Pemeriksaan Armada [Plat Nomor: <strong style="color: var(--primary-light);"><?= htmlspecialchars($order['plate_number']) ?></strong>]
                </div>
            </div>
            <div class="content-card-body">
                <div style="background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.15); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: 13.5px;">
                    <div class="row">
                        <div class="col-6 mb-2"><strong>ID Order:</strong> #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></div>
                        <div class="col-6 mb-2"><strong>Tipe Truk:</strong> <?= htmlspecialchars($order['vehicle_type']) ?></div>
                        <div class="col-6"><strong>Driver Utama:</strong> <?= htmlspecialchars($order['driver_name']) ?></div>
                        <div class="col-6"><strong>Rute:</strong> <?= htmlspecialchars($order['origin']) ?> &rarr; <?= htmlspecialchars($order['destination']) ?></div>
                    </div>
                </div>

                <form method="POST" action="<?= base_url('admin/inspections/store/' . $order['id']) ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <div style="font-weight: 700; font-size: 14px; color: #fff; margin-bottom: 14px; border-bottom: 1px solid var(--dark-border); padding-bottom: 8px;">
                        Checklist Fisik Kendaraan:
                    </div>

                    <!-- Tires -->
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 10px; margin-bottom: 12px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 500; color: #cbd5e1;">
                            <i class="fas fa-circle-dot me-2 text-primary-light"></i> 1. Kondisi Ban &amp; Tekanan Udara
                        </span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="checkbox" name="tires_ok" value="1" checked class="login-check-custom"
                                   style="width: 20px; height: 20px; accent-color: var(--success); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 700; color: #4ade80;">PASSED</span>
                        </div>
                    </label>

                    <!-- Brakes -->
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 10px; margin-bottom: 12px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 500; color: #cbd5e1;">
                            <i class="fas fa-circle-dot me-2 text-primary-light"></i> 2. Fungsi Rem &amp; Handbrake Utama
                        </span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="checkbox" name="brakes_ok" value="1" checked
                                   style="width: 20px; height: 20px; accent-color: var(--success); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 700; color: #4ade80;">PASSED</span>
                        </div>
                    </label>

                    <!-- Lights -->
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 10px; margin-bottom: 12px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 500; color: #cbd5e1;">
                            <i class="fas fa-circle-dot me-2 text-primary-light"></i> 3. Lampu Utama, Lampu Rem &amp; Sign
                        </span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="checkbox" name="lights_ok" value="1" checked
                                   style="width: 20px; height: 20px; accent-color: var(--success); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 700; color: #4ade80;">PASSED</span>
                        </div>
                    </label>

                    <!-- Engine Oil & Water -->
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 10px; margin-bottom: 12px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 500; color: #cbd5e1;">
                            <i class="fas fa-circle-dot me-2 text-primary-light"></i> 4. Level Oli Mesin, Air Aki &amp; Radiator
                        </span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="checkbox" name="engine_oil_ok" value="1" checked
                                   style="width: 20px; height: 20px; accent-color: var(--success); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 700; color: #4ade80;">PASSED</span>
                        </div>
                    </label>

                    <!-- Documents -->
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 10px; margin-bottom: 20px; cursor: pointer;">
                        <span style="font-size: 14px; font-weight: 500; color: #cbd5e1;">
                            <i class="fas fa-circle-dot me-2 text-primary-light"></i> 5. Kelengkapan STNK &amp; Masa KIR Aktif
                        </span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="checkbox" name="documents_ok" value="1" checked
                                   style="width: 20px; height: 20px; accent-color: var(--success); cursor: pointer;">
                            <span style="font-size: 13px; font-weight: 700; color: #4ade80;">PASSED</span>
                        </div>
                    </label>

                    <!-- Notes -->
                    <div class="form-group">
                        <label class="form-label-custom" for="notes">Catatan Kondisi Fisik Armada</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control-custom" placeholder="Contoh: Tekanan ban kiri belakang agak kurang, oli rem baru di-topup."></textarea>
                    </div>

                    <!-- Warning Alert if Failed -->
                    <div style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.25); border-radius: 10px; padding: 12px 16px; font-size: 12.5px; color: #fde68a; margin-top: 18px; margin-bottom: 24px;">
                        <i class="fas fa-circle-info me-1"></i>
                        <strong>Catatan:</strong> Jika Anda menonaktifkan salah satu checklist di atas, armada truk akan langsung ditandai <strong>FAIL</strong> dan secara otomatis dialihkan ke status <strong>Maintenance (Servis)</strong>.
                    </div>

                    <!-- Form Actions -->
                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('admin/orders') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-circle-check me-1"></i> Simpan Hasil Uji Kelayakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
