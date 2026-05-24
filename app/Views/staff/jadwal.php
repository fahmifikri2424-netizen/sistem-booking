<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filter Tanggal -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" action="<?= base_url('staff/jadwal') ?>" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label mb-1 fw-semibold">
                    <i class="bi bi-calendar3 me-1"></i>Pilih Tanggal
                </label>
                <input type="date" name="tanggal" class="form-control"
                       value="<?= esc($tanggal) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
                <a href="<?= base_url('staff/jadwal') ?>" class="btn btn-outline-secondary ms-1">
                    <i class="bi bi-arrow-counterclockwise"></i> Hari Ini
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Info Tanggal yang Ditampilkan -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-calendar-event me-2 text-primary"></i>
            Jadwal Tanggal: <span class="text-primary"><?= date('d F Y', strtotime($tanggal)) ?></span>
        </h5>
        <small class="text-muted"><?= count($jadwal) ?> booking ditemukan</small>
    </div>
</div>

<!-- Tabel Jadwal -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($jadwal)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 mb-3 d-block text-secondary"></i>
                <h6 class="fw-semibold">Tidak Ada Jadwal</h6>
                <p class="mb-0 small">Tidak ada tugas pada tanggal <?= date('d F Y', strtotime($tanggal)) ?>.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Jam</th>
                            <th>Catatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal as $i => $item): ?>
                        <tr>
                            <td class="ps-3"><?= $i + 1 ?></td>
                            <td>
                                <span class="badge bg-secondary font-monospace">
                                    <?= esc($item['kode_booking'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                         style="width:34px;height:34px;font-size:13px;font-weight:600;">
                                        <?= strtoupper(substr($item['nama_customer'], 0, 1)) ?>
                                    </div>
                                    <span class="fw-semibold"><?= esc($item['nama_customer']) ?></span>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-telephone me-1 text-muted"></i><?= esc($item['telepon']) ?>
                            </td>
                            <td><?= esc($item['nama_service']) ?></td>
                            <td class="text-success fw-semibold">
                                Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= substr($item['jam_mulai'], 0, 5) ?> – <?= substr($item['jam_selesai'], 0, 5) ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small"><?= esc($item['catatan'] ?? '-') ?></span>
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
