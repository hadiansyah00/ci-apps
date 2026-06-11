<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (!empty($inspections)): ?>
<div class="table-responsive">
    <table class="table-dark-custom">
        <thead>
            <tr>
                <th>Tanggal Cek</th>
                <th>No. Plat</th>
                <th>Pelanggan</th>
                <th>Rute Pengiriman</th>
                <th>Pemeriksa (Checker)</th>
                <th>Status Kelayakan</th>
                <th>Catatan Fisik</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inspections as $ins): ?>
            <tr>
                <td style="font-size: 13px; color: #fff; font-weight: 500;"><?= date('d-m-Y H:i', strtotime($ins['created_at'])) ?></td>
                <td style="font-weight: 700; color: #a5b4fc; font-size: 14px;"><?= htmlspecialchars($ins['plate_number']) ?></td>
                <td><?= htmlspecialchars($ins['customer_name'] ?? 'N/A') ?></td>
                <td style="font-size: 13px;">
                    <?= htmlspecialchars($ins['origin'] ?? '') ?> 
                    <i class="fas fa-arrow-right mx-1 text-primary-light" style="font-size: 10px;"></i>
                    <?= htmlspecialchars($ins['destination'] ?? '') ?>
                </td>
                <td><?= htmlspecialchars($ins['checker_name'] ?? 'N/A') ?></td>
                <td>
                    <span class="badge-status <?= $ins['status'] === 'passed' ? 'badge-active' : 'badge-inactive' ?>" style="font-size: 11px; font-weight: 700;">
                        <?= $ins['status'] === 'passed' ? 'PASSED (Lolos)' : 'FAILED (Gagal)' ?>
                    </span>
                </td>
                <td style="font-size: 13px; font-style: italic; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($ins['notes'] ?? '-') ?>">
                    <?= htmlspecialchars($ins['notes'] ? '"' . $ins['notes'] . '"' : '-') ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Pagination links -->
<?php if (!empty($pagination)): ?>
<div class="mt-4 pb-3 d-flex justify-content-center">
    <?= $pagination ?>
</div>
<?php endif; ?>

<?php else: ?>
<div class="empty-state" style="padding: 48px 24px;">
    <i class="fas fa-clipboard-check"></i>
    <p>Belum ada data riwayat cek fisik kelayakan jalan.</p>
</div>
<?php endif; ?>
