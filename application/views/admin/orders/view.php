<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =============================================
       TRACKING TIMELINE STYLES
    ============================================= */
    .timeline-card {
        background: var(--dark-surface);
        border: 1px solid var(--dark-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .tracking-timeline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 20px 0 10px 0;
        padding: 0 10px;
    }
    
    .tracking-timeline::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--dark-border);
        z-index: 1;
    }
    
    .tracking-line-progress {
        position: absolute;
        top: 22px;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        z-index: 2;
        transition: width 0.4s ease;
    }
    
    .tracking-step {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .tracking-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #0f172a;
        border: 3px solid var(--dark-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: var(--text-muted);
        transition: all 0.3s ease;
    }
    
    .tracking-step.active .tracking-icon-circle {
        background: var(--primary);
        border-color: var(--primary-light);
        color: white;
        box-shadow: 0 0 15px rgba(99,102,241,0.5);
    }
    
    .tracking-step.completed .tracking-icon-circle {
        background: var(--success);
        border-color: #4ade80;
        color: white;
        box-shadow: 0 0 12px rgba(34,197,94,0.4);
    }
    
    .tracking-step.failed .tracking-icon-circle {
        background: var(--danger);
        border-color: #f87171;
        color: white;
        box-shadow: 0 0 12px rgba(239,68,68,0.4);
    }
    
    .tracking-label {
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        margin-top: 8px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .tracking-step.active .tracking-label {
        color: var(--primary-light);
    }
    
    .tracking-step.completed .tracking-label {
        color: #fff;
    }
    
    .tracking-step.failed .tracking-label {
        color: #f87171;
    }

    @media (max-width: 767px) {
        .tracking-timeline {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            padding-left: 20px;
        }
        .tracking-timeline::before {
            left: 40px;
            top: 0;
            bottom: 0;
            width: 4px;
            height: 100%;
        }
        .tracking-line-progress {
            left: 40px;
            top: 0;
            width: 4px !important;
            height: var(--progress-mobile-height, 0%);
        }
        .tracking-step {
            flex-direction: row;
            gap: 16px;
        }
        .tracking-label {
            margin-top: 0;
            text-align: left;
        }
    }
</style>

<div class="page-header" data-aos="fade-down">
    <h1>
        <i class="fas fa-file-invoice me-2" style="color: var(--warning)"></i>
        Detail Order #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
    </h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span>
        <a href="<?= base_url('admin/orders') ?>">Order Logistik</a>
        <span class="separator">/</span>
        <span>Detail Tracking</span>
    </div>
</div>

<!-- Shipment Tracking Timeline Status -->
<?php
$status = $order['status'];
$progress = 0;
$progress_mobile = "0%";

$step1 = 'completed'; // Pending always complete once created
$step2 = 'pending';
$step3 = 'pending';
$step4 = 'pending';
$step5 = 'pending';
$step6 = 'pending';
$step7 = 'pending';

if ($status === 'allocated') {
    $step2 = 'active';
    $progress = 16;
    $progress_mobile = "16%";
} elseif ($status === 'inspect_failed') {
    $step2 = 'completed';
    $step3 = 'failed';
    $progress = 33;
    $progress_mobile = "33%";
} elseif ($status === 'ready') {
    $step2 = 'completed';
    $step3 = 'completed';
    $step4 = 'active';
    $progress = 50;
    $progress_mobile = "50%";
} elseif ($status === 'loading') {
    $step2 = 'completed';
    $step3 = 'completed';
    $step4 = 'completed';
    $step5 = 'active';
    $progress = 66;
    $progress_mobile = "66%";
} elseif ($status === 'in_transit') {
    $step2 = 'completed';
    $step3 = 'completed';
    $step4 = 'completed';
    $step5 = 'completed';
    $step6 = 'active';
    $progress = 83;
    $progress_mobile = "83%";
} elseif ($status === 'arrived') {
    $step2 = 'completed';
    $step3 = 'completed';
    $step4 = 'completed';
    $step5 = 'completed';
    $step6 = 'completed';
    $step7 = 'active';
    $progress = 90;
    $progress_mobile = "90%";
} elseif ($status === 'pod_submitted') {
    $step2 = 'completed';
    $step3 = 'completed';
    $step4 = 'completed';
    $step5 = 'completed';
    $step6 = 'completed';
    $step7 = 'active'; // Awaiting verification
    $progress = 95;
    $progress_mobile = "95%";
} elseif ($status === 'completed') {
    $step2 = 'completed';
    $step3 = 'completed';
    $step4 = 'completed';
    $step5 = 'completed';
    $step6 = 'completed';
    $step7 = 'completed';
    $progress = 100;
    $progress_mobile = "100%";
}
?>
<div class="timeline-card" data-aos="fade-up" style="--progress-mobile-height: <?= $progress_mobile ?>;">
    <div style="font-size: 13.5px; font-weight: 700; color: #fff; margin-bottom: 20px;">
        <i class="fas fa-route text-primary me-2"></i>Status Perjalanan Pengiriman
    </div>
    
    <div class="tracking-timeline">
        <!-- Progress Line (Desktop only, responsive override in style) -->
        <div class="tracking-line-progress d-none d-md-block" style="width: <?= $progress ?>%;"></div>
        <div class="tracking-line-progress d-md-none" style="height: <?= $progress_mobile ?>;"></div>
        
        <!-- Step 1: Order Created -->
        <div class="tracking-step <?= $step1 ?>">
            <div class="tracking-icon-circle">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="tracking-label">Order Dibuat</div>
        </div>
        
        <!-- Step 2: Fleet Allocated -->
        <div class="tracking-step <?= $step2 ?>">
            <div class="tracking-icon-circle">
                <i class="fas fa-truck-ramp-box"></i>
            </div>
            <div class="tracking-label">Armada Ditunjuk</div>
        </div>
        
        <!-- Step 3: Pre-Trip Inspection (Cek Fisik) -->
        <div class="tracking-step <?= $step3 ?>">
            <div class="tracking-icon-circle">
                <?php if ($status === 'inspect_failed'): ?>
                    <i class="fas fa-times-circle"></i>
                <?php else: ?>
                    <i class="fas fa-clipboard-check"></i>
                <?php endif; ?>
            </div>
            <div class="tracking-label">Cek Kelayakan</div>
        </div>
        
        <!-- Step 4: Ready -->
        <div class="tracking-step <?= $step4 ?>">
            <div class="tracking-icon-circle">
                <i class="fas fa-play"></i>
            </div>
            <div class="tracking-label">Siap Jalan</div>
        </div>

        <!-- Step 5: Loading -->
        <div class="tracking-step <?= $step5 ?>">
            <div class="tracking-icon-circle">
                <i class="fas fa-dolly"></i>
            </div>
            <div class="tracking-label">Proses Muat</div>
        </div>
        
        <!-- Step 6: In Transit -->
        <div class="tracking-step <?= $step6 ?>">
            <div class="tracking-icon-circle">
                <i class="fas fa-truck-fast"></i>
            </div>
            <div class="tracking-label">Dalam Rute</div>
        </div>
        
        <!-- Step 7: Arrived & Completed -->
        <div class="tracking-step <?= $step7 ?>">
            <div class="tracking-icon-circle">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="tracking-label">Selesai (POD)</div>
        </div>
    </div>
</div>

<div class="row g-4" data-aos="fade-up">
    <!-- Left Column: Core Order Information & Map -->
    <div class="col-lg-6">
        <!-- Card 1: Order Information -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-circle-info" style="color: var(--warning)"></i>
                    Informasi Transaksi Pengiriman
                </div>
                <?php 
                $CI =& get_instance();
                $CI->load->model('Order_model');
                $status_info = $CI->Order_model->get_status_label($order['status']);
                ?>
                <span class="badge-status <?= $status_info['class'] ?>" style="font-size: 12px; font-weight: 700;">
                    <?= $status_info['label'] ?>
                </span>
            </div>
            <div class="content-card-body">
                <table style="width: 100%; font-size: 14px; border-collapse: separate; border-spacing: 0 12px;">
                    <tr>
                        <td style="color: var(--text-muted); width: 140px;">Nomor Order</td>
                        <td style="font-weight: 700; color: #a5b4fc;">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Nama Pelanggan</td>
                        <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($order['customer_name']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted); vertical-align: top;">Rincian Kargo</td>
                        <td style="color: #cbd5e1;"><?= nl2br(htmlspecialchars($order['cargo_description'])) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Tonase / Volume</td>
                        <td><?= number_format($order['weight'], 1) ?> Ton / <?= number_format($order['volume'], 1) ?> CBM</td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Kota Asal</td>
                        <td style="font-weight: 600; color: #10b981;"><i class="fas fa-circle-dot me-1" style="font-size: 11px;"></i> <?= htmlspecialchars($order['origin']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Kota Tujuan</td>
                        <td style="font-weight: 600; color: #ef4444;"><i class="fas fa-location-dot me-1" style="font-size: 11px;"></i> <?= htmlspecialchars($order['destination']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Estimasi Tiba (ETA)</td>
                        <td style="color: #cbd5e1;"><?= date('d-m-Y H:i', strtotime($order['eta'])) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Uang Jalan Driver</td>
                        <td style="font-weight: 700; color: #4ade80;">Rp <?= number_format($order['uang_jalan'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Driver Bertugas</td>
                        <td style="color: #fff; font-weight: 600;"><?= htmlspecialchars($order['driver_name'] ?? 'Belum Ditugaskan') ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Armada Truk</td>
                        <td style="color: #fff; font-weight: 600;">
                            <?= $order['plate_number'] ? htmlspecialchars($order['plate_number']) . ' &mdash; ' . htmlspecialchars($order['vehicle_type']) : 'Belum Ditugaskan' ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Card 2: Live Map Tracking Simulator -->
        <div class="content-card mb-4">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-map-location-dot" style="color: var(--primary-light)"></i>
                    Sistem GPS Tracking (Simulasi Rute Rill)
                </div>
            </div>
            <div class="content-card-body" style="padding: 0; position: relative; overflow: hidden; border-radius: 0 0 16px 16px;">
                <!-- Map div -->
                <div id="tracking-map" style="height: 300px; width: 100%;"></div>
                
                <!-- Info Overlay -->
                <div style="position: absolute; bottom: 12px; left: 12px; z-index: 1000; background: rgba(30, 41, 59, 0.85); backdrop-filter: blur(8px); border: 1px solid var(--dark-border); padding: 8px 12px; border-radius: 8px; font-size: 12px; max-width: 90%;">
                    <div style="font-weight: 700; color: #fff; margin-bottom: 2px;"><i class="fas fa-satellite-dish text-warning me-1 animate-pulse"></i> Sinyal GPS:</div>
                    <div id="gps-status" style="color: #cbd5e1;">Menghitung rute perjalanan...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Pre-Trip Inspection & POD -->
    <div class="col-lg-6">
        <!-- Section: Pre-Trip Inspection -->
        <div class="content-card mb-4">
            <div class="content-card-header" style="background: rgba(16,185,129,0.02);">
                <div class="content-card-title">
                    <i class="fas fa-clipboard-check" style="color: var(--success)"></i>
                    Laporan Kelayakan Jalan Truk (Cek Fisik)
                </div>
                <?php if ($inspection): ?>
                    <span class="badge-status <?= $inspection['status'] === 'passed' ? 'badge-active' : 'badge-inactive' ?>" style="font-size: 12px;">
                        <?= $inspection['status'] === 'passed' ? 'LOLOS (PASSED)' : 'RUSAK (FAILED)' ?>
                    </span>
                <?php else: ?>
                    <span class="badge-status badge-inactive" style="font-size: 12px; background: rgba(245,158,11,0.15); color: #fbbf24; border-color: rgba(245,158,11,0.25);">Belum Dicek</span>
                <?php endif; ?>
            </div>
            <div class="content-card-body">
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px; line-height: 1.5;">
                    Cek fisik wajib disubmit oleh petugas Checker sebelum Surat Jalan dicetak. Menjamin keamanan armada.
                </p>
                <?php if ($inspection): ?>
                <div class="row g-3" style="font-size: 13.5px;">
                    <div class="col-sm-6">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Petugas Pemeriksa</div>
                        <div style="font-weight: 600; color: #fff;"><?= htmlspecialchars($inspection['checker_name']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Tanggal Pengecekan</div>
                        <div style="font-weight: 600; color: #fff;"><?= date('d-m-Y H:i', strtotime($inspection['created_at'])) ?></div>
                    </div>
                    
                    <div class="col-12" style="margin-top: 16px;">
                        <div style="font-weight: 700; color: #fff; margin-bottom: 10px; font-size: 13px;">Hasil Pemeriksaan Parameter:</div>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div style="padding: 6px 10px; background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 6px;">
                                <i class="<?= $inspection['tires_ok'] ? 'fas fa-circle-check text-success' : 'fas fa-circle-xmark text-danger' ?> me-2"></i> Ban &amp; Udara
                            </div>
                            <div style="padding: 6px 10px; background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 6px;">
                                <i class="<?= $inspection['brakes_ok'] ? 'fas fa-circle-check text-success' : 'fas fa-circle-xmark text-danger' ?> me-2"></i> Sistem Rem
                            </div>
                            <div style="padding: 6px 10px; background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 6px;">
                                <i class="<?= $inspection['lights_ok'] ? 'fas fa-circle-check text-success' : 'fas fa-circle-xmark text-danger' ?> me-2"></i> Lampu-Lampu
                            </div>
                            <div style="padding: 6px 10px; background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 6px;">
                                <i class="<?= $inspection['engine_oil_ok'] ? 'fas fa-circle-check text-success' : 'fas fa-circle-xmark text-danger' ?> me-2"></i> Oli &amp; Radiator
                            </div>
                            <div style="padding: 6px 10px; background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 6px; grid-column: span 2;">
                                <i class="<?= $inspection['documents_ok'] ? 'fas fa-circle-check text-success' : 'fas fa-circle-xmark text-danger' ?> me-2"></i> Surat Kabin (KIR &amp; STNK)
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($inspection['notes']): ?>
                    <div class="col-12" style="margin-top: 14px;">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Catatan Kerusakan/Info Truk:</div>
                        <div style="padding: 10px 14px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 8px; font-style: italic; color: #cbd5e1;">
                            "<?= htmlspecialchars($inspection['notes']) ?>"
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="empty-state" style="padding: 24px 12px; border: 1px dashed var(--dark-border); border-radius: 12px;">
                    <i class="fas fa-clipboard-question text-warning mb-2" style="font-size: 32px;"></i>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Belum ada laporan cek fisik masuk dari Driver.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Section: Cargo Loading Verification & Seal -->
        <div class="content-card mb-4">
            <div class="content-card-header" style="background: rgba(139,92,246,0.02);">
                <div class="content-card-title">
                    <i class="fas fa-box-open" style="color: var(--primary-light)"></i>
                    Laporan Pemuatan Kargo &amp; Segel Kontainer
                </div>
                <?php if ($order['seal_number']): ?>
                    <span class="badge-status badge-active" style="font-size: 12px; background: rgba(139,92,246,0.15); color: #a78bfa; border-color: rgba(139,92,246,0.25);">VERIFIED</span>
                <?php else: ?>
                    <span class="badge-status badge-inactive" style="font-size: 12px;">Belum Diverifikasi</span>
                <?php endif; ?>
            </div>
            <div class="content-card-body">
                <?php if ($order['seal_number']): ?>
                <?php
                // Fetch loading verifier name
                $verifier = $this->db->select('name')->from('users')->where('id', $order['loading_verified_by'])->get()->row_array();
                $verifier_name = $verifier ? $verifier['name'] : 'Checker';
                ?>
                <div class="row g-3" style="font-size: 13.5px;">
                    <div class="col-sm-6">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Nomor Segel (Seal)</div>
                        <div style="font-weight: 700; color: #a78bfa; font-size: 14.5px;"><i class="fas fa-key me-1"></i> <?= htmlspecialchars($order['seal_number']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Tanggal Verifikasi</div>
                        <div style="font-weight: 600; color: #fff;"><?= date('d-m-Y H:i', strtotime($order['loading_verified_at'])) ?></div>
                    </div>
                    <div class="col-12" style="border-top: 1px solid var(--dark-border); padding-top: 10px; margin-top: 10px;">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Petugas Checker</div>
                        <div style="font-weight: 600; color: #fff;"><i class="fas fa-user-check me-1" style="color: var(--success)"></i> <?= htmlspecialchars($verifier_name) ?></div>
                    </div>
                    <?php if ($order['loading_notes']): ?>
                    <div class="col-12">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Catatan Pemuatan:</div>
                        <div style="padding: 8px 12px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 8px; font-style: italic; color: #cbd5e1;">
                            "<?= htmlspecialchars($order['loading_notes']) ?>"
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="empty-state" style="padding: 24px 12px; border: 1px dashed var(--dark-border); border-radius: 12px;">
                    <i class="fas fa-dolly text-muted mb-2" style="font-size: 32px; opacity: 0.4;"></i>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                        <?php if ($order['status'] === 'loading'): ?>
                            Kargo sedang dalam pemuatan oleh Driver. Menunggu verifikasi segel &amp; rilis dari Checker.
                        <?php else: ?>
                            Pemuatan kargo belum dimulai.
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Section: Proof of Delivery (POD) -->
        <div class="content-card">
            <div class="content-card-header" style="background: rgba(99,102,241,0.02);">
                <div class="content-card-title">
                    <i class="fas fa-images" style="color: var(--primary-light)"></i>
                    Bukti Serah Terima Barang (POD)
                </div>
                <?php if ($pod): ?>
                    <span class="badge-status <?= $pod['verified_by'] ? 'badge-active' : 'badge-role' ?>" style="font-size: 12px;">
                        <?= $pod['verified_by'] ? 'VERIFIED (POD Sah)' : 'MENUNGGU VERIFIKASI' ?>
                    </span>
                <?php else: ?>
                    <span class="badge-status badge-inactive" style="font-size: 12px;">Belum Ada POD</span>
                <?php endif; ?>
            </div>
            <div class="content-card-body">
                <?php if ($pod): ?>
                <div class="row g-3" style="font-size: 13.5px;">
                    <div class="col-sm-6">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Nama Penerima Gudang</div>
                        <div style="font-weight: 600; color: #fff;"><?= htmlspecialchars($pod['receiver_name']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Tanggal Unggah POD</div>
                        <div style="font-weight: 600; color: #fff;"><?= date('d-m-Y H:i', strtotime($pod['created_at'])) ?></div>
                    </div>
                    
                    <div class="col-12">
                        <div style="color: var(--text-muted); margin-bottom: 6px;">Foto Lembar Surat Jalan TTD:</div>
                        <div style="border: 1px solid var(--dark-border); border-radius: 12px; overflow: hidden; background: #0f172a; text-align: center;">
                            <img src="<?= base_url($pod['file_path']) ?>" alt="POD Image" style="max-width: 100%; max-height: 250px; object-fit: contain; display: inline-block; padding: 8px;">
                        </div>
                    </div>

                    <?php if ($pod['notes']): ?>
                    <div class="col-12">
                        <div style="color: var(--text-muted); margin-bottom: 4px;">Catatan Tambahan Driver:</div>
                        <div style="padding: 8px 12px; background: rgba(15,23,42,0.4); border: 1px solid var(--dark-border); border-radius: 8px; font-style: italic; color: #cbd5e1;">
                            "<?= htmlspecialchars($pod['notes']) ?>"
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($pod['verified_by']): ?>
                    <div class="col-12" style="border-top: 1px solid var(--dark-border); padding-top: 14px; margin-top: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; color: #4ade80;">
                            <i class="fas fa-check-circle" style="font-size: 16px;"></i>
                            <strong>POD Disetujui oleh: <?= htmlspecialchars($pod['verifier_name'] ?? 'Admin') ?> pada <?= date('d-m-Y H:i', strtotime($pod['verified_at'])) ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action: Verify POD (For Admin/Ops roles) -->
                    <?php if (!$pod['verified_by'] && can('pod.verify')): ?>
                    <div class="col-12" style="border-top: 1px solid var(--dark-border); padding-top: 18px; margin-top: 12px; display: flex; justify-content: flex-end;">
                        <a href="<?= base_url('admin/orders/verify-pod/' . $order['id']) ?>" 
                           onclick="return confirmVerification('<?= base_url('admin/orders/verify-pod/' . $order['id']) ?>')"
                           class="btn-primary-custom" style="background: linear-gradient(135deg, var(--success), #16a34a); box-shadow: 0 4px 15px rgba(34,197,94,0.3);">
                            <i class="fas fa-check-double me-1"></i> Verifikasi &amp; Selesaikan Order (Verify)
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="empty-state" style="padding: 28px 12px; border: 1px dashed var(--dark-border); border-radius: 12px;">
                    <i class="fas fa-images text-muted mb-2" style="font-size: 32px; opacity: 0.4;"></i>
                    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Foto Bukti Tanda Terima (POD) belum diunggah.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 28px; display: flex; gap: 12px; justify-content: flex-start;">
    <a href="<?= base_url('admin/orders') ?>" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Order
    </a>

    <!-- Checker Action: Physical Inspection -->
    <?php if (in_array($order['status'], ['allocated', 'inspect_failed']) && can('inspections.create')): ?>
    <a href="<?= base_url('admin/inspections/check/' . $order['id']) ?>" class="btn-primary-custom" style="background: linear-gradient(135deg, var(--warning), #d97706); box-shadow: 0 4px 15px rgba(245,158,11,0.3);">
        <i class="fas fa-clipboard-check me-1"></i> Lakukan Uji Kelayakan
    </a>
    <?php endif; ?>

    <!-- Checker Action: Cargo Loading Verification -->
    <?php if ($order['status'] === 'loading' && can('loading.verify')): ?>
    <a href="<?= base_url('admin/inspections/verify-loading/' . $order['id']) ?>" class="btn-primary-custom" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); box-shadow: 0 4px 15px rgba(99,102,241,0.3);">
        <i class="fas fa-box-open me-1"></i> Verifikasi Pemuatan &amp; Segel
    </a>
    <?php endif; ?>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars($this->config->item('google_maps_api_key')) ?>&libraries=geometry&language=id"></script>

<script>
/**
 * Confirm POD Approval
 */
function confirmVerification(url) {
    event.preventDefault();
    Swal.fire({
        title: 'Verifikasi POD?',
        text: 'Apakah foto POD dan nama penerima sudah sesuai? Tindakan ini akan menyelesaikan pengiriman kargo.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#475569',
        confirmButtonText: 'Ya, Verifikasi POD',
        cancelButtonText: 'Batal',
        background: '#1e293b',
        color: '#e2e8f0',
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    return false;
}

/**
 * Google Maps real-road GPS route simulator.
 */
document.addEventListener('DOMContentLoaded', function() {
    if (!window.google || !google.maps) {
        document.getElementById('gps-status').innerText = 'Google Maps API tidak dapat dimuat. Periksa GOOGLE_MAPS_API_KEY di .env.';
        return;
    }

    const originPoint = <?= (!empty($order['origin_latitude']) && !empty($order['origin_longitude']))
        ? "{ lat: ".(float)$order['origin_latitude'].", lng: ".(float)$order['origin_longitude']." }"
        : "null" ?>;
    const destinationPoint = <?= (!empty($order['destination_latitude']) && !empty($order['destination_longitude']))
        ? "{ lat: ".(float)$order['destination_latitude'].", lng: ".(float)$order['destination_longitude']." }"
        : "null" ?>;
    const originAddress = <?= json_encode($order['origin']) ?>;
    const destinationAddress = <?= json_encode($order['destination']) ?>;
    const latestLocation = <?= !empty($latest_location)
        ? json_encode(['lat' => (float)$latest_location['latitude'], 'lng' => (float)$latest_location['longitude'], 'recorded_at' => $latest_location['recorded_at']])
        : 'null' ?>;
    const breadcrumbs = <?= json_encode(array_map(function($row) {
        return [
            'lat' => (float) $row['latitude'],
            'lng' => (float) $row['longitude'],
            'recorded_at' => $row['recorded_at']
        ];
    }, $route_breadcrumbs ?? [])) ?>;
    const status = "<?= $order['status'] ?>";
    let gpsText = "";
    let truckPosition = null;

    const routeOrigin = originPoint || originAddress;
    const routeDestination = destinationPoint || destinationAddress;
    const mapCenter = originPoint || destinationPoint || { lat: -6.9175, lng: 110.0 };
    const darkMapStyle = [
        { elementType: 'geometry', stylers: [{ color: '#1e293b' }] },
        { elementType: 'labels.text.stroke', stylers: [{ color: '#1e293b' }] },
        { elementType: 'labels.text.fill', stylers: [{ color: '#94a3b8' }] },
        { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#334155' }] },
        { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#1e293b' }] },
        { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#cbd5e1' }] },
        { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0f172a' }] },
        { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }
    ];

    const map = new google.maps.Map(document.getElementById('tracking-map'), {
        center: mapCenter,
        zoom: 8,
        styles: darkMapStyle,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
        scrollwheel: false
    });

    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
        preserveViewport: true,
        polylineOptions: {
            strokeColor: '#6366f1',
            strokeOpacity: 0.9,
            strokeWeight: 5
        }
    });

    function makeMarker(position, title, color, iconClass) {
        return new google.maps.Marker({
            position: position,
            map: map,
            title: title,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                fillColor: color,
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2
            }
        });
    }

    function getRouteProgress() {
        if (status === 'pending' || status === 'allocated' || status === 'inspect_failed' || status === 'ready' || status === 'loading') {
            return 0;
        }
        if (status === 'in_transit') {
            return 0.52;
        }
        return 1;
    }

    function pointAtProgress(path, progress) {
        if (!path || !path.length) return originPoint || destinationPoint || mapCenter;
        if (progress <= 0) return path[0];
        if (progress >= 1) return path[path.length - 1];

        let total = 0;
        const segments = [];
        for (let i = 1; i < path.length; i++) {
            const distance = google.maps.geometry
                ? google.maps.geometry.spherical.computeDistanceBetween(path[i - 1], path[i])
                : Math.hypot(path[i].lat() - path[i - 1].lat(), path[i].lng() - path[i - 1].lng());
            segments.push(distance);
            total += distance;
        }

        let target = total * progress;
        for (let i = 1; i < path.length; i++) {
            if (target <= segments[i - 1]) {
                const start = path[i - 1];
                const end = path[i];
                const ratio = segments[i - 1] === 0 ? 0 : target / segments[i - 1];
                return new google.maps.LatLng(
                    start.lat() + (end.lat() - start.lat()) * ratio,
                    start.lng() + (end.lng() - start.lng()) * ratio
                );
            }
            target -= segments[i - 1];
        }
        return path[path.length - 1];
    }

    if (status === 'pending') {
        gpsText = "Standby: Menunggu alokasi driver & armada di " + "<?= htmlspecialchars($order['origin']) ?>";
    } else if (status === 'allocated') {
        gpsText = "Standby: Armada terparkir di pool " + "<?= htmlspecialchars($order['origin']) ?>" + ". Menunggu uji kelayakan.";
    } else if (status === 'inspect_failed') {
        gpsText = "Warning: Truk gagal uji kelayakan jalan. Masuk status Bengkel/Maintenance di " + "<?= htmlspecialchars($order['origin']) ?>";
    } else if (status === 'ready') {
        gpsText = "Siap Jalan: Cek fisik lolos. Surat Jalan dicetak. Siap berangkat di " + "<?= htmlspecialchars($order['origin']) ?>";
    } else if (status === 'loading') {
        gpsText = "Loading: Truk masih berada di titik asal. Kargo sedang dimuat dan menunggu verifikasi segel.";
    } else if (status === 'in_transit') {
        gpsText = latestLocation
            ? "In Transit: Posisi terakhir GPS driver tercatat " + latestLocation.recorded_at + "."
            : "In Transit: Simulasi mengikuti rute jalan Google Maps menuju " + "<?= htmlspecialchars($order['destination']) ?>";
    } else if (status === 'arrived') {
        gpsText = "Tiba: Truk sampai di lokasi tujuan " + "<?= htmlspecialchars($order['destination']) ?>" + ". Sedang proses bongkar kargo.";
    } else if (status === 'pod_submitted') {
        gpsText = "POD Terkirim: Kargo diterima. Driver mengunggah bukti Surat Jalan. Menunggu verifikasi admin.";
    } else { // completed
        gpsText = "Selesai: Pengiriman diverifikasi sah. Kargo diserahterahkan sepenuhnya. Armada kembali Available.";
    }

    document.getElementById('gps-status').innerText = gpsText;

    if (originPoint) {
        makeMarker(originPoint, 'Asal: <?= htmlspecialchars($order['origin']) ?>', '#10b981');
    }
    if (destinationPoint) {
        makeMarker(destinationPoint, 'Tujuan: <?= htmlspecialchars($order['destination']) ?>', '#ef4444');
    }

    const truckMarker = new google.maps.Marker({
        map: map,
        title: 'Posisi Truk',
        icon: {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
            scale: 6,
            fillColor: '#fbbf24',
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 2
        }
    });

    const infoWindow = new google.maps.InfoWindow({
        content: "<b>No. Polisi:</b> <?= htmlspecialchars($order['plate_number'] ?? '-') ?><br><b>Driver:</b> <?= htmlspecialchars($order['driver_name'] ?? '-') ?><br><b>GPS:</b> " + gpsText
    });
    truckMarker.addListener('click', function() {
        infoWindow.open(map, truckMarker);
    });

    directionsService.route({
        origin: routeOrigin,
        destination: routeDestination,
        travelMode: google.maps.TravelMode.DRIVING,
        region: 'ID'
    }, function(response, routeStatus) {
        if (routeStatus !== 'OK') {
            document.getElementById('gps-status').innerText = 'Rute Google Maps gagal dihitung: ' + routeStatus + '. Pastikan koordinat asal dan tujuan valid.';
            if (originPoint && destinationPoint) {
                const fallbackBounds = new google.maps.LatLngBounds();
                fallbackBounds.extend(originPoint);
                fallbackBounds.extend(destinationPoint);
                map.fitBounds(fallbackBounds, 50);
            }
            truckMarker.setPosition(latestLocation || originPoint || destinationPoint || mapCenter);
            return;
        }

        directionsRenderer.setDirections(response);
        const route = response.routes[0];
        const path = route.overview_path;
        const leg = route.legs[0];
        const bounds = route.bounds || new google.maps.LatLngBounds();
        map.fitBounds(bounds, 50);

        if (breadcrumbs.length > 0) {
            const breadcrumbPath = breadcrumbs.map(function(point) {
                return new google.maps.LatLng(point.lat, point.lng);
            });
            new google.maps.Polyline({
                path: breadcrumbPath,
                map: map,
                strokeColor: '#22c55e',
                strokeOpacity: 0.95,
                strokeWeight: 4
            });
            truckPosition = new google.maps.LatLng(breadcrumbs[breadcrumbs.length - 1].lat, breadcrumbs[breadcrumbs.length - 1].lng);
            document.getElementById('gps-status').innerText = gpsText + ' Rute biru = jalan Google Maps, hijau = breadcrumb GPS driver.';
        } else {
            truckPosition = pointAtProgress(path, getRouteProgress());
            document.getElementById('gps-status').innerText = gpsText + ' Estimasi Google: ' + leg.distance.text + ', ' + leg.duration.text + '.';
        }

        truckMarker.setPosition(truckPosition);
    });
});
</script>
