<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Salam User -->
<div class="d-flex align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Selamat Datang, <?= esc(session()->get('nama')) ?>! 👋</h4>
        <small class="text-muted">Siap untuk tampil lebih rapi hari ini?</small>
    </div>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">

    <div class="col-xxl-3 col-md-6">
        <div class="card info-card" style="border-left: 4px solid #4e73df;">
            <div class="card-body d-flex align-items-center py-4">
                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e8edfb;">
                    <i class="bi bi-journal-bookmark fs-4 text-primary"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Booking</p>
                    <h4 class="mb-0 fw-bold"><?= $totalBooking ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-md-6">
        <div class="card info-card" style="border-left: 4px solid #f6c23e;">
            <div class="card-body d-flex align-items-center py-4">
                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#fef9e7;">
                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Pending</p>
                    <h4 class="mb-0 fw-bold"><?= $totalPending ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-md-6">
        <div class="card info-card" style="border-left: 4px solid #36b9cc;">
            <div class="card-body d-flex align-items-center py-4">
                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e8f8fb;">
                    <i class="bi bi-check2-circle fs-4 text-info"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Dikonfirmasi</p>
                    <h4 class="mb-0 fw-bold"><?= $totalDikonfirmasi ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-md-6">
        <div class="card info-card" style="border-left: 4px solid #1cc88a;">
            <div class="card-body d-flex align-items-center py-4">
                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e6f9f3;">
                    <i class="bi bi-patch-check fs-4 text-success"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Selesai</p>
                    <h4 class="mb-0 fw-bold"><?= $totalSelesai ?></h4>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Booking Terbaru & Promo -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Booking Terbaru Saya
                </h5>
                <a href="<?= base_url('user/riwayat') ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($bookingTerbaru)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-1 mb-2 d-block"></i>
                        <p class="mb-0">Belum ada riwayat booking.</p>
                        <a href="<?= base_url('user/layanan') ?>" class="btn btn-primary mt-3">Booking Sekarang</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Kode</th>
                                    <th>Layanan</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookingTerbaru as $item): ?>
                                <tr>
                                    <td class="ps-3"><span class="badge bg-secondary"><?= esc($item['kode_booking']) ?></span></td>
                                    <td>
                                        <div class="fw-semibold"><?= esc($item['nama_service']) ?></div>
                                        <small class="text-muted"><i class="bi bi-person me-1"></i><?= esc($item['nama_staff']) ?></small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                                            <?= date('d M Y', strtotime($item['tanggal'])) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= substr($item['jam_mulai'], 0, 5) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($item['status_booking'] == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php elseif ($item['status_booking'] == 'dikonfirmasi'): ?>
                                            <span class="badge bg-info text-dark">Dikonfirmasi</span>
                                        <?php elseif ($item['status_booking'] == 'selesai'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php elseif ($item['status_booking'] == 'batal'): ?>
                                            <span class="badge bg-danger">Batal</span>
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
    </div>

    <div class="col-lg-4">
        <div class="card bg-primary text-white text-center">
            <div class="card-body py-5">
                <i class="bi bi-scissors fs-1 mb-3"></i>
                <h4 class="fw-bold">Butuh Layanan?</h4>
                <p class="mb-4">Pilih layanan favoritmu dan booking jadwal sekarang juga. Tanpa perlu antre lama!</p>
                <a href="<?= base_url('user/layanan') ?>" class="btn btn-light text-primary fw-bold px-4 rounded-pill">
                    Pilih Layanan
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>