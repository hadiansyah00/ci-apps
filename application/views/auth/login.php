<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?></title>
    <meta name="description" content="Login ke sistem CI3 Auth & RBAC Management">
    
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Toastr.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #090d16;
            --dark-card: rgba(17, 24, 39, 0.7);
            --text-muted-custom: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: var(--dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 0%, rgba(14, 165, 233, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(129, 140, 248, 0.08) 0px, transparent 50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 40px 20px;
        }

        /* Ambient floating glow circles behind content */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 1;
            pointer-events: none;
            opacity: 0.55;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(99, 102, 241, 0) 70%);
            top: -100px;
            right: -100px;
            animation: float-slow 15s ease-in-out infinite alternate;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, rgba(14, 165, 233, 0) 70%);
            bottom: -80px;
            left: -80px;
            animation: float-slow 12s ease-in-out infinite alternate-reverse;
        }

        @keyframes float-slow {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -50px) scale(1.1); }
            100% { transform: translate(-30px, 30px) scale(0.95); }
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        /* Brand Logo Area */
        .brand-area {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
            margin-bottom: 14px;
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.35);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 8px 30px rgba(99, 102, 241, 0.35); }
            50% { box-shadow: 0 8px 45px rgba(99, 102, 241, 0.6), 0 0 0 6px rgba(99, 102, 241, 0.1); }
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #ffffff 50%, #c7d2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            color: var(--text-muted-custom);
            font-size: 13px;
            margin-top: 3px;
            font-weight: 500;
        }

        /* Glassmorphism Card */
        .login-card {
            background: var(--dark-card);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 38px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                inset 0 0 80px rgba(99, 102, 241, 0.02);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .card-header-text h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .card-header-text p {
            color: var(--text-muted-custom);
            font-size: 13px;
            margin-bottom: 26px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            letter-spacing: 0.2px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 15px;
            transition: color 0.25s ease;
            z-index: 2;
            pointer-events: none;
        }

        /* High specificity control styling to override default Bootstrap */
        .login-card .form-control {
            width: 100%;
            padding: 13px 16px 13px 44px;
            background: rgba(10, 15, 30, 0.65) !important;
            border: 1px solid #2d3f5a !important;
            border-radius: 12px;
            color: #fff !important;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            outline: none;
            box-shadow: none !important;
        }

        .login-card .form-control:focus {
            border-color: var(--primary) !important;
            background: rgba(10, 15, 30, 0.85) !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
            color: #fff !important;
        }

        /* Icon color change on input wrapper focus */
        .input-wrapper:focus-within .input-icon {
            color: var(--primary-light);
        }

        .login-card .form-control::placeholder {
            color: #475569;
        }

        /* Password toggle button positioning */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 6px;
            z-index: 5;
            transition: color 0.25s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover { color: var(--primary-light); }

        #password-field {
            padding-right: 44px;
        }

        /* Remember me & forgot password container */
        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
        }

        .login-card .form-check-input {
            width: 17px;
            height: 17px;
            background-color: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid #334155 !important;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: none !important;
        }

        .login-card .form-check-input:checked {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .login-card .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
            border-color: var(--primary) !important;
        }

        .form-check-label {
            font-size: 13.5px;
            color: #94a3b8;
            cursor: pointer;
            margin-left: 5px;
            user-select: none;
        }

        .forgot-link {
            font-size: 13.5px;
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover { color: #c7d2fe; }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 14.5px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.2px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover::before { left: 100%; }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.45);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4) !important;
            outline: none;
        }

        .btn-login:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-login .spinner-border {
            width: 16px;
            height: 16px;
            border-width: 2px;
        }

        /* Error alert wrapper */
        .alert-login-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fca5a5;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeInDown 0.3s ease;
        }

        /* Footer styling */
        .login-footer {
            text-align: center;
            margin-top: 28px;
            color: #475569;
            font-size: 12.5px;
        }

        .login-footer a {
            color: var(--text-muted-custom);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .login-footer a:hover {
            color: #fff;
        }

        /* Decorative dots */
        .dots-grid {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 150px;
            height: 150px;
            opacity: 0.05;
            background-image: radial-gradient(circle, #6366f1 1px, transparent 1px);
            background-size: 20px 20px;
            z-index: 1;
            pointer-events: none;
        }

        @media (max-width: 480px) {
            .login-card { padding: 30px 22px; border-radius: 20px; }
            body { padding: 20px 14px; }
        }
    </style>
</head>
<body>

<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="dots-grid"></div>

<div class="login-wrapper animate__animated animate__fadeInUp">
    <!-- Brand Area -->
    <div class="brand-area">
        <div class="brand-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        <div class="brand-title">CI3 Auth &amp; RBAC</div>
        <div class="brand-subtitle">Sistem Manajemen Akses Modern</div>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        <div class="card-header-text">
            <h2>Selamat Datang <span style="color: var(--primary-light)">&#x1F44B;</span></h2>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Error Alert (shown on validation failure without AJAX) -->
        <div id="error-alert" class="alert-login-error d-none">
            <i class="fas fa-circle-exclamation"></i>
            <span id="error-message"></span>
        </div>

        <!-- Login Form -->
        <form id="login-form" action="<?= base_url('auth/do_login') ?>" method="POST" autocomplete="off">
            <!-- CSRF Token -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <!-- Identity (Email or Username) -->
            <div class="form-group">
                <label class="form-label" for="identity">
                    <i class="fas fa-at me-1" style="color: var(--primary-light)"></i> Email atau Username
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input
                        type="text"
                        class="form-control"
                        id="identity"
                        name="identity"
                        placeholder="email@example.com atau username"
                        autocomplete="username"
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password-field">
                    <i class="fas fa-lock me-1" style="color: var(--primary-light)"></i> Password
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        type="password"
                        class="form-control"
                        id="password-field"
                        name="password"
                        placeholder="Masukkan password Anda"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="password-toggle" id="toggle-password" title="Tampilkan password">
                        <i class="fas fa-eye" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Options -->
            <div class="login-options">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" value="1">
                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                </div>
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login" id="btn-login">
                <span id="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Masuk ke Sistem</span>
                <span id="btn-loading" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Memproses...
                </span>
            </button>
        </form>
    </div>

    <div class="login-footer">
        &copy; <?= date('Y') ?> CI3 Auth &amp; RBAC System &mdash; 
        <a href="#">Bantuan</a>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Toastr.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Toastr Configuration
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 4000,
    extendedTimeOut: 1000,
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
    newestOnTop: true,
};

<?php if (!empty($toast['message'])): ?>
$(document).ready(function() {
    var type = '<?= $toast['type'] ?? 'info' ?>';
    var message = '<?= addslashes($toast['message']) ?>';
    if (typeof toastr[type] === 'function') {
        toastr[type](message);
    } else {
        toastr.info(message);
    }
});
<?php endif; ?>

$(document).ready(function() {
    // Password toggle
    $('#toggle-password').on('click', function() {
        var input = $('#password-field');
        var icon = $('#toggle-icon');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // AJAX Login Form
    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $('#btn-login');
        var $btnText = $('#btn-text');
        var $btnLoading = $('#btn-loading');
        var $errorAlert = $('#error-alert');
        var $errorMsg = $('#error-message');

        // Show loading
        $btn.prop('disabled', true);
        $btnText.addClass('d-none');
        $btnLoading.removeClass('d-none');
        $errorAlert.addClass('d-none');

        var formData = $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(function() {
                        window.location.href = res.redirect || '<?= base_url('admin/dashboard') ?>';
                    }, 800);
                } else {
                    // Show error
                    $errorMsg.text(res.message);
                    $errorAlert.removeClass('d-none');
                    toastr.error(res.message);
                    
                    // Reset button
                    $btn.prop('disabled', false);
                    $btnText.removeClass('d-none');
                    $btnLoading.addClass('d-none');

                    // Shake animation on card
                    $('.login-card').addClass('animate__animated animate__shakeX');
                    setTimeout(function() {
                        $('.login-card').removeClass('animate__animated animate__shakeX');
                    }, 600);
                }
            },
            error: function(xhr) {
                var msg = 'Terjadi kesalahan. Silahkan coba lagi.';
                if (xhr.status === 419 || xhr.status === 403) {
                    msg = 'Sesi CSRF habis. Silahkan refresh halaman.';
                }
                $errorMsg.text(msg);
                $errorAlert.removeClass('d-none');
                toastr.error(msg);
                
                $btn.prop('disabled', false);
                $btnText.removeClass('d-none');
                $btnLoading.addClass('d-none');
            }
        });
    });

    // Focus first input
    $('#identity').focus();
});
</script>
</body>
</html>
