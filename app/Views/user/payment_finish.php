<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<?php
    $isPaid    = ($booking['status_pembayaran'] === 'sudah_bayar');
    $isFailed  = ($payment && $payment['status'] === 'gagal');
    $isPending = !$isPaid && !$isFailed;
?>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <?php if ($isPaid): ?>
        <!-- SUCCESS STATE -->
        <div class="text-center py-4">
            <div class="mb-4" style="font-size: 80px; line-height:1;">✅</div>
            <h3 class="fw-bold text-success mb-2">Pembayaran Berhasil!</h3>
            <p class="text-muted">Transaksi Anda telah dikonfirmasi dan booking Anda sudah aktif.</p>
        </div>

        <?php elseif ($isFailed): ?>
        <!-- FAILED STATE -->
        <div class="text-center py-4">
            <div class="mb-4" style="font-size: 80px; line-height:1;">❌</div>
            <h3 class="fw-bold text-danger mb-2">Pembayaran Gagal</h3>
            <p class="text-muted">Transaksi Anda dibatalkan atau ditolak. Silakan coba lagi.</p>
        </div>

        <?php else: ?>
        <!-- PENDING STATE -->
        <div class="text-center py-4">
            <div class="mb-4" style="font-size: 80px; line-height:1;">⏳</div>
            <h3 class="fw-bold text-warning mb-2">Pembayaran Menunggu Konfirmasi</h3>
            <p class="text-muted">Pembayaran Anda sedang diproses. Status akan diperbarui otomatis.</p>
        </div>
        <?php endif; ?>

        <!-- Detail Booking Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Detail Transaksi</h5>
                <hr class="mt-0">

                <!-- Kode Booking -->
                <div class="text-center mb-4">
                    <p class="text-muted small text-uppercase fw-semibold mb-1">Kode Booking</p>
                    <span class="font-monospace fw-bold fs-4 text-primary">
                        <?= esc($booking['kode_booking']) ?>
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Layanan</div>
                            <div class="fw-bold"><?= esc($booking['nama_service']) ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Jadwal</div>
                            <div class="fw-bold"><?= date('d M Y', strtotime($booking['tanggal'])) ?></div>
                            <div class="text-muted small">
                                <?= substr($booking['jam_mulai'], 0, 5) ?> – <?= substr($booking['jam_selesai'], 0, 5) ?> WIB
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Status Pembayaran</div>
                            <?php if ($isPaid): ?>
                                <span class="badge bg-success px-3 py-2">✅ Lunas</span>
                            <?php elseif ($isFailed): ?>
                                <span class="badge bg-danger px-3 py-2">❌ Gagal</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark px-3 py-2">⏳ Pending</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Total Dibayar</div>
                            <div class="fw-bold text-primary fs-5">
                                Rp <?= number_format($booking['harga'], 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($isPaid): ?>
                <div class="alert alert-success border-0 mt-4 mb-0 d-flex align-items-start">
                    <i class="bi bi-envelope-check-fill me-2 mt-1"></i>
                    <div>
                        <strong>Email konfirmasi telah dikirim</strong> ke alamat email Anda.
                        Harap datang 10-15 menit sebelum jadwal.
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($isPending): ?>
                <div class="alert alert-warning border-0 mt-4 mb-0 d-flex align-items-start">
                    <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                    <div>
                        Status pembayaran akan diperbarui otomatis dalam beberapa saat.
                        Jika sudah melebihi 30 menit dan status belum berubah, hubungi admin.
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?= base_url('user/riwayat') ?>" class="btn btn-primary px-4">
                <i class="bi bi-clock-history me-1"></i>Lihat Riwayat
            </a>
            <a href="<?= base_url('user/layanan') ?>" class="btn btn-outline-secondary px-4">
                <i class="bi bi-scissors me-1"></i>Booking Lagi
            </a>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
