<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #000;
            background: #fff;
            padding: 20px;
            font-size: 13px;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .logo-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .subtitle {
            font-size: 11px;
            color: #555;
            margin: 3px 0 0 0;
        }

        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            text-decoration: underline;
            margin: 20px 0 10px 0;
            letter-spacing: 1px;
        }

        .doc-number {
            text-align: center;
            font-size: 12px;
            font-family: monospace;
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .cargo-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }

        .cargo-table th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: 700;
        }

        .cargo-table td {
            border-bottom: 1px solid #ddd;
            padding: 10px 8px;
        }

        .signatures-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .signatures-table td {
            text-align: center;
            width: 33.3%;
            padding-bottom: 80px;
            vertical-align: top;
        }

        .signatures-name {
            font-weight: 700;
            text-decoration: underline;
        }

        .print-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }

        @media print {
            .print-btn {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

<!-- Print Control -->
<div style="text-align: right;">
    <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Cetak Surat Jalan</button>
</div>

<!-- Header -->
<table class="header-table">
    <tr>
        <td style="width: 60%;">
            <div class="logo-title">PT TIRTA NUSA PERSADA (TNP)</div>
            <div class="subtitle">Jasa Transportasi &amp; Distribusi Logistik Nasional</div>
            <div class="subtitle">Pool &amp; Gudang: Jl. Raya Bekasi KM 24, Cakung, Jakarta Timur</div>
            <div class="subtitle">Telp: (021) 8899-7711 | Email: ops@tirtanusapersada.co.id</div>
        </td>
        <td style="width: 40%; text-align: right; vertical-align: bottom;">
            <div style="font-weight: bold; font-size: 14px;">SALINAN DOKUMEN</div>
            <div class="subtitle">Tanggal Cetak: <?= date('d-m-Y H:i') ?></div>
        </td>
    </tr>
</table>

<!-- Doc Title -->
<div class="doc-title">SURAT JALAN PENGIRIMAN</div>
<div class="doc-number">Nomor: SJ-TNP-<?= date('Y', strtotime($order['created_at'])) ?>-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></div>

<!-- Info Grid -->
<table class="info-table">
    <tr>
        <td style="width: 15%; color: #555;">Pelanggan</td>
        <td style="width: 35%;">: <strong><?= htmlspecialchars($order['customer_name']) ?></strong></td>
        <td style="width: 15%; color: #555;">No. Plat Truk</td>
        <td style="width: 35%;">: <strong><?= htmlspecialchars($order['plate_number']) ?></strong> (<?= htmlspecialchars($order['vehicle_type']) ?>)</td>
    </tr>
    <tr>
        <td style="color: #555;">Kota Asal</td>
        <td>: <?= htmlspecialchars($order['origin']) ?></td>
        <td style="color: #555;">Driver Utama</td>
        <td>: <strong><?= htmlspecialchars($order['driver_name']) ?></strong></td>
    </tr>
    <tr>
        <td style="color: #555;">Kota Tujuan</td>
        <td>: <strong><?= htmlspecialchars($order['destination']) ?></strong></td>
        <td style="color: #555;">Estimasi ETA</td>
        <td>: <?= date('d-m-Y H:i', strtotime($order['eta'])) ?></td>
    </tr>
</table>

<!-- Cargo Grid -->
<table class="cargo-table">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 60%;">Rincian Deskripsi Barang / Muatan</th>
            <th style="width: 15%; text-align: center;">Berat (Ton)</th>
            <th style="width: 15%; text-align: center;">Volume (CBM)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td style="font-weight: bold; line-height: 1.5;"><?= nl2br(htmlspecialchars($order['cargo_description'])) ?></td>
            <td style="text-align: center;"><?= number_format($order['weight'], 1) ?> Ton</td>
            <td style="text-align: center;"><?= number_format($order['volume'], 1) ?> CBM</td>
        </tr>
    </tbody>
</table>

<!-- Notes -->
<div style="font-size: 11px; border: 1px dashed #000; padding: 10px; border-radius: 6px; margin-bottom: 30px;">
    <strong>Catatan Penting:</strong>
    <ol style="margin: 5px 0 0 15px; padding: 0;">
        <li>Penerima wajib memeriksa kondisi segel dan fisik barang saat serah terima selesai dilakukan.</li>
        <li>Tanda tangan dan stempel basah penerima di kolom penerima di bawah ini sah sebagai bukti serah terima kargo (*Proof of Delivery).</li>
        <li>Harap lembar Surat Jalan asli ini disimpan dan diserahkan kembali kepada driver bertugas.</li>
    </ol>
</div>

<!-- Signatures -->
<table class="signatures-table">
    <tr>
        <td>
            <div>Diserahkan Oleh,</div>
            <div style="margin-top: 80px;" class="signatures-name">Staff Logistik TNP</div>
            <div style="font-size: 10px; color: #555;">Admin Gudang</div>
        </td>
        <td>
            <div>Pengemudi (Driver),</div>
            <div style="margin-top: 80px;" class="signatures-name"><?= htmlspecialchars($order['driver_name']) ?></div>
            <div style="font-size: 10px; color: #555;">Crew Armada</div>
        </td>
        <td>
            <div>Diterima Dengan Baik Oleh,</div>
            <div style="margin-top: 80px;">_______________________</div>
            <div style="font-size: 10px; color: #555;">Tanda Tangan &amp; Stempel Basah</div>
        </td>
    </tr>
</table>

</body>
</html>
