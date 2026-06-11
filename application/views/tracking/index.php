<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        :root {
            --primary: #1e3a8a;       /* Dark Royal Blue */
            --primary-light: #3b82f6; /* Bright Blue */
            --accent: #dc2626;        /* Crimson Red */
            --bg-body: #f8fafc;
            --card-border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar-brand-custom {
            font-weight: 800;
            color: var(--primary);
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand-custom span {
            color: var(--accent);
        }

        .nav-link-custom {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--text-dark) !important;
            transition: color 0.2s;
            padding: 8px 16px !important;
        }

        .nav-link-custom:hover {
            color: var(--primary-light) !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            color: white;
            padding: 100px 0 120px 0;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&q=80&w=1200') center/cover no-repeat;
            opacity: 0.18;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .search-box-container {
            max-width: 600px;
            margin: 35px auto 0 auto;
        }

        .search-box-custom {
            background: white;
            border-radius: 50px;
            padding: 8px 10px 8px 24px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .search-box-custom:focus-within {
            border-color: var(--primary-light);
            box-shadow: 0 15px 35px rgba(59,130,246,0.3);
        }

        .search-input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 30px;
            font-weight: 700;
            transition: all 0.2s;
        }

        .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.4);
        }

        /* Section Corporate General */
        .section-padding {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 30px;
            font-weight: 800;
            color: var(--primary);
            position: relative;
            display: inline-block;
            padding-bottom: 12px;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background-color: var(--accent);
            border-radius: 2px;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 15px;
            margin-top: 10px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* About Us styling */
        .about-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.01);
        }

        /* Services Grid */
        .service-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.01);
            transition: all 0.3s;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(30,58,138,0.08);
            border-color: var(--primary-light);
        }

        .service-icon {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            background: rgba(30,58,138,0.08);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 20px auto;
            transition: all 0.3s;
        }

        .service-card:hover .service-icon {
            background: var(--primary);
            color: white;
        }

        /* Fleet Cards */
        .fleet-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.01);
            transition: all 0.3s;
            height: 100%;
        }

        .fleet-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            border-color: var(--card-border);
        }

        .fleet-img-placeholder {
            height: 180px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 40px;
        }

        .fleet-info {
            padding: 20px;
        }

        /* Tracking Timeline styling (Copied from index) */
        .timeline-card-public {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        .tracking-timeline-public {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: 24px 0 10px 0;
            padding: 0 10px;
        }

        .tracking-timeline-public::before {
            content: '';
            position: absolute;
            top: 22px;
            left: 0; right: 0;
            height: 4px;
            background: #e2e8f0;
            z-index: 1;
        }

        .tracking-line-progress-public {
            position: absolute;
            top: 22px;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            z-index: 2;
            transition: width 0.4s ease;
        }

        .tracking-step-public {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90px;
        }

        .tracking-icon-circle-public {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 3px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .tracking-step-public.active .tracking-icon-circle-public {
            background: var(--primary);
            border-color: var(--primary-light);
            color: white;
            box-shadow: 0 0 12px rgba(30,58,138,0.3);
        }

        .tracking-step-public.completed .tracking-icon-circle-public {
            background: #10b981;
            border-color: #34d399;
            color: white;
            box-shadow: 0 0 10px rgba(16,185,129,0.25);
        }

        .tracking-step-public.failed .tracking-icon-circle-public {
            background: var(--accent);
            border-color: #f87171;
            color: white;
            box-shadow: 0 0 10px rgba(220,38,38,0.25);
        }

        .tracking-label-public {
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            margin-top: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tracking-step-public.active .tracking-label-public {
            color: var(--primary-light);
        }

        .tracking-step-public.completed .tracking-label-public {
            color: var(--text-dark);
        }

        .tracking-step-public.failed .tracking-label-public {
            color: var(--accent);
        }

        .info-card {
            background: white;
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.02);
            height: 100%;
        }

        .info-card-header {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 12px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #tracking-map {
            height: 350px;
            border-radius: 12px;
            border: 1px solid var(--card-border);
        }

        @media (max-width: 767px) {
            .tracking-timeline-public {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
                padding-left: 20px;
            }
            .tracking-timeline-public::before {
                left: 40px; top: 0; bottom: 0;
                width: 4px; height: 100%;
            }
            .tracking-line-progress-public {
                left: 40px; top: 0;
                width: 4px !important;
                height: var(--progress-mobile-height, 0%);
            }
            .tracking-step-public {
                flex-direction: row;
                gap: 16px;
                width: 100%;
            }
            .tracking-label-public {
                margin-top: 0;
                text-align: left;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container py-2">
            <a class="navbar-brand-custom" href="<?= base_url() ?>">
                <i class="fas fa-truck-fast text-primary"></i> PT Tirta Nusa Persada <span>TNP</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="<?= base_url() ?>#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="<?= base_url() ?>#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="<?= base_url() ?>#services">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="<?= base_url() ?>#fleet">Armada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="<?= base_url() ?>#contact">Kontak</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="<?= base_url('login') ?>" class="btn btn-primary rounded-pill px-4" style="font-size: 13.5px; font-weight: 600; background: var(--primary); border-color: var(--primary);">
                            <i class="fas fa-sign-in-alt me-1"></i> Login Staff
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Home) -->
    <header class="hero-section" id="home">
        <div class="container hero-content">
            <h1 class="fw-extrabold text-white" style="font-size: 36px; font-weight: 900; letter-spacing: -0.8px; line-height: 1.2;">
                Solusi Logistik &amp; Transportasi Terpercaya
            </h1>
            <p class="text-white opacity-75 mt-2" style="font-size: 16px; max-width: 600px; margin-left: auto; margin-right: auto;">
                PT Tirta Nusa Persada menghadirkan pengiriman kargo aman, armada layak jalan, pengemudi profesional, dan pelacakan rute GPS real-time.
            </p>
            
            <!-- Integrated Search Form -->
            <div class="search-box-container">
                <form action="<?= base_url() ?>" method="GET">
                    <div class="search-box-custom">
                        <i class="fas fa-route text-muted" style="font-size: 18px;"></i>
                        <input type="text" name="order_id" class="search-input" placeholder="Masukkan Nomor Order (Contoh: 12 atau 13)" 
                               required value="<?= htmlspecialchars($order_id ?? '') ?>">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-magnifying-glass me-1"></i> Lacak Kargo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <!-- Tracking Results Panel (Shown only when order_id is searched) -->
    <?php if ($order_id || $order || $error): ?>
    <section class="container py-5" id="track">
        <div class="section-header" style="margin-bottom: 30px;">
            <h2>Hasil Pelacakan Pengiriman</h2>
            <p>Informasi real-time kargo untuk Nomor Order #<?= htmlspecialchars($order_id) ?></p>
        </div>

        <?php if ($error): ?>
            <!-- Error Banner -->
            <div class="alert alert-danger border-0 shadow-sm p-4 rounded-3 d-flex align-items-center gap-3" role="alert">
                <i class="fas fa-circle-exclamation text-danger fs-3"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1" style="font-size: 15px;">Order Tidak Ditemukan</h5>
                    <p class="mb-0 text-secondary" style="font-size: 13.5px;"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($order): ?>
            <!-- If Order Found -->
            <?php
            $status = $order['status'];
            $progress = 0;
            $progress_mobile = "0%";
 
            $step1 = 'completed';
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
                $step7 = 'active';
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
            
            <!-- Progress Timeline -->
            <div class="timeline-card-public" style="--progress-mobile-height: <?= $progress_mobile ?>;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 style="font-size: 14.5px; font-weight: 700; color: var(--primary); margin: 0;">
                        <i class="fas fa-route me-2"></i>Status Perjalanan Pengiriman
                    </h4>
                    
                    <?php 
                    $CI =& get_instance();
                    $CI->load->model('Order_model');
                    $status_label = $CI->Order_model->get_status_label($status);
                    ?>
                    <span class="badge" style="background-color: <?= $status_label['color'] ?>; font-size: 12px; padding: 6px 12px; border-radius: 20px;">
                        <?= $status_label['label'] ?>
                    </span>
                </div>
                
                <div class="tracking-timeline-public">
                    <div class="tracking-line-progress-public d-none d-md-block" style="width: <?= $progress ?>%;"></div>
                    <div class="tracking-line-progress-public d-md-none" style="height: <?= $progress_mobile ?>;"></div>
                    
                    <div class="tracking-step-public <?= $step1 ?>">
                        <div class="tracking-icon-circle-public"><i class="fas fa-file-invoice"></i></div>
                        <div class="tracking-label-public">Order Dibuat</div>
                    </div>
                    
                    <div class="tracking-step-public <?= $step2 ?>">
                        <div class="tracking-icon-circle-public"><i class="fas fa-truck-ramp-box"></i></div>
                        <div class="tracking-label-public">Armada Ditunjuk</div>
                    </div>
                    
                    <div class="tracking-step-public <?= $step3 ?>">
                        <div class="tracking-icon-circle-public">
                            <?php if ($status === 'inspect_failed'): ?>
                                <i class="fas fa-circle-xmark"></i>
                            <?php else: ?>
                                <i class="fas fa-clipboard-check"></i>
                            <?php endif; ?>
                        </div>
                        <div class="tracking-label-public">Cek Kelayakan</div>
                    </div>
                    
                    <div class="tracking-step-public <?= $step4 ?>">
                        <div class="tracking-icon-circle-public"><i class="fas fa-circle-play"></i></div>
                        <div class="tracking-label-public">Siap Jalan</div>
                    </div>

                    <div class="tracking-step-public <?= $step5 ?>">
                        <div class="tracking-icon-circle-public"><i class="fas fa-dolly"></i></div>
                        <div class="tracking-label-public">Proses Muat</div>
                    </div>
                    
                    <div class="tracking-step-public <?= $step6 ?>">
                        <div class="tracking-icon-circle-public"><i class="fas fa-truck-fast"></i></div>
                        <div class="tracking-label-public">Dalam Rute</div>
                    </div>
                    
                    <div class="tracking-step-public <?= $step7 ?>">
                        <div class="tracking-icon-circle-public"><i class="fas fa-circle-check"></i></div>
                        <div class="tracking-label-public">Selesai (POD)</div>
                    </div>
                </div>
            </div>

            <!-- Maps & Details -->
            <div class="row g-4">
                <!-- Left: Maps Tracking -->
                <div class="col-lg-7">
                    <div class="info-card">
                        <div class="info-card-header">
                            <i class="fas fa-map-location-dot"></i> Live GPS Tracking (Simulasi Rute Rill)
                        </div>
                        
                        <div style="position: relative;">
                            <div id="tracking-map"></div>
                            
                            <div style="position: absolute; bottom: 12px; left: 12px; z-index: 1000; background: rgba(255,255,255,0.92); border: 1px solid var(--card-border); padding: 8px 12px; border-radius: 8px; font-size: 11px; max-width: 90%; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                                <div style="font-weight: 700; color: var(--primary);"><i class="fas fa-satellite-dish text-success me-1 animate-pulse"></i> Sinyal GPS:</div>
                                <div id="gps-status" style="color: var(--text-dark); font-weight: 500;">Menghitung rute...</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Shipment Info -->
                <div class="col-lg-5">
                    <div class="info-card">
                        <div class="info-card-header">
                            <i class="fas fa-circle-info"></i> Rincian Pengiriman
                        </div>
                        
                        <table style="width: 100%; font-size: 13.5px; border-collapse: separate; border-spacing: 0 10px;">
                            <tr>
                                <td style="color: var(--text-muted); width: 130px;">ID Pengiriman</td>
                                <td style="font-weight: 700; color: var(--primary);">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted);">Penerima Kargo</td>
                                <td style="font-weight: 600; color: var(--text-dark);"><?= htmlspecialchars($order['customer_name']) ?></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); vertical-align: top;">Muatan</td>
                                <td style="color: var(--text-dark);"><?= htmlspecialchars($order['cargo_description']) ?> (<?= number_format($order['weight'], 1) ?> Ton / <?= number_format($order['volume'], 1) ?> CBM)</td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted);">Kota Asal</td>
                                <td style="font-weight: 600; color: #10b981;"><i class="fas fa-circle-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($order['origin']) ?></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted);">Kota Tujuan</td>
                                <td style="font-weight: 600; color: var(--accent);"><i class="fas fa-location-dot me-1" style="font-size: 10px;"></i> <?= htmlspecialchars($order['destination']) ?></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted);">Estimasi Tiba (ETA)</td>
                                <td style="color: var(--text-dark);"><?= date('d-m-Y H:i', strtotime($order['eta'])) ?></td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted);">Armada Truk</td>
                                <td style="color: var(--text-dark); font-weight: 500;">
                                    <?= $order['plate_number'] ? '<i class="fas fa-truck text-muted me-1"></i>' . htmlspecialchars($order['plate_number']) . ' (' . htmlspecialchars($order['vehicle_type']) . ')' : 'Belum Dialokasikan' ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted);">Driver Pengemudi</td>
                                <td style="color: var(--text-dark); font-weight: 500;">
                                    <?= $order['driver_name'] ? '<i class="fas fa-user-check text-muted me-1"></i>' . htmlspecialchars(explode(' ', $order['driver_name'])[0]) : 'Belum Dialokasikan' ?>
                                </td>
                            </tr>
                        </table>
                        
                        <?php if ($inspection && $inspection['status'] === 'failed'): ?>
                            <div class="alert alert-warning border-0 p-3 mt-3 mb-0" style="font-size: 12.5px; border-radius: 8px;">
                                <i class="fas fa-circle-exclamation text-warning me-1"></i>
                                <strong>Pengecekan Gagal:</strong> Armada mengalami kendala kelayakan fisik dan sedang dalam perbaikan mekanik di pool asal.
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($status === 'completed'): ?>
                            <div class="alert alert-success border-0 p-3 mt-3 mb-0 d-flex align-items-center gap-2" style="font-size: 12.5px; border-radius: 8px;">
                                <i class="fas fa-check-circle text-success fs-5"></i>
                                <div>
                                    <strong>Muatan Diterima:</strong> Pengiriman selesai. Bukti Surat Jalan (POD) telah diverifikasi sah.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- Section: About Us (Tentang Kami) -->
    <section class="section-padding bg-white" id="about">
        <div class="container">
            <div class="section-header">
                <h2>Tentang Kami</h2>
                <p>Mengenal lebih dekat visi keselamatan dan profesionalisme PT Tirta Nusa Persada</p>
            </div>
            
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h3 class="fw-bold text-primary mb-3">Keamanan Armada &amp; Ketepatan Waktu adalah Prioritas Kami</h3>
                    <p class="text-secondary" style="line-height: 1.7; font-size: 14.5px;">
                        PT Tirta Nusa Persada (TNP) merupakan perusahaan logistik terintegrasi yang melayani pengiriman kargo industri di seluruh wilayah Indonesia. Kami percaya bahwa pengiriman yang sukses berawal dari keselamatan armada dan kenyamanan pengemudi.
                    </p>
                    <p class="text-secondary" style="line-height: 1.7; font-size: 14.5px;">
                        Setiap armada yang bertugas wajib lolos **Pre-Trip Inspection (Uji Kelayakan Jalan)** yang mencakup pengecekan ban, rem, lampu, dan oli demi meniadakan kendala di jalan tol maupun rute ekspedisi nasional.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="about-card border-start border-4 border-primary">
                        <h4 class="fw-bold text-primary mb-3"><i class="fas fa-shield-halved me-2 text-accent"></i> Visi Keamanan Logistik</h4>
                        <p class="mb-0 text-secondary" style="font-size: 14px; line-height: 1.6;">
                            Menjadi penyedia jasa transportasi logistik nasional yang terpercaya dengan integritas operasional tinggi, didukung sistem pelacakan digital transparan dan standar keselamatan berkendara zero-accident.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Services (Layanan Kami) -->
    <section class="section-padding" id="services">
        <div class="container">
            <div class="section-header">
                <h2>Layanan Kami</h2>
                <p>Layanan operasional logistik digital terintegrasi untuk kebutuhan industri Anda</p>
            </div>
            
            <div class="row g-4">
                <!-- Service 1 -->
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-truck-moving"></i></div>
                        <h4 class="fw-bold fs-5 text-primary mb-3">Sewa Armada Truk (Charter)</h4>
                        <p class="text-secondary mb-0" style="font-size: 13.5px; line-height: 1.6;">Penyediaan sewa truk Tronton, Fuso, dan CDD untuk rute distribusi point-to-point antarkota.</p>
                    </div>
                </div>
                <!-- Service 2 -->
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-satellite-dish"></i></div>
                        <h4 class="fw-bold fs-5 text-primary mb-3">Pelacakan Rute Real-time</h4>
                        <p class="text-secondary mb-0" style="font-size: 13.5px; line-height: 1.6;">Transparansi penuh pengiriman kargo Anda melalui visualisasi peta rute GPS dan timeline digital.</p>
                    </div>
                </div>
                <!-- Service 3 -->
                <div class="col-md-4">
                    <div class="service-card">
                        <div class="service-icon"><i class="fas fa-user-shield"></i></div>
                        <h4 class="fw-bold fs-5 text-primary mb-3">Keamanan Standar Tinggi</h4>
                        <p class="text-secondary mb-0" style="font-size: 13.5px; line-height: 1.6;">Setiap keberangkatan dilindungi sertifikat layak jalan pre-trip untuk memperkecil risiko mogok.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Fleet (Armada Truk) -->
    <section class="section-padding bg-white" id="fleet">
        <div class="container">
            <div class="section-header">
                <h2>Armada Truk Kami</h2>
                <p>Jenis armada siap jalan yang dapat disesuaikan dengan kapasitas kargo Anda</p>
            </div>
            
            <div class="row g-4">
                <!-- Fleet 1 -->
                <div class="col-md-4">
                    <div class="fleet-card">
                        <div class="fleet-img-placeholder"><i class="fas fa-truck-front"></i></div>
                        <div class="fleet-info">
                            <h4 class="fw-bold fs-5 text-primary mb-2">Tronton Wingbox</h4>
                            <p class="text-secondary mb-3" style="font-size: 13px;">Sangat cocok untuk kargo pabrik volume besar dengan bongkar muatan samping yang cepat.</p>
                            <span class="badge bg-primary text-white" style="font-size: 11px;">Kapasitas: 15 Ton / 45 CBM</span>
                        </div>
                    </div>
                </div>
                <!-- Fleet 2 -->
                <div class="col-md-4">
                    <div class="fleet-card">
                        <div class="fleet-img-placeholder"><i class="fas fa-truck"></i></div>
                        <div class="fleet-info">
                            <h4 class="fw-bold fs-5 text-primary mb-2">Fuso Box</h4>
                            <p class="text-secondary mb-3" style="font-size: 13px;">Armada pengiriman andalan kargo tertutup berkeamanan tinggi antarkota dan antarprovinsi.</p>
                            <span class="badge bg-primary text-white" style="font-size: 11px;">Kapasitas: 8 Ton / 25 CBM</span>
                        </div>
                    </div>
                </div>
                <!-- Fleet 3 -->
                <div class="col-md-4">
                    <div class="fleet-card">
                        <div class="fleet-img-placeholder"><i class="fas fa-truck-flatbed"></i></div>
                        <div class="fleet-info">
                            <h4 class="fw-bold fs-5 text-primary mb-2">Colt Diesel Double (CDD)</h4>
                            <p class="text-secondary mb-3" style="font-size: 13px;">Truk bertenaga andalan untuk rute distribusi logistik perkotaan dan pergudangan medium.</p>
                            <span class="badge bg-primary text-white" style="font-size: 11px;">Kapasitas: 4 Ton / 14 CBM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Contact (Hubungi Kami) -->
    <section class="section-padding" id="contact">
        <div class="container">
            <div class="section-header">
                <h2>Hubungi Kami</h2>
                <p>Hubungi kantor operasional kami untuk penawaran harga dan sewa armada</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="fs-2 text-primary mb-2"><i class="fas fa-map-location-dot"></i></div>
                        <h5 class="fw-bold mb-2">Kantor Pusat</h5>
                        <p class="text-secondary" style="font-size: 13.5px; line-height: 1.5;">Gedung TNP Logistik, Jln. Jenderal Sudirman Kav. 52-53, Jakarta, Indonesia</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="fs-2 text-primary mb-2"><i class="fas fa-envelope-open-text"></i></div>
                        <h5 class="fw-bold mb-2">Surel Resmi</h5>
                        <p class="text-secondary" style="font-size: 13.5px; line-height: 1.5;">info@tirtanusapersada.com<br>support@tnp-logistik.co.id</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="fs-2 text-primary mb-2"><i class="fas fa-headset"></i></div>
                        <h5 class="fw-bold mb-2">Telepon Operasional</h5>
                        <p class="text-secondary" style="font-size: 13.5px; line-height: 1.5;">+62 (21) 555-1234<br>+62 (21) 555-5678</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <div style="font-size: 15px; font-weight: 700; color: white;" class="mb-2">
                PT Tirta Nusa Persada
            </div>
            <p style="font-size: 12.5px; color: #64748b; margin-bottom: 20px;">Layanan Digital Logistics &amp; Expedition Terintegrasi &mdash; Indonesia</p>
            <div style="font-size: 11px; color: #475569;">
                &copy; <?= date('Y') ?> PT Tirta Nusa Persada (TNP). Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS Map script -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <?php if ($order): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll to tracking section on search
        window.location.hash = '#track';

        const cities = {
            'jakarta': [-6.2088, 106.8456],
            'bandung': [-6.9175, 107.6191],
            'surabaya': [-7.2575, 112.7521],
            'cirebon': [-6.7218, 108.5560],
            'semarang': [-6.9667, 110.4167],
            'tangerang': [-6.1783, 106.6319],
            'bekasi': [-6.2383, 106.9756],
            'karawang': [-6.3024, 107.3082],
            'bogor': [-6.5971, 106.8060],
            'depok': [-6.4025, 106.7942],
            'yogyakarta': [-7.7956, 110.3695],
            'solo': [-7.5755, 110.8243],
            'malang': [-7.9839, 112.6214]
        };

        const originName = "<?= strtolower(trim($order['origin'])) ?>";
        const destName = "<?= strtolower(trim($order['destination'])) ?>";

        // Coordinates from order or city fallbacks
        const originCoords = [
            <?= !empty($order['origin_latitude']) ? (float)$order['origin_latitude'] : '(cities[originName] ? cities[originName][0] : -6.2088)' ?>,
            <?= !empty($order['origin_longitude']) ? (float)$order['origin_longitude'] : '(cities[originName] ? cities[originName][1] : 106.8456)' ?>
        ];
        const destCoords = [
            <?= !empty($order['destination_latitude']) ? (float)$order['destination_latitude'] : '(cities[destName] ? cities[destName][0] : -6.9175)' ?>,
            <?= !empty($order['destination_longitude']) ? (float)$order['destination_longitude'] : '(cities[destName] ? cities[destName][1] : 107.6191)' ?>
        ];

        const midpoint = [
            (originCoords[0] + destCoords[0]) / 2,
            (originCoords[1] + destCoords[1]) / 2
        ];

        // Initialize Light theme Map (Voyager style)
        const map = L.map('tracking-map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView(midpoint, 7);

        // CartoDB Voyager Light tiles - matches corporate light website
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            maxZoom: 19
        }).addTo(map);

        // Origin Marker (Green)
        L.circleMarker(originCoords, {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.8,
            radius: 7
        }).addTo(map).bindPopup("<b>Asal:</b> " + "<?= htmlspecialchars($order['origin']) ?>");

        // Destination Marker (Red)
        L.circleMarker(destCoords, {
            color: '#dc2626',
            fillColor: '#dc2626',
            fillOpacity: 0.8,
            radius: 7
        }).addTo(map).bindPopup("<b>Tujuan:</b> " + "<?= htmlspecialchars($order['destination']) ?>");

        // Route line (dashed origin to destination)
        L.polyline([originCoords, destCoords], {
            color: '#3b82f6',
            weight: 3,
            dashArray: '6, 6',
            opacity: 0.5
        }).addTo(map);

        // Determine initial position
        const status = "<?= $order['status'] ?>";
        let initialTruckCoords = originCoords;
        let gpsText = "";

        if (status === 'pending') {
            initialTruckCoords = originCoords;
            gpsText = "Standby: Menunggu alokasi di pool " + "<?= htmlspecialchars($order['origin']) ?>";
        } else if (status === 'allocated') {
            initialTruckCoords = originCoords;
            gpsText = "Standby: Armada terparkir di pool " + "<?= htmlspecialchars($order['origin']) ?>" + ". Menunggu uji layak.";
        } else if (status === 'inspect_failed') {
            initialTruckCoords = originCoords;
            gpsText = "Warning: Truk gagal uji kelayakan jalan. Truk masuk bengkel di " + "<?= htmlspecialchars($order['origin']) ?>";
        } else if (status === 'ready') {
            initialTruckCoords = originCoords;
            gpsText = "Siap Jalan: Cek fisik lolos. Surat Jalan siap. Bersiap berangkat di " + "<?= htmlspecialchars($order['origin']) ?>";
        } else if (status === 'loading') {
            initialTruckCoords = originCoords;
            gpsText = "Sedang Muat: Kargo sedang dimuat ke armada di pool/gudang asal " + "<?= htmlspecialchars($order['origin']) ?>";
        } else if (status === 'in_transit') {
            <?php $latest_loc = $this->Order_model->get_latest_location($order['id']); ?>
            initialTruckCoords = <?= $latest_loc ? '['.(float)$latest_loc['latitude'].', '.(float)$latest_loc['longitude'].']' : 'originCoords' ?>;
            gpsText = "Dalam Rute: Truk sedang dalam perjalanan menuju " + "<?= htmlspecialchars($order['destination']) ?>";
        } else if (status === 'arrived') {
            initialTruckCoords = destCoords;
            gpsText = "Tiba: Truk sampai di tujuan " + "<?= htmlspecialchars($order['destination']) ?>" + ". Sedang proses bongkar.";
        } else if (status === 'pod_submitted') {
            initialTruckCoords = destCoords;
            gpsText = "POD Terkirim: Barang diterima. Bukti Surat Jalan telah diupload. Menunggu verifikasi kantor.";
        } else {
            initialTruckCoords = destCoords;
            gpsText = "Selesai: Pengiriman diverifikasi sah. Kargo selesai serah-terima.";
        }

        // Custom marker icon
        const truckIcon = L.divIcon({
            html: '<div style="background: #2563eb; border: 2px solid #fff; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 0 10px rgba(37,99,235,0.5);"><i class="fas fa-truck" style="font-size: 12px;"></i></div>',
            className: 'custom-truck-marker',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        // Add truck marker to map
        let truckMarker = L.marker(initialTruckCoords, { icon: truckIcon }).addTo(map)
            .bindPopup("<b>GPS Info:</b> " + gpsText);

        document.getElementById('gps-status').innerText = gpsText;

        // Draw static path if there are already recorded location logs
        <?php $breadcrumbs = $this->Order_model->get_route_breadcrumbs($order['id']); ?>
        let historyPolyline = null;
        <?php if (!empty($breadcrumbs)): ?>
            const initialPathCoords = [
                <?php foreach ($breadcrumbs as $b): ?>
                    [<?= (float)$b['latitude'] ?>, <?= (float)$b['longitude'] ?>],
                <?php endforeach; ?>
                initialTruckCoords
            ];
            historyPolyline = L.polyline(initialPathCoords, {
                color: '#10b981',
                weight: 4,
                opacity: 0.85
            }).addTo(map);
        <?php endif; ?>

        // Auto zoom fit
        const bounds = L.latLngBounds([originCoords, destCoords]);
        map.fitBounds(bounds, { padding: [50, 50] });

        // GPS Real-time Polling Function (uses Fetch API, no dependencies)
        function updateLiveLocation() {
            fetch('<?= base_url("tracking/get-live-location/" . $order["id"]) ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.current_latitude && data.current_longitude) {
                        const newPos = [data.current_latitude, data.current_longitude];
                        
                        // Update marker position
                        if (truckMarker) {
                            truckMarker.setLatLng(newPos);
                        } else {
                            truckMarker = L.marker(newPos, { icon: truckIcon }).addTo(map);
                        }
                        
                        // Update GPS status text
                        let liveText = "Dalam Rute: Truk aktif melaju. Posisi: " + data.current_latitude.toFixed(4) + ", " + data.current_longitude.toFixed(4);
                        document.getElementById('gps-status').innerHTML = '<i class="fas fa-satellite-dish text-success me-1 animate-pulse"></i> ' + liveText;
                        truckMarker.setPopupContent("<b>GPS Info:</b> " + liveText);

                        // Redraw polyline path based on breadcrumbs
                        if (data.breadcrumbs && data.breadcrumbs.length > 0) {
                            const pathCoords = data.breadcrumbs.map(c => [c.latitude, c.longitude]);
                            pathCoords.push(newPos);
                            
                            if (historyPolyline) {
                                historyPolyline.setLatLngs(pathCoords);
                            } else {
                                historyPolyline = L.polyline(pathCoords, {
                                    color: '#10b981',
                                    weight: 4,
                                    opacity: 0.85
                                }).addTo(map);
                            }
                        }
                    }
                })
                .catch(err => console.error("Tracking update error:", err));
        }

        // Activate polling every 15 seconds if in_transit
        if (status === 'in_transit') {
            updateLiveLocation();
            setInterval(updateLiveLocation, 15000);
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>
