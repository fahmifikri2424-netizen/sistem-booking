<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle mb-4">
    <h1 class="fw-bold"><i class="bi bi-wallet2 text-primary me-2"></i>Laporan Pembayaran</h1>
    <nav>
        <ol class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active">Pembayaran</li>
        </ol>
    </nav>
</div>

<!-- Statistik Singkat (Opsional) -->
<?php
    $totalPendapatan = 0;
    $totalSukses = 0;
    foreach ($payments as $p) {
        if ($p['status'] == 'sukses') {
            $totalPendapatan += $p['jumlah'];
            $totalSukses++;
        }
    }
?>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 small text-uppercase fw-bold mb-1">Total Pendapatan (Sukses)</p>
                        <h4 class="fw-bold mb-0">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h4>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-cash-stack"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100 bg-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-white-50 small text-uppercase fw-bold mb-1">Transaksi Sukses</p>
                        <h4 class="fw-bold mb-0"><?= $totalSukses ?> Transaksi</h4>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        
        <!-- Filter Form -->
        <form method="get" action="<?= base_url('admin/payments') ?>" class="row g-2 align-items-center mb-4">
            <div class="col-auto">
                <label class="form-label mb-0 fw-semibold text-muted small">Filter Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm bg-light border-0">
                    <option value="" <?= $filterStatus == '' ? 'selected' : '' ?>>Semua Transaksi</option>
                    <option value="sukses"  <?= $filterStatus == 'sukses'  ? 'selected' : '' ?>>Sukses / Lunas</option>
                    <option value="pending" <?= $filterStatus == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="gagal"   <?= $filterStatus == 'gagal'   ? 'selected' : '' ?>>Gagal / Batal</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary px-3">Terapkan</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Metode</th>
                        <th>Jumlah (Rp)</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($payments as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <?= esc($item['kode_booking']) ?>
                                </span>
                            </td>
                            <td class="fw-semibold"><?= esc($item['nama_pelanggan']) ?></td>
                            <td><?= esc($item['nama_service']) ?></td>
                            <td>
                                <?php if ($item['payment_type']): ?>
                                    <span class="badge bg-secondary text-uppercase"><?= esc(str_replace('_', ' ', $item['payment_type'])) ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-success">
                                <?= number_format($item['jumlah'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php if ($item['status'] == 'sukses'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Sukses</span>
                                <?php elseif ($item['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Gagal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['payment_time']): ?>
                                    <?= date('d M Y, H:i', strtotime($item['payment_time'])) ?>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Tombol detail sekadar melihat ID transaksi dari midtrans -->
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $item['id_payment'] ?>">
                                    <i class="bi bi-info-circle"></i>
                                </button>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="modalDetail<?= $item['id_payment'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Detail Transaksi Midtrans</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Transaction ID:</span>
                                                        <span class="font-monospace fw-bold"><?= esc($item['transaction_id'] ?? '-') ?></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Kode Booking:</span>
                                                        <span><?= esc($item['kode_booking']) ?></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Customer:</span>
                                                        <span><?= esc($item['nama_pelanggan']) ?></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span class="text-muted">Nominal:</span>
                                                        <span class="fw-bold text-success">Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<?= $this->endSection() ?>
