<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.collapse-arrow {
    transition: transform 0.2s;
}
[aria-expanded="true"] .collapse-arrow {
    transform: rotate(180deg);
}
</style>

<div class="page-header text-center" data-aos="fade-down" style="margin-bottom: 20px;">
    <h1 style="font-size: 20px;"><i class="fas fa-truck-fast me-2" style="color: var(--primary-light)"></i>Portal Driver TNP</h1>
    <div style="font-size: 13px; color: var(--text-muted);">Akses Tugas Operasional Lapangan</div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Driver Profile Header -->
        <div style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 16px; padding: 20px; color: white; display: flex; align-items: center; gap: 14px; margin-bottom: 16px; box-shadow: 0 4px 15px rgba(99,102,241,0.35);" data-aos="fade-up">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold;">
                <?= strtoupper(substr($this->session->userdata('auth_user')['name'] ?? 'D', 0, 1)) ?>
            </div>
            <div>
                <div style="font-size: 11px; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Pengemudi Aktif</div>
                <div style="font-size: 16px; font-weight: 700;"><?= htmlspecialchars($this->session->userdata('auth_user')['name'] ?? 'Driver') ?></div>
            </div>
            <div style="margin-left: auto;">
                <span style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold;">
                    ONLINE
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-3 mb-4" data-aos="fade-up" data-aos-delay="50">
            <div class="col-4">
                <div style="background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: 12px; padding: 12px 8px; text-align: center;">
                    <div style="font-size: 18px; color: var(--primary-light); margin-bottom: 4px;"><i class="fas fa-route"></i></div>
                    <div style="font-size: 16px; font-weight: 800; color: #fff;"><?= $stats['total_trips'] ?></div>
                    <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Trip Selesai</div>
                </div>
            </div>
            <div class="col-4">
                <div style="background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: 12px; padding: 12px 8px; text-align: center;">
                    <div style="font-size: 18px; color: #4ade80; margin-bottom: 4px;"><i class="fas fa-wallet"></i></div>
                    <div style="font-size: 12px; font-weight: 800; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Rp <?= number_format($stats['total_income'], 0, ',', '.') ?>">
                        Rp <?= number_format($stats['total_income'] / 1000, 0, ',', '.') ?>K
                    </div>
                    <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Uang Jalan</div>
                </div>
            </div>
            <div class="col-4">
                <div style="background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: 12px; padding: 12px 8px; text-align: center;">
                    <div style="font-size: 18px; color: var(--warning); margin-bottom: 4px;"><i class="fas fa-truck"></i></div>
                    <div style="font-size: 12px; font-weight: 800; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($stats['assigned_vehicle'] ?: 'N/A') ?></div>
                    <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Plat Truk</div>
                </div>
            </div>
        </div>

        <!-- Warnings Logic for Vehicle Expiry -->
        <?php
        $warnings = [];
        if (!empty($task)) {
            if (!empty($task['kir_expiry'])) {
                $kir_days = ceil((strtotime($task['kir_expiry']) - time()) / (60 * 60 * 24));
                if ($kir_days <= 0) {
                    $warnings[] = [
                        'type' => 'danger',
                        'message' => 'Masa berlaku <strong>KIR Kendaraan telah HABIS</strong> (' . date('d-m-Y', strtotime($task['kir_expiry'])) . '). Segera hubungi operasional.'
                    ];
                } elseif ($kir_days <= 30) {
                    $warnings[] = [
                        'type' => 'warning',
                        'message' => 'Masa berlaku <strong>KIR Kendaraan akan habis dalam ' . $kir_days . ' hari</strong> (' . date('d-m-Y', strtotime($task['kir_expiry'])) . ').'
                    ];
                }
            }
            if (!empty($task['tax_expiry'])) {
                $tax_days = ceil((strtotime($task['tax_expiry']) - time()) / (60 * 60 * 24));
                if ($tax_days <= 0) {
                    $warnings[] = [
                        'type' => 'danger',
                        'message' => 'Masa berlaku <strong>Pajak STNK Kendaraan telah HABIS</strong> (' . date('d-m-Y', strtotime($task['tax_expiry'])) . '). Segera hubungi operasional.'
                    ];
                } elseif ($tax_days <= 30) {
                    $warnings[] = [
                        'type' => 'warning',
                        'message' => 'Masa berlaku <strong>Pajak STNK akan habis dalam ' . $tax_days . ' hari</strong> (' . date('d-m-Y', strtotime($task['tax_expiry'])) . ').'
                    ];
                }
            }
        }
        ?>

        <!-- Active Task Card -->
        <?php if (!empty($task)): ?>
        <?php 
        $status_label = $this->Order_model->get_status_label($task['status']);
        ?>
        <div class="content-card" style="border-color: rgba(99,102,241,0.2);" data-aos="fade-up" data-aos-delay="100">
            <div class="content-card-header" style="padding: 16px 20px;">
                <div class="content-card-title" style="font-size: 14.5px;">
                    <i class="fas fa-route" style="color: var(--warning)"></i> Tugas Pengiriman Aktif
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <?php if ($task['status'] === 'in_transit'): ?>
                        <span id="gps-status-badge" class="badge-status" style="font-size: 10px; font-weight: 700; background-color: rgba(34,197,94,0.15); border: 1px solid #22c55e; color: #4ade80; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fas fa-satellite-dish fa-spin"></i> GPS Lacak Aktif
                        </span>
                    <?php endif; ?>
                    <span class="badge-status <?= $status_label['class'] ?>" style="font-size: 11px; font-weight: 700; background-color: <?= $status_label['color'] ?>25; border: 1px solid <?= $status_label['color'] ?>; color: <?= $status_label['color'] ?>;">
                        <?= $status_label['label'] ?>
                    </span>
                </div>
            </div>
            <div class="content-card-body" style="padding: 20px;">
                <!-- Expiry Warnings -->
                <?php foreach ($warnings as $warn): ?>
                    <div class="alert-custom alert-<?= $warn['type'] === 'danger' ? 'danger' : 'warning' ?> mb-3 py-2 px-3 align-items-center" style="font-size: 12px; border-radius: 10px; display: flex; gap: 8px;">
                        <i class="fas fa-triangle-exclamation" style="font-size: 14px;"></i>
                        <span><?= $warn['message'] ?></span>
                    </div>
                <?php endforeach; ?>

                <!-- Main details -->
                <div style="border-bottom: 1px solid var(--dark-border); padding-bottom: 14px; margin-bottom: 14px;">
                    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">NOMOR ORDER</div>
                    <div style="font-size: 16px; font-weight: 800; color: #a5b4fc;">#<?= str_pad($task['id'], 5, '0', STR_PAD_LEFT) ?></div>
                </div>

                <div class="row g-3 style="font-size: 13.5px; margin-bottom: 20px;">
                    <div class="col-6">
                        <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">KOTA ASAL</div>
                        <div style="font-weight: 700; color: #10b981;"><i class="fas fa-circle-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($task['origin']) ?></div>
                    </div>
                    <div class="col-6">
                        <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">KOTA TUJUAN</div>
                        <div style="font-weight: 700; color: #ef4444;"><i class="fas fa-location-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($task['destination']) ?></div>
                    </div>
                    
                    <!-- Google Maps Directions Link -->
                    <div class="col-12 mt-2">
                        <a href="https://www.google.com/maps/dir/?api=1&origin=<?= urlencode($task['origin']) ?>&destination=<?= urlencode($task['destination']) ?>" 
                           target="_blank" 
                           class="btn-edit btn-sm-icon w-100 py-2" 
                           style="border-radius: 8px; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: auto;">
                            <i class="fas fa-map-location-dot"></i> Buka Rute di Google Maps
                        </a>
                    </div>

                    <div class="col-12" style="border-top: 1px solid rgba(45,63,90,0.3); padding-top: 10px; margin-top: 12px;">
                        <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">MUATAN &amp; TONASE</div>
                        <div style="color: #fff; font-weight: 600;"><?= htmlspecialchars($task['cargo_description']) ?></div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;"><?= number_format($task['weight'], 1) ?> Ton / <?= number_format($task['volume'], 1) ?> CBM</div>
                    </div>
                    
                    <div class="col-6" style="border-top: 1px solid rgba(45,63,90,0.3); padding-top: 10px;">
                        <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">NO. PLAT TRUK</div>
                        <div style="color: #fff; font-weight: 600;"><i class="fas fa-truck me-1"></i><?= htmlspecialchars($task['plate_number']) ?></div>
                        <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($task['vehicle_type']) ?></div>
                    </div>
                    
                    <div class="col-6" style="border-top: 1px solid rgba(45,63,90,0.3); padding-top: 10px;">
                        <div style="color: var(--text-muted); font-size: 11.5px; margin-bottom: 3px;">UANG JALAN KAS</div>
                        <div style="color: #4ade80; font-weight: 700; font-size: 14px;">Rp <?= number_format($task['uang_jalan'], 0, ',', '.') ?></div>
                    </div>
                </div>

                <!-- Checker Pre-Trip Inspection Summary -->
                <?php if (!empty($inspection)): ?>
                <div style="margin-top: 20px; border-top: 1px solid var(--dark-border); padding-top: 16px;">
                    <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">
                        <i class="fas fa-clipboard-check text-primary me-1"></i> Hasil Cek Kelayakan Truk (Checker)
                    </div>
                    <div class="row g-2 mb-3" style="font-size: 11.5px;">
                        <div class="col-4">
                            <div style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 8px; padding: 6px 10px; text-align: center;">
                                <div style="color: var(--text-muted); font-size: 9px; margin-bottom: 2px; text-transform: uppercase;">Ban</div>
                                <?php if ($inspection['tires_ok']): ?>
                                    <span class="text-success" style="font-weight: 700;"><i class="fas fa-circle-check"></i> AMAN</span>
                                <?php else: ?>
                                    <span class="text-danger" style="font-weight: 700;"><i class="fas fa-circle-xmark"></i> NOT OK</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 8px; padding: 6px 10px; text-align: center;">
                                <div style="color: var(--text-muted); font-size: 9px; margin-bottom: 2px; text-transform: uppercase;">Rem</div>
                                <?php if ($inspection['brakes_ok']): ?>
                                    <span class="text-success" style="font-weight: 700;"><i class="fas fa-circle-check"></i> AMAN</span>
                                <?php else: ?>
                                    <span class="text-danger" style="font-weight: 700;"><i class="fas fa-circle-xmark"></i> NOT OK</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 8px; padding: 6px 10px; text-align: center;">
                                <div style="color: var(--text-muted); font-size: 9px; margin-bottom: 2px; text-transform: uppercase;">Lampu</div>
                                <?php if ($inspection['lights_ok']): ?>
                                    <span class="text-success" style="font-weight: 700;"><i class="fas fa-circle-check"></i> AMAN</span>
                                <?php else: ?>
                                    <span class="text-danger" style="font-weight: 700;"><i class="fas fa-circle-xmark"></i> NOT OK</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 8px; padding: 6px 10px; text-align: center;">
                                <div style="color: var(--text-muted); font-size: 9px; margin-bottom: 2px; text-transform: uppercase;">Oli Mesin</div>
                                <?php if ($inspection['engine_oil_ok']): ?>
                                    <span class="text-success" style="font-weight: 700;"><i class="fas fa-circle-check"></i> AMAN</span>
                                <?php else: ?>
                                    <span class="text-danger" style="font-weight: 700;"><i class="fas fa-circle-xmark"></i> NOT OK</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 8px; padding: 6px 10px; text-align: center;">
                                <div style="color: var(--text-muted); font-size: 9px; margin-bottom: 2px; text-transform: uppercase;">Dokumen Truk</div>
                                <?php if ($inspection['documents_ok']): ?>
                                    <span class="text-success" style="font-weight: 700;"><i class="fas fa-circle-check"></i> LENGKAP</span>
                                <?php else: ?>
                                    <span class="text-danger" style="font-weight: 700;"><i class="fas fa-circle-xmark"></i> KURANG</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: rgba(99,102,241,0.05); border: 1px solid rgba(99,102,241,0.15); border-radius: 10px; padding: 10px 12px;">
                        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                            <span style="color: var(--text-muted);">DIPERIKSA OLEH</span>
                            <span style="font-weight: 700; color: var(--primary-light);"><i class="fas fa-user-check me-1"></i> <?= htmlspecialchars($inspection['checker_name'] ?? 'Checker') ?></span>
                        </div>
                        <div style="color: var(--text-muted); font-size: 10.5px; margin-bottom: 2px; font-weight: 600;">CATATAN PEMERIKSAAN:</div>
                        <div style="color: #cbd5e1; font-style: italic; line-height: 1.4; font-size: 12px;">
                            <?= !empty($inspection['notes']) ? htmlspecialchars($inspection['notes']) : 'Truk dinyatakan layak jalan. Tidak ada catatan khusus.' ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Cargo Seal & Verified Loading Details -->
                <?php if (in_array($task['status'], ['in_transit', 'arrived', 'pod_submitted', 'completed']) && !empty($task['loading_verified_by'])): ?>
                <div style="margin-top: 20px; border-top: 1px solid var(--dark-border); padding-top: 16px;">
                    <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">
                        <i class="fas fa-lock text-success me-1"></i> Informasi Segel &amp; Pemuatan Kargo
                    </div>
                    <div style="background: rgba(34,197,94,0.05); border: 1px solid rgba(34,197,94,0.15); border-radius: 10px; padding: 12px 14px; font-size: 12.5px;">
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div style="color: var(--text-muted); font-size: 10.5px;">NOMOR SEGEL</div>
                                <div style="font-weight: 800; color: #4ade80; font-size: 13.5px;"><i class="fas fa-shield-halved me-1"></i> <?= htmlspecialchars($task['seal_number'] ?? 'N/A') ?></div>
                            </div>
                            <div class="col-6">
                                <div style="color: var(--text-muted); font-size: 10.5px;">VERIFIKATOR MUAT</div>
                                <div style="font-weight: 600; color: #cbd5e1;"><i class="fas fa-user-shield me-1"></i> <?= htmlspecialchars($task['loading_verifier_name'] ?? 'Checker') ?></div>
                            </div>
                        </div>
                        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 2px;">CATATAN PEMUATAN:</div>
                        <div style="color: #cbd5e1; font-style: italic; line-height: 1.4;">
                            <?= !empty($task['loading_notes']) ? htmlspecialchars($task['loading_notes']) : 'Kargo dimuat dengan lengkap.' ?>
                        </div>
                        <div style="color: var(--text-muted); font-size: 10px; margin-top: 6px; text-align: right;">
                            Waktu Rilis: <?= date('d-m-Y H:i', strtotime($task['loading_verified_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Driver Actions -->
                <div style="border-top: 1px solid var(--dark-border); padding-top: 20px; text-align: center; margin-top: 20px;">
                    <?php if (in_array($task['status'], ['allocated', 'inspect_failed'])): ?>
                        <!-- Action: Pre-Trip checklist warning -->
                        <div style="font-size: 13px; color: #fde68a; text-align: left; padding: 12px 16px; background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-radius: 12px; line-height: 1.4;">
                            <div style="font-weight: 700; margin-bottom: 4px;"><i class="fas fa-circle-info me-1"></i> Menunggu Cek Fisik</div>
                            Truk wajib diperiksa kelayakan fisiknya terlebih dahulu oleh **Petugas Checker** di pool sebelum kargo mulai dimuat.
                        </div>

                    <?php elseif ($task['status'] === 'ready'): ?>
                        <!-- Action: Start Loading -->
                        <a href="<?= base_url('driver/tasks/update-status/' . $task['id'] . '/loading') ?>" 
                           onclick="return confirmAction('Mulai Muat Kargo?', 'Pastikan armada sudah sandar di dermaga muat dan kargo siap dimasukkan.', '#8b5cf6', 'Muat')"
                           class="btn-primary-custom" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); box-shadow: 0 4px 15px rgba(99,102,241,0.35);">
                            <i class="fas fa-dolly me-2"></i> Mulai Muat Kargo (Start Loading)
                        </a>

                    <?php elseif ($task['status'] === 'loading'): ?>
                        <!-- Action: Waiting for Loading Verification & Seal from Checker -->
                        <div style="font-size: 13px; color: #a5b4fc; text-align: left; padding: 12px 16px; background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2); border-radius: 12px; line-height: 1.4;">
                            <div style="font-weight: 700; margin-bottom: 4px;"><i class="fas fa-spinner fa-spin me-1"></i> Proses Pemuatan Kargo</div>
                            Kargo sedang dimuat ke dalam armada. Setelah selesai, harap hubungi **Petugas Checker** untuk verifikasi pemuatan, segel kontainer, dan rilis jalan keberangkatan.
                        </div>

                    <?php elseif ($task['status'] === 'in_transit'): ?>
                        <!-- Action: Arrive (Tiba) -->
                        <a href="<?= base_url('driver/tasks/update-status/' . $task['id'] . '/arrived') ?>" 
                           onclick="return confirmAction('Tiba di Tujuan?', 'Pastikan armada sudah sampai di lokasi unloading dan kargo siap dibongkar.', '#0ea5e9', 'Tiba')"
                           class="btn-primary-custom" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; background: linear-gradient(135deg, var(--secondary), #0284c7); box-shadow: 0 4px 15px rgba(14,165,233,0.3);">
                            <i class="fas fa-location-dot me-2"></i> Tiba di Tujuan (Arrive)
                        </a>

                    <?php elseif ($task['status'] === 'arrived'): ?>
                        <!-- Action: Upload POD -->
                        <a href="<?= base_url('driver/tasks/pod/' . $task['id']) ?>" 
                           class="btn-primary-custom" style="width: 100%; justify-content: center; padding: 14px; font-size: 15px; background: linear-gradient(135deg, var(--warning), #d97706); box-shadow: 0 4px 15px rgba(245,158,11,0.3);">
                            <i class="fas fa-file-arrow-up me-2"></i> Unggah Bukti Penerimaan (POD)
                        </a>

                    <?php elseif ($task['status'] === 'pod_submitted'): ?>
                        <!-- Waiting for admin verification -->
                        <div style="padding: 14px; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.25); border-radius: 12px; color: #a5b4fc; font-weight: 500;">
                            <i class="fas fa-spinner fa-spin me-2"></i> Foto POD sudah dikirim. Menunggu verifikasi dari admin operasional di kantor.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- No Active Task State -->
        <div class="content-card text-center" style="padding: 40px 24px;" data-aos="fade-up" data-aos-delay="100">
            <div style="font-size: 48px; color: var(--text-muted); opacity: 0.3; margin-bottom: 16px;">
                <i class="fas fa-route"></i>
            </div>
            <h3 style="color: #fff; font-size: 16px; font-weight: 700; margin-bottom: 4px;">Tidak Ada Tugas Aktif</h3>
            <p style="color: var(--text-muted); font-size: 13px;">Saat ini Anda tidak memiliki penugasan rute jalan. Harap standby menunggu dispatcher kantor.</p>
        </div>
        <?php endif; ?>

        <!-- Shipment History Card (Collapsible) -->
        <div class="content-card mt-4 mb-4" data-aos="fade-up" data-aos-delay="150">
            <div class="content-card-header" style="padding: 16px 20px; cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#historyCollapse" aria-expanded="false" aria-controls="historyCollapse">
                <div class="content-card-title" style="font-size: 14px;">
                    <i class="fas fa-history" style="color: var(--primary-light)"></i> Riwayat Pengiriman Saya
                </div>
                <i class="fas fa-chevron-down text-muted collapse-arrow"></i>
            </div>
            <div id="historyCollapse" class="collapse">
                <div class="content-card-body" style="padding: 0;">
                    <?php if (!empty($history)): ?>
                    <div class="table-responsive">
                        <table class="table-dark-custom mb-0" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th style="padding: 10px 14px;">No. Order</th>
                                    <th style="padding: 10px 14px;">Rute (Asal &rarr; Tujuan)</th>
                                    <th style="padding: 10px 14px;">Uang Jalan</th>
                                    <th style="padding: 10px 14px;">Tanggal Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $row): ?>
                                <tr>
                                    <td style="padding: 12px 14px; font-weight: 700; color: #a5b4fc;">#<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td style="padding: 12px 14px;">
                                        <div style="font-weight: 600; color: #cbd5e1;"><?= htmlspecialchars($row['origin']) ?> &rarr; <?= htmlspecialchars($row['destination']) ?></div>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($row['plate_number']) ?></div>
                                    </td>
                                    <td style="padding: 12px 14px; color: #4ade80; font-weight: 600;">Rp <?= number_format($row['uang_jalan'], 0, ',', '.') ?></td>
                                    <td style="padding: 12px 14px; color: var(--text-muted); font-size: 11.5px;"><?= date('d-m-Y H:i', strtotime($row['updated_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4 text-muted" style="font-size: 13px; padding: 20px;">
                        <i class="fas fa-folder-open mb-2" style="font-size: 24px; opacity: 0.3;"></i>
                        <div>Belum ada riwayat pengiriman selesai.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Driver action confirmation alert helper
 */
function confirmAction(title, text, confirmColor, buttonText) {
    event.preventDefault();
    var href = event.currentTarget.getAttribute('href');
    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#475569',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Ya, ' + buttonText,
        cancelButtonText: 'Batal',
        background: '#1e293b',
        color: '#e2e8f0',
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
    return false;
}
</script>

<?php if (!empty($task) && $task['status'] === 'in_transit'): ?>
<script>
$(document).ready(function() {
    let lastSentTime = 0;
    const sendIntervalMs = 30000; // 30 seconds
    const orderId = <?= $task['id'] ?>;
    const csrfName = '<?= $this->security->get_csrf_token_name() ?>';
    const csrfHash = '<?= $this->security->get_csrf_hash() ?>';
    let wakeLock = null;

    // --- Screen Wake Lock API ---
    async function requestWakeLock() {
        if ('wakeLock' in navigator) {
            try {
                wakeLock = await navigator.wakeLock.request('screen');
                console.log('Screen Wake Lock is active.');
                
                // Event listener for release
                wakeLock.addEventListener('release', () => {
                    console.log('Screen Wake Lock was released.');
                });
            } catch (err) {
                console.error(`Wake Lock request failed: ${err.name}, ${err.message}`);
            }
        } else {
            console.log('Screen Wake Lock is not supported by this browser.');
        }
    }

    // Request lock when page loads
    requestWakeLock();

    // Re-request lock if page becomes visible again
    document.addEventListener('visibilitychange', async () => {
        if (wakeLock !== null && document.visibilityState === 'visible') {
            await requestWakeLock();
        }
    });

    function sendLocation(lat, lng) {
        const now = Date.now();
        if (now - lastSentTime < sendIntervalMs) {
            return; // Throttle
        }
        lastSentTime = now;

        const postData = {
            latitude: lat,
            longitude: lng
        };
        postData[csrfName] = csrfHash;

        $.ajax({
            url: '<?= base_url('driver/tasks/log-location/') ?>' + orderId,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                console.log('Location logged:', response);
                $('#gps-status-badge').html('<i class="fas fa-satellite-dish fa-spin"></i> GPS + Screen Lock Aktif');
                $('#gps-status-badge').css({
                    'background-color': 'rgba(34,197,94,0.15)',
                    'border': '1px solid #22c55e',
                    'color': '#4ade80'
                });
            },
            error: function(xhr) {
                console.error('Failed to log location:', xhr.responseJSON || xhr.responseText);
                $('#gps-status-badge').html('<i class="fas fa-triangle-exclamation"></i> GPS Gagal Sinkron');
                $('#gps-status-badge').css({
                    'background-color': 'rgba(239,68,68,0.15)',
                    'border': '1px solid #ef4444',
                    'color': '#f87171'
                });
            }
        });
    }

    if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                sendLocation(lat, lng);
            },
            function(error) {
                console.error("GPS Watch Position Error:", error);
                let errMsg = "GPS Mati";
                if (error.code === error.PERMISSION_DENIED) {
                    errMsg = "Izin GPS Ditolak";
                }
                $('#gps-status-badge').html('<i class="fas fa-triangle-exclamation"></i> ' + errMsg);
                $('#gps-status-badge').css({
                    'background-color': 'rgba(239,68,68,0.15)',
                    'border': '1px solid #ef4444',
                    'color': '#f87171'
                });
            },
            {
                enableHighAccuracy: true,
                maximumAge: 10000,
                timeout: 20000
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
        $('#gps-status-badge').html('<i class="fas fa-triangle-exclamation"></i> Browser No GPS');
    }
});
</script>
<?php endif; ?>
