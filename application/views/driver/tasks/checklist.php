<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down" style="margin-bottom: 20px;">
    <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('driver/tasks') ?>" class="btn-sm-icon btn-edit" style="width: 32px; height: 32px; border-radius: 8px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size: 18px; margin: 0;"><i class="fas fa-list-check me-2" style="color: var(--primary-light)"></i>Cek Kelayakan Truk</h1>
            <div style="font-size: 11px; color: var(--text-muted);">Order #<?= str_pad($task['id'], 5, '0', STR_PAD_LEFT) ?> &bull; Plat <?= htmlspecialchars($task['plate_number']) ?></div>
        </div>
    </div>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-md-8 col-lg-6">
        <div class="content-card">
            <div class="content-card-header" style="padding: 16px 20px; background: rgba(99,102,241,0.05);">
                <div class="content-card-title" style="font-size: 14px;">
                    <i class="fas fa-clipboard-check text-warning"></i> Lembar Pemeriksaan Mandiri
                </div>
            </div>
            <div class="content-card-body" style="padding: 20px;">
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                    <i class="fas fa-info-circle text-primary me-1"></i> Periksa kondisi fisik armada secara teliti sebelum berangkat. Aktifkan semua saklar jika kondisi dalam keadaan baik/layak.
                </p>

                <form action="<?= base_url('driver/tasks/submit-checklist/' . $task['id']) ?>" method="POST" id="checklist-form">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <!-- Item 1: Tires -->
                    <div class="checklist-item d-flex align-items-center justify-content-between p-3 mb-3" 
                         style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 12px; transition: border-color 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,0.1); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-circle-dot"></i>
                            </div>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #fff;">Ban Kendaraan</div>
                                <div style="font-size: 11px; color: var(--text-muted);">Tekanan udara & kembangan ban layak</div>
                            </div>
                        </div>
                        <div class="form-check form-switch m-0 p-0" style="min-height: auto;">
                            <input class="form-check-input ms-0" type="checkbox" role="switch" id="tires_ok" name="tires_ok" value="1" style="width: 2.2em; height: 1.2em; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Item 2: Brakes -->
                    <div class="checklist-item d-flex align-items-center justify-content-between p-3 mb-3" 
                         style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 12px; transition: border-color 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(239,68,68,0.1); color: #f87171; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-circle-stop"></i>
                            </div>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #fff;">Sistem Pengereman</div>
                                <div style="font-size: 11px; color: var(--text-muted);">Rem utama & rem parkir berfungsi pakem</div>
                            </div>
                        </div>
                        <div class="form-check form-switch m-0 p-0">
                            <input class="form-check-input ms-0" type="checkbox" role="switch" id="brakes_ok" name="brakes_ok" value="1" style="width: 2.2em; height: 1.2em; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Item 3: Lights -->
                    <div class="checklist-item d-flex align-items-center justify-content-between p-3 mb-3" 
                         style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 12px; transition: border-color 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(245,158,11,0.1); color: #fbbf24; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #fff;">Lampu & Kelistrikan</div>
                                <div style="font-size: 11px; color: var(--text-muted);">Lampu utama, sein, rem, mundur menyala</div>
                            </div>
                        </div>
                        <div class="form-check form-switch m-0 p-0">
                            <input class="form-check-input ms-0" type="checkbox" role="switch" id="lights_ok" name="lights_ok" value="1" style="width: 2.2em; height: 1.2em; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Item 4: Engine Oil -->
                    <div class="checklist-item d-flex align-items-center justify-content-between p-3 mb-3" 
                         style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 12px; transition: border-color 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(6,182,212,0.1); color: #22d3ee; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-oil-can"></i>
                            </div>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #fff;">Oli Mesin & Air Radiator</div>
                                <div style="font-size: 11px; color: var(--text-muted);">Volume cukup & tidak ada kebocoran</div>
                            </div>
                        </div>
                        <div class="form-check form-switch m-0 p-0">
                            <input class="form-check-input ms-0" type="checkbox" role="switch" id="engine_oil_ok" name="engine_oil_ok" value="1" style="width: 2.2em; height: 1.2em; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Item 5: Documents -->
                    <div class="checklist-item d-flex align-items-center justify-content-between p-3 mb-4" 
                         style="background: rgba(15,23,42,0.3); border: 1px solid var(--dark-border); border-radius: 12px; transition: border-color 0.2s;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(34,197,94,0.1); color: #4ade80; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #fff;">Surat-Surat Kendaraan</div>
                                <div style="font-size: 11px; color: var(--text-muted);">STNK & KIR hidup dan siap di kabin</div>
                            </div>
                        </div>
                        <div class="form-check form-switch m-0 p-0">
                            <input class="form-check-input ms-0" type="checkbox" role="switch" id="documents_ok" name="documents_ok" value="1" style="width: 2.2em; height: 1.2em; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label-custom">Catatan Tambahan (Kondisi/Kerusakan)</label>
                        <textarea class="form-control-custom" id="notes" name="notes" rows="3" placeholder="Tulis catatan jika ada kendala atau bagian yang bermasalah..."></textarea>
                    </div>

                    <!-- Warning Alert when not all checked -->
                    <div id="check-alert" class="alert-custom alert-warning d-none" style="margin-bottom: 24px;">
                        <i class="fas fa-triangle-exclamation" style="font-size: 16px; margin-top: 2px;"></i>
                        <div>
                            <strong>Peringatan!</strong> Jika ada item yang tidak dicentang (Tidak Layak), Truk otomatis masuk status <strong>Maintenance</strong> dan order ini akan tertunda.
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn-primary-custom justify-content-center" style="padding: 12px; font-size: 15px;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Laporan Pemeriksaan
                        </button>
                        <a href="<?= base_url('driver/tasks') ?>" class="btn-primary-custom justify-content-center" 
                           style="padding: 12px; font-size: 15px; background: transparent; border: 1px solid var(--dark-border); color: var(--text-secondary);">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const switches = document.querySelectorAll('#checklist-form .form-check-input');
    const alertBox = document.getElementById('check-alert');
    const checklistItems = document.querySelectorAll('.checklist-item');

    function checkSwitches() {
        let allChecked = true;
        switches.forEach((sw, idx) => {
            const item = checklistItems[idx];
            if (!sw.checked) {
                allChecked = false;
                item.style.borderColor = 'rgba(239, 68, 68, 0.4)';
                item.style.boxShadow = '0 0 8px rgba(239, 68, 68, 0.1)';
            } else {
                item.style.borderColor = 'var(--dark-border)';
                item.style.boxShadow = 'none';
            }
        });

        if (allChecked) {
            alertBox.classList.add('d-none');
        } else {
            alertBox.classList.remove('d-none');
        }
    }

    // Initialize state
    checkSwitches();

    // Attach event listeners
    switches.forEach(sw => {
        sw.addEventListener('change', checkSwitches);
    });

    // Form submission confirmation
    const form = document.getElementById('checklist-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let allChecked = true;
        switches.forEach(sw => {
            if (!sw.checked) allChecked = false;
        });

        const title = allChecked ? 'Kirim Hasil Cek?' : 'Kirim Laporan Kerusakan?';
        const text = allChecked 
            ? 'Semua sistem dinyatakan LAYAK. Status armada akan berubah menjadi READY.' 
            : 'Ada sistem TIDAK LAYAK. Status armada akan menjadi MAINTENANCE.';
        const confirmColor = allChecked ? '#22c55e' : '#f59e0b';
        const confirmText = allChecked ? 'Ya, Kirim (Layak)' : 'Ya, Kirim (Rusak)';

        Swal.fire({
            title: title,
            text: text,
            icon: allChecked ? 'success' : 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#475569',
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal',
            background: '#1e293b',
            color: '#e2e8f0',
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
