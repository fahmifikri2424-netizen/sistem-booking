<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Salam Staff -->
<div class="d-flex align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Selamat Datang, <?= esc(session()->get('nama')) ?>! 👋</h4>
        <small class="text-muted">Hari ini: <?= date('l, d F Y') ?></small>
    </div>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">

    <div class="col-xxl-3 col-md-6">
        <div class="card info-card" style="border-left: 4px solid #4e73df;">
            <div class="card-body d-flex align-items-center py-4">
                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;background:#e8edfb;">
                    <i class="bi bi-calendar-day fs-4 text-primary"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Tugas Hari Ini</p>
                    <h4 class="mb-0 fw-bold"><?= $totalHariIni ?></h4>
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
                    <p class="mb-0 text-muted small">Menunggu</p>
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
                    <p class="mb-0 text-muted small">Total Selesai</p>
                    <h4 class="mb-0 fw-bold"><?= $totalSelesai ?></h4>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Jadwal Hari Ini -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">
            <i class="bi bi-calendar-check me-2 text-primary"></i>Jadwal Hari Ini
        </h5>
        <a href="<?= base_url('staff/jadwal') ?>" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($jadwalHariIni)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 mb-2 d-block"></i>
                <p class="mb-0">Tidak ada jadwal untuk hari ini.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwalHariIni as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><span class="badge bg-secondary"><?= esc($item['kode_booking']) ?></span></td>
                            <td><?= esc($item['nama_customer']) ?></td>
                            <td><?= esc($item['nama_service']) ?></td>
                            <td>
                                <i class="bi bi-clock me-1"></i>
                                <?= substr($item['jam_mulai'], 0, 5) ?> – <?= substr($item['jam_selesai'], 0, 5) ?>
                            </td>
                            <td>
                                <?php if ($item['status_booking'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($item['status_booking'] == 'dikonfirmasi'): ?>
                                    <span class="badge bg-info text-dark">Dikonfirmasi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('staff/update-status') ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> Update
                                </a>
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