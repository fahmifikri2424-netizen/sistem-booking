<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Filter Status -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" action="<?= base_url('staff/riwayat') ?>" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label mb-1 fw-semibold">
                    <i class="bi bi-funnel me-1"></i>Filter Status
                </label>
                <select name="status" class="form-select">
                    <option value="" <?= $filterStatus == '' ? 'selected' : '' ?>>-- Semua Status --</option>
                    <option value="pending"       <?= $filterStatus == 'pending'       ? 'selected' : '' ?>>Pending</option>
                    <option value="dikonfirmasi"  <?= $filterStatus == 'dikonfirmasi'  ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="selesai"       <?= $filterStatus == 'selesai'       ? 'selected' : '' ?>>Selesai</option>
                    <option value="batal"         <?= $filterStatus == 'batal'         ? 'selected' : '' ?>>Batal</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
                <a href="<?= base_url('staff/riwayat') ?>" class="btn btn-outline-secondary ms-1">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Statistik -->
<?php
$totalSelesai = 0;
$totalBatal   = 0;
$totalPending = 0;
$totalDikonfirmasi = 0;
$totalPenghasilan  = 0;
foreach ($riwayat as $r) {
    if ($r['status_booking'] == 'selesai') {
        $totalSelesai++;
        $totalPenghasilan += $r['harga'];
    } elseif ($r['status_booking'] == 'batal') {
        $totalBatal++;
    } elseif ($r['status_booking'] == 'pending') {
        $totalPending++;
    } elseif ($r['status_booking'] == 'dikonfirmasi') {
        $totalDikonfirmasi++;
    }
}
?>

<?php if (empty($filterStatus)): // Hanya tampilkan statistik di tampilan semua ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 bg-success bg-opacity-10">
            <div class="card-body py-3">
                <i class="bi bi-patch-check fs-2 text-success mb-1 d-block"></i>
                <h5 class="fw-bold text-success mb-0"><?= $totalSelesai ?></h5>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 bg-warning bg-opacity-10">
            <div class="card-body py-3">
                <i class="bi bi-hourglass-split fs-2 text-warning mb-1 d-block"></i>
                <h5 class="fw-bold text-warning mb-0"><?= $totalPending ?></h5>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 bg-info bg-opacity-10">
            <div class="card-body py-3">
                <i class="bi bi-check-circle fs-2 text-info mb-1 d-block"></i>
                <h5 class="fw-bold text-info mb-0"><?= $totalDikonfirmasi ?></h5>
                <small class="text-muted">Dikonfirmasi</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 bg-danger bg-opacity-10">
            <div class="card-body py-3">
                <i class="bi bi-x-circle fs-2 text-danger mb-1 d-block"></i>
                <h5 class="fw-bold text-danger mb-0"><?= $totalBatal ?></h5>
                <small class="text-muted">Batal</small>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Riwayat -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">
            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pekerjaan Saya
        </h5>
        <span class="badge bg-primary"><?= count($riwayat) ?> data</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($riwayat)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-archive fs-1 mb-3 d-block text-secondary"></i>
                <h6 class="fw-semibold">Belum Ada Riwayat</h6>
                <p class="mb-0 small">Tidak ada data riwayat pekerjaan yang cocok dengan filter.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableRiwayat">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat as $i => $item): ?>
                        <tr>
                            <td class="ps-3"><?= $i + 1 ?></td>
                            <td>
                                <span class="badge bg-secondary font-monospace">
                                    <?= esc($item['kode_booking'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                         style="width:32px;height:32px;font-size:12px;">
                                        <?= strtoupper(substr($item['nama_customer'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold lh-sm small"><?= esc($item['nama_customer']) ?></div>
                                        <div class="text-muted" style="font-size:11px;">
                                            <i class="bi bi-telephone me-1"></i><?= esc($item['telepon']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="small"><?= esc($item['nama_service']) ?></td>
                            <td class="small text-success fw-semibold">
                                Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                            </td>
                            <td class="small">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                <?= date('d M Y', strtotime($item['tanggal'])) ?>
                            </td>
                            <td class="small">
                                <span class="badge bg-light text-dark border">
                                    <?= substr($item['jam_mulai'], 0, 5) ?> – <?= substr($item['jam_selesai'], 0, 5) ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?= esc($item['catatan'] ?? '-') ?>
                                <?php if (!empty($item['rating'])): ?>
                                    <div class="mt-2 p-2 bg-light rounded border border-warning">
                                        <div class="text-warning small mb-1">
                                            <?php for($j=1; $j<=5; $j++): ?>
                                                <?= $j <= $item['rating'] ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>' ?>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="fst-italic" style="font-size: 11px;">"<?= esc($item['komentar']) ?>"</div>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['status_booking'] == 'pending'): ?>
                                    <span class="badge rounded-pill bg-warning text-dark">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                <?php elseif ($item['status_booking'] == 'dikonfirmasi'): ?>
                                    <span class="badge rounded-pill bg-info text-dark">
                                        <i class="bi bi-check-circle me-1"></i>Dikonfirmasi
                                    </span>
                                <?php elseif ($item['status_booking'] == 'selesai'): ?>
                                    <span class="badge rounded-pill bg-success">
                                        <i class="bi bi-patch-check me-1"></i>Selesai
                                    </span>
                                <?php elseif ($item['status_booking'] == 'batal'): ?>
                                    <span class="badge rounded-pill bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Batal
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['status_pembayaran'] == 'sudah_bayar'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-wallet2 me-1"></i>Lunas
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-wallet2 me-1"></i>Belum Bayar
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
