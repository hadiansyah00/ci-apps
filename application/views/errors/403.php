<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh;">
    <div style="text-align: center; max-width: 500px;" data-aos="zoom-in">
        <div style="font-size: 80px; font-weight: 900; background: linear-gradient(135deg, #6366f1, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; margin-bottom: 8px;">403</div>
        <h2 style="color: #e2e8f0; font-size: 24px; font-weight: 700; margin-bottom: 12px;">Akses Ditolak</h2>
        <p style="color: #64748b; font-size: 15px; margin-bottom: 28px;">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi administrator jika Anda yakin ini adalah kesalahan.</p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="javascript:history.back()" class="btn-primary-custom" style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.3);">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn-primary-custom">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>
    </div>
</div>
