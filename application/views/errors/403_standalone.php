<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-num { font-size: 100px; font-weight: 900; background: linear-gradient(135deg, #6366f1, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; }
    </style>
</head>
<body>
    <div style="text-align: center; max-width: 480px; padding: 24px;">
        <div class="error-num">403</div>
        <h2 style="color: #e2e8f0; margin: 12px 0;">Akses Ditolak</h2>
        <p style="color: #64748b; margin-bottom: 24px;">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="<?= base_url('login') ?>" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 12px; color: white; text-decoration: none; font-weight: 600;">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
    </div>
</body>
</html>
