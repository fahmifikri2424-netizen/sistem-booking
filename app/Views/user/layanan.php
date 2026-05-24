<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="mb-4 text-center">
    <h3 class="fw-bold text-primary">Layanan Kami</h3>
    <p class="text-muted">Pilih layanan perawatan terbaik dari ahlinya.</p>
</div>

<div class="row g-4">
    <?php if (empty($layanan)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-scissors fs-1 text-muted mb-3 d-block"></i>
            <h5>Belum Ada Layanan</h5>
            <p class="text-muted">Mohon maaf, saat ini belum ada layanan yang aktif.</p>
        </div>
    <?php else: ?>
        <?php foreach ($layanan as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm service-card transition-hover">
                    <?php if (!empty($item['foto'])): ?>
                        <img src="<?= base_url('uploads/services/' . $item['foto']) ?>" class="card-img-top" alt="<?= esc($item['nama']) ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0 fw-bold"><?= esc($item['nama']) ?></h5>
                            <span class="badge bg-primary rounded-pill">
                                <?= $item['durasi'] ?> Min
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-success fw-bold mb-0">Rp <?= number_format($item['harga'], 0, ',', '.') ?></h6>
                            <div class="text-warning small" title="<?= $item['avg_rating'] ?> dari 5 bintang">
                                <?php if ($item['total_reviews'] > 0): ?>
                                    <i class="bi bi-star-fill"></i> <span class="fw-bold text-dark"><?= $item['avg_rating'] ?></span>
                                    <span class="text-muted ms-1">(<?= $item['total_reviews'] ?>)</span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Belum ada ulasan</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <p class="card-text text-muted small flex-grow-1">
                            <?= esc($item['deskripsi']) ?>
                        </p>
                        
                        <a href="<?= base_url('user/pilih-jadwal/' . $item['id_service']) ?>" class="btn btn-outline-primary w-100 mt-3 fw-semibold">
                            <i class="bi bi-calendar-plus me-1"></i>Pilih Layanan
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.service-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>

<?= $this->endSection() ?>
