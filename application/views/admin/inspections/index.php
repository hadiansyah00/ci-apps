<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header" data-aos="fade-down">
    <h1><i class="fas fa-clipboard-check me-2" style="color: var(--success)"></i>Riwayat Cek Fisik Armada</h1>
    <div class="breadcrumb-custom">
        <a href="<?= base_url('admin/dashboard') ?>"><i class="fas fa-home"></i></a>
        <span class="separator">/</span><span>Riwayat Cek Fisik</span>
    </div>
</div>

<!-- Filters Card -->
<div class="content-card mb-4" data-aos="fade-up">
    <div class="content-card-body" style="padding: 18px 24px;">
        <form id="filter-form" method="GET" action="<?= base_url('admin/inspections') ?>" class="row g-3 align-items-center">
            <!-- Search -->
            <div class="col-md-5">
                <div class="input-wrapper">
                    <i class="fas fa-search input-icon" style="top: 50%;"></i>
                    <input type="text" name="search" class="form-control-custom" 
                           placeholder="Cari plat nomor, pelanggan, rute, atau checker..." 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>" style="padding-left: 42px;">
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-4">
                <select name="status" class="form-control-custom" style="background: #1e293b;">
                    <option value="">-- Semua Status Kelayakan --</option>
                    <option value="passed" <?= ($filters['status'] ?? '') === 'passed' ? 'selected' : '' ?>>PASSED (Lolos Uji)</option>
                    <option value="failed" <?= ($filters['status'] ?? '') === 'failed' ? 'selected' : '' ?>>FAILED (Gagal Uji)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-3" style="display: flex; gap: 8px;">
                <button type="submit" class="btn-primary-custom" style="flex: 1; justify-content: center;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('admin/inspections') ?>" id="reset-btn" class="btn-primary-custom" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc; padding: 10px 14px;">
                    <i class="fas fa-arrows-rotate"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Inspections List Card -->
<div class="content-card" data-aos="fade-up" data-aos-delay="100">
    <div class="content-card-header">
        <div class="content-card-title">
            <i class="fas fa-history" style="color: var(--success)"></i>
            Daftar Uji Kelayakan Jalan
        </div>
    </div>
    <div class="content-card-body" style="padding: 0;" id="inspections-table-container">
        <?php $this->load->view('admin/inspections/table', ['inspections' => $inspections, 'pagination' => $pagination ?? '']); ?>
    </div>
</div>

<!-- AJAX Pagination and Filter script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercept pagination clicks inside container
    $(document).on('click', '#inspections-table-container .pagination-custom a', function(e) {
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
        loadTable('<?= base_url("admin/inspections") ?>');
    });

    function loadTable(url) {
        $('#inspections-table-container').css('opacity', '0.4');
        
        $.get(url, function(data) {
            $('#inspections-table-container').html(data);
            $('#inspections-table-container').css('opacity', '1');
            window.history.pushState({path: url}, '', url);
        }).fail(function() {
            toastr.error('Gagal memperbarui daftar cek fisik.');
            $('#inspections-table-container').css('opacity', '1');
        });
    }
});
</script>
