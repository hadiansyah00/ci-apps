<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1>
        <i class="fas fa-truck me-2" style="color: var(--primary-light)"></i>
        <?= $is_edit ? 'Edit Armada' : 'Tambah Armada Baru' ?>
    </h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/vehicles') ?>">Armada</a>
        <span class="separator">/</span>
        <span><?= $is_edit ? 'Edit' : 'Tambah' ?></span>
    </div>
</div>

<div class="row" data-aos="fade-up">
    <div class="col-md-8 col-lg-6">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-truck-ramp-box" style="color: var(--primary-light)"></i>
                    Form Rincian Armada
                </div>
            </div>
            <div class="content-card-body">
                <form method="POST" action="<?= $is_edit ? base_url('admin/vehicles/update/' . $vehicle['id']) : base_url('admin/vehicles/store') ?>" autocomplete="off">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <!-- Plate Number -->
                    <div class="form-group">
                        <label class="form-label-custom" for="plate_number">Nomor Plat Kendaraan (Unique)</label>
                        <input type="text" name="plate_number" id="plate_number" 
                               class="form-control-custom" placeholder="Contoh: B 9012 TNP" 
                               value="<?= htmlspecialchars($vehicle['plate_number'] ?? '') ?>" required style="text-transform: uppercase;">
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label class="form-label-custom" for="type">Tipe / Model Kendaraan</label>
                        <input type="text" name="type" id="type" 
                               class="form-control-custom" placeholder="Contoh: Tronton Wingbox, Fuso Box, CDD Box" 
                               value="<?= htmlspecialchars($vehicle['type'] ?? '') ?>" required>
                    </div>

                    <!-- Capacity Row -->
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label class="form-label-custom" for="capacity_weight">Tonase Maksimal (Ton)</label>
                            <input type="number" step="0.01" name="capacity_weight" id="capacity_weight" 
                                   class="form-control-custom" placeholder="Contoh: 15.00" 
                                   value="<?= htmlspecialchars($vehicle['capacity_weight'] ?? '0.00') ?>" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="form-label-custom" for="capacity_volume">Kapasitas Volume (CBM)</label>
                            <input type="number" step="0.01" name="capacity_volume" id="capacity_volume" 
                                   class="form-control-custom" placeholder="Contoh: 45.0" 
                                   value="<?= htmlspecialchars($vehicle['capacity_volume'] ?? '0.00') ?>" required>
                        </div>
                    </div>

                    <!-- Dates Row -->
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label class="form-label-custom" for="kir_expiry">Masa Berlaku KIR</label>
                            <input type="date" name="kir_expiry" id="kir_expiry" 
                                   class="form-control-custom" 
                                   value="<?= htmlspecialchars($vehicle['kir_expiry'] ?? '') ?>" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="form-label-custom" for="tax_expiry">Masa Berlaku Pajak STNK</label>
                            <input type="date" name="tax_expiry" id="tax_expiry" 
                                   class="form-control-custom" 
                                   value="<?= htmlspecialchars($vehicle['tax_expiry'] ?? '') ?>" required>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label-custom" for="status">Status Awal Armada</label>
                        <select name="status" id="status" class="form-control-custom" style="background: #1e293b;">
                            <?php 
                            $status = $vehicle['status'] ?? 'available';
                            ?>
                            <option value="available" <?= $status === 'available' ? 'selected' : '' ?>>Available (Bebas / Siap Jalan)</option>
                            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active (Sedang Bertugas)</option>
                            <option value="maintenance" <?= $status === 'maintenance' ? 'selected' : '' ?>>Maintenance (Dalam Servis / Perbaikan)</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div style="margin-top: 28px; display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('admin/vehicles') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save me-1"></i> Simpan Armada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
