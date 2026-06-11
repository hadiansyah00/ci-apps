<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down" style="margin-bottom: 20px;">
    <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('driver/tasks') ?>" class="btn-sm-icon btn-edit" style="width: 32px; height: 32px; border-radius: 8px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="font-size: 18px; margin: 0;"><i class="fas fa-file-arrow-up me-2" style="color: var(--primary-light)"></i>Kirim Bukti Terima (POD)</h1>
            <div style="font-size: 11px; color: var(--text-muted);">Order #<?= str_pad($task['id'], 5, '0', STR_PAD_LEFT) ?> &bull; Rute: <?= htmlspecialchars($task['origin']) ?> &rarr; <?= htmlspecialchars($task['destination']) ?></div>
        </div>
    </div>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-md-8 col-lg-6">
        <div class="content-card">
            <div class="content-card-header" style="padding: 16px 20px; background: rgba(99,102,241,0.05);">
                <div class="content-card-title" style="font-size: 14px;">
                    <i class="fas fa-file-invoice text-warning"></i> Unggah Dokumen POD
                </div>
            </div>
            <div class="content-card-body" style="padding: 20px;">
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px; line-height: 1.5;">
                    <i class="fas fa-circle-info text-primary me-1"></i> Foto Surat Jalan fisik yang sudah ditandatangani dan dicap oleh penerima barang. Pastikan tulisan terbaca jelas.
                </p>

                <form action="<?= base_url('driver/tasks/upload-pod/' . $task['id']) ?>" method="POST" enctype="multipart/form-data" id="pod-form">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <!-- Receiver Name -->
                    <div class="mb-3">
                        <label for="receiver_name" class="form-label-custom">Nama Penerima Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-custom" id="receiver_name" name="receiver_name" required 
                               placeholder="Contoh: Bpk. Joko (Gudang B)" maxlength="150" value="<?= set_value('receiver_name') ?>">
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Tulis nama lengkap petugas/pihak yang menerima kargo.</div>
                    </div>

                    <!-- File Input (POD Image) -->
                    <div class="mb-4">
                        <label class="form-label-custom">Foto Surat Jalan (POD) <span class="text-danger">*</span></label>
                        
                        <!-- Premium Upload Container -->
                        <div id="upload-box" class="text-center p-4" 
                             style="border: 2px dashed var(--dark-border); border-radius: 12px; background: rgba(15,23,42,0.3); cursor: pointer; transition: all 0.2s;">
                            <input type="file" id="pod_image" name="pod_image" accept="image/*" capture="environment" required style="display: none;">
                            
                            <div id="upload-placeholder">
                                <i class="fas fa-camera text-primary mb-3" style="font-size: 32px; opacity: 0.8;"></i>
                                <div style="font-size: 14px; font-weight: 700; color: #fff;">Ambil Foto Surat Jalan</div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Gunakan kamera ponsel / upload file (Maks 5MB)</div>
                            </div>
                            
                            <div id="upload-preview" class="d-none">
                                <img id="preview-img" src="#" alt="Preview POD" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid var(--dark-border); margin-bottom: 12px;">
                                <div style="font-size: 13px; font-weight: 600; color: #4ade80;"><i class="fas fa-check-circle me-1"></i> Foto Siap Dikirim</div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Ketuk untuk mengambil ulang foto</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label-custom">Catatan Tambahan (Opsional)</label>
                        <textarea class="form-control-custom" id="notes" name="notes" rows="3" placeholder="Tulis keterangan jika ada barang kurang, rusak, atau info tambahan..."><?= set_value('notes') ?></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn-primary-custom justify-content-center" style="padding: 12px; font-size: 15px;">
                            <i class="fas fa-paper-plane me-2"></i> Laporkan POD &amp; Selesaikan Tugas
                        </button>
                        <a href="<?= base_url('driver/tasks') ?>" class="btn-primary-custom justify-content-center" 
                           style="padding: 12px; font-size: 15px; background: transparent; border: 1px solid var(--dark-border); color: var(--text-secondary);">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadBox = document.getElementById('upload-box');
    const fileInput = document.getElementById('pod_image');
    const placeholder = document.getElementById('upload-placeholder');
    const preview = document.getElementById('upload-preview');
    const previewImg = document.getElementById('preview-img');
    const form = document.getElementById('pod-form');

    // Trigger file selection on box click
    uploadBox.addEventListener('click', function() {
        fileInput.click();
    });

    // Image preview handler
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                placeholder.classList.add('d-none');
                preview.classList.remove('d-none');
                uploadBox.style.borderColor = 'rgba(99,102,241,0.5)';
                uploadBox.style.background = 'rgba(99,102,241,0.02)';
            }
            reader.readAsDataURL(file);
        }
    });

    // Client-side validation & submit confirm
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const receiverName = document.getElementById('receiver_name').value.trim();
        if (!receiverName) {
            Swal.fire({
                title: 'Nama Penerima Kosong',
                text: 'Silahkan isi nama penerima barang terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#6366f1',
                background: '#1e293b',
                color: '#e2e8f0',
            });
            return;
        }

        if (!fileInput.files.length) {
            Swal.fire({
                title: 'Foto POD Belum Ada',
                text: 'Anda wajib mengambil foto Surat Jalan fisik sebagai bukti serah terima.',
                icon: 'warning',
                confirmButtonColor: '#6366f1',
                background: '#1e293b',
                color: '#e2e8f0',
            });
            return;
        }

        Swal.fire({
            title: 'Kirim Bukti POD?',
            text: 'Pastikan nama penerima dan foto Surat Jalan sudah sesuai dan terbaca dengan jelas.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Ya, Kirim Sekarang',
            cancelButtonText: 'Batal',
            background: '#1e293b',
            color: '#e2e8f0',
        }).then(function(result) {
            if (result.isConfirmed) {
                // Show loading indicator
                Swal.fire({
                    title: 'Sedang Mengunggah...',
                    html: 'Mohon tunggu sebentar, bukti pengiriman sedang diupload ke server.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: '#1e293b',
                    color: '#e2e8f0',
                });
                form.submit();
            }
        });
    });
});
</script>
