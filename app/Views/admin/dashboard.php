<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle mb-4">
    <h1>Dashboard Admin</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<!-- Info Cards -->
<div class="row">
    
    <!-- Pendapatan Card -->
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Total Pendapatan <span>| Lunas</span></h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width:64px;height:64px;font-size:28px;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                        <h6 class="fw-bold text-success mb-0" style="font-size: 24px;">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h6>
                        <span class="text-muted small pt-2 ps-1">Dari booking yang selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Card -->
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Total Booking <span>| Semua</span></h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width:64px;height:64px;font-size:28px;">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <div class="ps-3">
                        <h6 class="fw-bold text-primary mb-0" style="font-size: 28px;"><?= $totalBooking ?></h6>
                        <span class="text-muted small pt-2 ps-1">Transaksi keseluruhan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Card -->
    <div class="col-xxl-4 col-md-12">
        <div class="card info-card customers-card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Pelanggan <span>| Aktif</span></h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white" style="width:64px;height:64px;font-size:28px;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                        <h6 class="fw-bold text-warning mb-0" style="font-size: 28px;"><?= $totalPelanggan ?></h6>
                        <span class="text-muted small pt-2 ps-1">User terdaftar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    
    <!-- Left side columns -->
    <div class="col-lg-8">
        
        <!-- Status Booking (Mini Cards) -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card bg-info text-white shadow-sm border-0">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-semibold">Pending</h6>
                            <h3 class="mb-0 fw-bold"><?= $statusPending ?></h3>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm border-0">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-semibold">Selesai</h6>
                            <h3 class="mb-0 fw-bold"><?= $statusSelesai ?></h3>
                        </div>
                        <i class="bi bi-patch-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm border-0">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-semibold">Batal</h6>
                            <h3 class="mb-0 fw-bold"><?= $statusBatal ?></h3>
                        </div>
                        <i class="bi bi-x-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Terbaru -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-0 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Booking Terbaru</h5>
                <a href="<?= base_url('admin/bookings') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookingTerbaru)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada booking</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookingTerbaru as $item): ?>
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace"><?= esc($item['kode_booking']) ?></span></td>
                                    <td class="fw-semibold"><?= esc($item['nama_customer']) ?></td>
                                    <td><?= esc($item['nama_service']) ?></td>
                                    <td><?= date('d M Y', strtotime($item['tanggal'])) ?></td>
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
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right side columns -->
    <div class="col-lg-4">
        
        <!-- Layanan Terpopuler -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-0 border-bottom-0">
                <h5 class="card-title mb-0"><i class="bi bi-graph-up-arrow text-success me-2"></i>Layanan Terpopuler</h5>
                <p class="text-muted small mt-1 mb-0">Berdasarkan total pesanan</p>
            </div>
            <div class="card-body">
                <div class="mt-3">
                    <?php if (empty($layananPopuler)): ?>
                        <div class="text-center py-4 text-muted">Belum ada data</div>
                    <?php else: ?>
                        <?php foreach ($layananPopuler as $i => $item): ?>
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded bg-light text-primary d-flex align-items-center justify-content-center fw-bold me-3" style="width:40px;height:40px;">
                                    #<?= $i + 1 ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold"><?= esc($item['nama']) ?></h6>
                                    <small class="text-success fw-semibold">Rp <?= number_format($item['harga'], 0, ',', '.') ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill px-3 py-2"><?= $item['total_dipesan'] ?>x</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>