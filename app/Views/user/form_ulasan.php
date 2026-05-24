<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('user/riwayat') ?>" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold">Beri Ulasan & Rating</h4>
    <p class="text-muted">Bagaimana pengalaman Anda menggunakan layanan kami?</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        <!-- Info Booking -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-secondary font-monospace"><?= esc($booking['kode_booking']) ?></span>
                    <span class="text-muted small"><?= date('d M Y', strtotime($booking['tanggal'])) ?></span>
                </div>
                <h5 class="fw-bold text-primary mb-1"><?= esc($booking['nama_service']) ?></h5>
                <p class="mb-0 text-muted small"><i class="bi bi-person me-1"></i>Barber: <span class="fw-semibold text-dark"><?= esc($booking['nama_staff']) ?></span></p>
            </div>
        </div>

        <!-- Form Ulasan -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="text-danger small mb-3">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('user/ulasan/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_booking" value="<?= $booking['id_booking'] ?>">

                    <!-- Rating Stars -->
                    <div class="mb-4 text-center">
                        <label class="form-label fw-bold d-block mb-3 fs-5">Beri Rating</label>
                        <div class="rating-stars">
                            <input type="radio" name="rating" id="star5" value="5" required>
                            <label for="star5" title="5 Bintang"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="rating" id="star4" value="4">
                            <label for="star4" title="4 Bintang"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="rating" id="star3" value="3">
                            <label for="star3" title="3 Bintang"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="rating" id="star2" value="2">
                            <label for="star2" title="2 Bintang"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" name="rating" id="star1" value="1">
                            <label for="star1" title="1 Bintang"><i class="bi bi-star-fill"></i></label>
                        </div>
                    </div>

                    <!-- Komentar -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tulis Komentar (Opsional)</label>
                        <textarea name="komentar" class="form-control" rows="4" placeholder="Ceritakan pengalaman Anda di sini... (Maksimal 1000 karakter)"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning py-3 fw-bold fs-6 rounded-3 shadow-sm text-dark">
                            <i class="bi bi-send-check me-2"></i>Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
/* Styling for reverse star rating using CSS */
.rating-stars {
    display: inline-flex;
    flex-direction: row-reverse;
    justify-content: center;
}
.rating-stars input {
    display: none;
}
.rating-stars label {
    font-size: 2.5rem;
    color: #e4e5e9;
    cursor: pointer;
    padding: 0 5px;
    transition: color 0.2s;
}
.rating-stars input:checked ~ label {
    color: #ffc107;
}
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}
</style>

<?= $this->endSection() ?>
