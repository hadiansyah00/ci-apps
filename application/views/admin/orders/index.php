<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-dolly me-2" style="color: var(--warning)"></i>Order Logistik</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span><span>Order Logistik</span>
    </div>
</div>

<!-- Filters Card -->
<div class="content-card mb-4" data-aos="fade-up">
    <div class="content-card-body" style="padding: 18px 24px;">
        <form id="filter-form" method="GET" action="<?= base_url('admin/orders') ?>" class="row g-3 align-items-center">
            <!-- Search -->
            <div class="col-md-5">
                <div class="input-wrapper">
                    <i class="fas fa-search input-icon" style="top: 50%;"></i>
                    <input type="text" name="search" class="form-control-custom" 
                           placeholder="Cari nama pelanggan, asal, atau tujuan..." 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>" style="padding-left: 42px;">
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-4">
                <select name="status" class="form-control-custom" style="background: #1e293b;">
                    <option value="">-- Semua Status Perjalanan --</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending (Belum Di-Assign)</option>
                    <option value="allocated" <?= ($filters['status'] ?? '') === 'allocated' ? 'selected' : '' ?>>Allocated (Sudah Di-Assign)</option>
                    <option value="inspect_failed" <?= ($filters['status'] ?? '') === 'inspect_failed' ? 'selected' : '' ?>>Uji Kelayakan Gagal</option>
                    <option value="ready" <?= ($filters['status'] ?? '') === 'ready' ? 'selected' : '' ?>>Siap Jalan (Surat Jalan Aktif)</option>
                    <option value="loading" <?= ($filters['status'] ?? '') === 'loading' ? 'selected' : '' ?>>Sedang Muat Kargo (Loading)</option>
                    <option value="in_transit" <?= ($filters['status'] ?? '') === 'in_transit' ? 'selected' : '' ?>>Dalam Perjalanan (In-Transit)</option>
                    <option value="arrived" <?= ($filters['status'] ?? '') === 'arrived' ? 'selected' : '' ?>>Tiba di Tujuan</option>
                    <option value="pod_submitted" <?= ($filters['status'] ?? '') === 'pod_submitted' ? 'selected' : '' ?>>POD Dikirim (Menunggu Verifikasi)</option>
                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Selesai (POD Verified)</option>
                    <option value="canceled" <?= ($filters['status'] ?? '') === 'canceled' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-3" style="display: flex; gap: 8px;">
                <button type="submit" class="btn-primary-custom" style="flex: 1; justify-content: center;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('admin/orders') ?>" id="reset-btn" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc; padding: 10px 14px;">
                    <i class="fas fa-arrows-rotate"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders List Card -->
<div class="content-card" data-aos="fade-up" data-aos-delay="100">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-list" style="color: var(--warning)"></i>
            Daftar Order Pengiriman
        </div>
        <?php if (can('orders.create')): ?>
        <a href="<?= base_url('admin/orders/create') ?>" class="btn-primary-custom">
            <i class="fas fa-plus"></i> Buat Order Baru
        </a>
        <?php endif; ?>
    </div>
    <div class="content-card-body" style="padding: 0;" id="orders-table-container">
        <?php $this->load->view('admin/orders/table', ['orders' => $orders, 'pagination' => $pagination ?? '']); ?>
    </div>
</div>

<!-- AJAX Pagination and Filter script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercept pagination clicks inside container
    $(document).on('click', '#orders-table-container .pagination-custom a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        loadTable(url);
    });

    // Intercept filter form submit
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action') + '?' + $(this).serialize();
        loadTable(url);
    });

    // Reset filters
    $('#reset-btn').on('click', function(e) {
        e.preventDefault();
        $('#filter-form')[0].reset();
        $('#filter-form select').val('');
        $('#filter-form input').val('');
        loadTable('<?= base_url("admin/orders") ?>');
    });

    function loadTable(url) {
        $('#orders-table-container').css('opacity', '0.4');
        
        $.get(url, function(data) {
            $('#orders-table-container').html(data);
            $('#orders-table-container').css('opacity', '1');
            window.history.pushState({path: url}, '', url);
        }).fail(function() {
            toastr.error('Gagal memperbarui daftar order.');
            $('#orders-table-container').css('opacity', '1');
        });
    }
});
</script>
