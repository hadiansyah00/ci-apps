<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (!empty($orders)): ?>
<div class="table-responsive">
    <table class="table-dark-custom">
        <thead>
            <tr>
                <th>ID Order</th>
                <th>Pelanggan</th>
                <th>Kargo / Muatan</th>
                <th>Tonase &amp; Vol</th>
                <th>Rute Asal &rarr; Tujuan</th>
                <th>Driver &amp; Armada</th>
                <th>Status</th>
                <th style="text-align: right; min-width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $CI =& get_instance();
            $CI->load->model('Order_model');
            foreach ($orders as $order): 
                $status_info = $CI->Order_model->get_status_label($order['status']);
            ?>
            <tr>
                <td style="font-weight: 700; color: #a5b4fc;">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td style="font-weight: 600; color: #fff;"><?= htmlspecialchars($order['customer_name']) ?></td>
                <td style="font-size: 13px; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($order['cargo_description']) ?>">
                    <?= htmlspecialchars($order['cargo_description']) ?>
                </td>
                <td style="font-size: 13px;"><?= number_format($order['weight'], 1) ?> T / <?= number_format($order['volume'], 1) ?> CBM</td>
                <td style="font-size: 13px;">
                    <strong><?= htmlspecialchars($order['origin']) ?></strong> 
                    <i class="fas fa-arrow-right mx-1 text-primary-light" style="font-size: 10px;"></i> 
                    <strong><?= htmlspecialchars($order['destination']) ?></strong>
                </td>
                <td>
                    <?php if ($order['driver_name']): ?>
                    <div style="font-size: 13px; color: #fff; font-weight: 500;"><?= htmlspecialchars(explode(' ', $order['driver_name'])[0]) ?></div>
                    <div style="font-size: 11px; color: var(--text-muted);"><i class="fas fa-truck me-1"></i><?= htmlspecialchars($order['plate_number']) ?></div>
                    <?php else: ?>
                    <span style="font-size: 12px; color: var(--text-muted); font-style: italic;">Belum ditugaskan</span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge-status <?= $status_info['class'] ?>" style="font-size: 11px; font-weight: 700;">
                        <?= $status_info['label'] ?>
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                        <!-- View details -->
                        <a href="<?= base_url('admin/orders/view/' . $order['id']) ?>" class="btn-sm-icon btn-edit" title="Detail Order">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Dispatch Assignment -->
                        <?php if ($order['status'] === 'pending' && can('dispatch.assign')): ?>
                        <a href="<?= base_url('admin/orders/dispatch/' . $order['id']) ?>" class="btn-sm-icon btn-success-sm" title="Assign Driver &amp; Armada" style="background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #818cf8;">
                            <i class="fas fa-truck-ramp-box"></i>
                        </a>
                        <?php endif; ?>

                        <!-- Uji Kelayakan Cek Fisik (Checker) -->
                        <?php if (in_array($order['status'], ['allocated', 'inspect_failed']) && can('inspections.create')): ?>
                        <a href="<?= base_url('admin/inspections/check/' . $order['id']) ?>" class="btn-sm-icon btn-success-sm" title="Lakukan Uji Kelayakan" style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b;">
                            <i class="fas fa-clipboard-check"></i>
                        </a>
                        <?php endif; ?>

                        <!-- Verifikasi Pemuatan & Segel (Checker) -->
                        <?php if ($order['status'] === 'loading' && can('loading.verify')): ?>
                        <a href="<?= base_url('admin/inspections/verify-loading/' . $order['id']) ?>" class="btn-sm-icon btn-success-sm" title="Verifikasi Pemuatan &amp; Segel" style="background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3); color: #a78bfa;">
                            <i class="fas fa-box-open"></i>
                        </a>
                        <?php endif; ?>

                        <!-- Print Surat Jalan -->
                        <?php if (in_array($order['status'], ['ready', 'in_transit', 'arrived', 'pod_submitted', 'completed']) && can('dispatch.print-sj')): ?>
                        <a href="<?= base_url('admin/orders/print-sj/' . $order['id']) ?>" target="_blank" class="btn-sm-icon btn-warning-sm" title="Cetak Surat Jalan">
                            <i class="fas fa-print"></i>
                        </a>
                        <?php endif; ?>

                        <!-- Edit (only if Pending) -->
                        <?php if ($order['status'] === 'pending' && can('orders.edit')): ?>
                        <a href="<?= base_url('admin/orders/edit/' . $order['id']) ?>" class="btn-sm-icon btn-edit" title="Edit Detail">
                            <i class="fas fa-pen"></i>
                        </a>
                        <?php endif; ?>

                        <!-- Delete (only if Pending, Completed, Canceled) -->
                        <?php if (in_array($order['status'], ['pending', 'completed', 'canceled']) && can('orders.delete')): ?>
                        <a href="#" class="btn-sm-icon btn-delete" title="Hapus Order" onclick="return confirmDelete('<?= base_url('admin/orders/delete/' . $order['id']) ?>', 'Order #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>')">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
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
    <i class="fas fa-dolly"></i>
    <p>Tidak ada order pengiriman ditemukan.</p>
</div>
<?php endif; ?>
