<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('user/pilih-jadwal/' . $service['id_service']) ?>" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali Ubah Jadwal/Barber
    </a>
    <h4 class="fw-bold">Konfirmasi Booking</h4>
    <p class="text-muted">Lengkapi detail pesanan Anda sebelum menyimpan.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="card-title mb-0"><i class="bi bi-card-checklist me-2 text-primary"></i>Detail Booking</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('user/booking/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_service" value="<?= $service['id_service'] ?>">
                    <input type="hidden" name="id_schedule" value="<?= $schedule['id_schedule'] ?>">
                    <input type="hidden" name="id_staff" value="<?= $staffData['id_staff'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Layanan Terpilih</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded border">
                            <i class="bi bi-scissors fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= esc($service['nama']) ?></h6>
                                <small class="text-muted"><?= $service['durasi'] ?> Menit</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Jadwal</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded border">
                            <i class="bi bi-calendar-event fs-4 text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= date('l, d F Y', strtotime($schedule['tanggal'])) ?></h6>
                                <small class="text-muted">Jam: <?= substr($schedule['jam_mulai'], 0, 5) ?> - <?= substr($schedule['jam_selesai'], 0, 5) ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Barber</label>
                        <div class="d-flex align-items-center p-3 bg-light rounded border">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-5 me-3" style="width:40px;height:40px;">
                                <?= strtoupper(substr($staffData['nama'], 0, 1)) ?>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= esc($staffData['nama']) ?></h6>
                                <small class="text-muted"><?= esc($staffData['spesialisasi']) ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Tolong potong tipis di bagian samping..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-3 fw-bold fs-6 rounded-3 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Konfirmasi & Simpan Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="bi bi-wallet2 me-2"></i>Ringkasan Pembayaran</h5>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Layanan</span>
                    <span class="fw-semibold">Rp <?= number_format($service['harga'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Pajak / Biaya Admin</span>
                    <span class="fw-semibold text-success">Gratis</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-4 text-primary">Rp <?= number_format($service['harga'], 0, ',', '.') ?></span>
                </div>
                
                <div class="alert alert-warning mt-4 mb-0 small border-0 d-flex align-items-start">
                    <i class="bi bi-info-circle-fill mt-1 me-2"></i>
                    <div>
                        Pembayaran dilakukan secara langsung (Cash/QRIS) di barbershop setelah layanan selesai.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
