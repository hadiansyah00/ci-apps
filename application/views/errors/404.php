<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-num { font-size: 100px; font-weight: 900; background: linear-gradient(135deg, #6366f1, #0ea5e9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; }
    </style>
</head>
<body>
    <div style="text-align: center; max-width: 480px; padding: 24px;">
        <div class="error-num">404</div>
        <h2 style="color: #e2e8f0; margin: 12px 0;">Halaman Tidak Ditemukan</h2>
        <p style="color: #64748b; margin-bottom: 24px;">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); border-radius: 10px; color: #a5b4fc; text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="<?= base_url('/') ?>" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 10px; color: white; text-decoration: none; font-weight: 600;">
                <i class="fas fa-home"></i> Beranda
            </a>
        </div>
    </div>
</body>
</html>
