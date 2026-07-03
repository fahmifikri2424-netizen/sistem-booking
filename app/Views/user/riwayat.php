<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filter Status -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="get" action="<?= base_url('user/riwayat') ?>" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label mb-1 fw-semibold text-muted small text-uppercase">Filter Status</label>
                <select name="status" class="form-select border-0 bg-light" style="min-width:200px;">
                    <option value="" <?= $filterStatus == '' ? 'selected' : '' ?>>Semua Transaksi</option>
                    <option value="pending"       <?= $filterStatus == 'pending'       ? 'selected' : '' ?>>Pending</option>
                    <option value="dikonfirmasi"  <?= $filterStatus == 'dikonfirmasi'  ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="selesai"       <?= $filterStatus == 'selesai'       ? 'selected' : '' ?>>Selesai</option>
                    <option value="batal"         <?= $filterStatus == 'batal'         ? 'selected' : '' ?>>Batal</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- List Riwayat -->
<div class="row g-4">
    <?php if (empty($riwayat)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-journal-x fs-1 text-muted mb-3 d-block"></i>
            <h5 class="fw-bold">Tidak Ada Transaksi</h5>
            <p class="text-muted">Anda belum memiliki riwayat booking dengan status tersebut.</p>
            <a href="<?= base_url('user/layanan') ?>" class="btn btn-primary mt-2">Buat Booking Baru</a>
        </div>
    <?php else: ?>
        <?php foreach ($riwayat as $item): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                            <div>
                                <span class="badge bg-light text-dark border border-secondary font-monospace me-2">
                                    <?= esc($item['kode_booking']) ?>
                                </span>
                                <span class="text-muted small">Dipesan pada: <?= date('d M Y', strtotime($item['created_at'])) ?></span>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <?php if ($item['status_booking'] == 'pending'): ?>
                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                <?php elseif ($item['status_booking'] == 'dikonfirmasi'): ?>
                                    <span class="badge rounded-pill bg-info text-dark px-3 py-2"><i class="bi bi-check-circle me-1"></i>Dikonfirmasi</span>
                                <?php elseif ($item['status_booking'] == 'selesai'): ?>
                                    <span class="badge rounded-pill bg-success px-3 py-2"><i class="bi bi-patch-check me-1"></i>Selesai</span>
                                <?php elseif ($item['status_booking'] == 'batal'): ?>
                                    <span class="badge rounded-pill bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i>Batal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <hr class="mt-0 mb-3 text-muted">
                        
                        <div class="row">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <h5 class="fw-bold text-primary mb-1"><?= esc($item['nama_service']) ?></h5>
                                <div class="text-muted small mb-2"><i class="bi bi-person me-1"></i>Barber: <?= esc($item['nama_staff']) ?></div>
                                <h6 class="fw-bold text-success mb-0">Rp <?= number_format($item['harga'], 0, ',', '.') ?></h6>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0 border-start border-end px-md-4">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Jadwal Kedatangan</div>
                                <div class="fw-bold"><i class="bi bi-calendar-event me-2 text-primary"></i><?= date('l, d F Y', strtotime($item['tanggal'])) ?></div>
                                <div class="text-muted"><i class="bi bi-clock me-2 text-primary"></i><?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?> WIB</div>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <div class="text-muted small text-uppercase fw-semibold mb-1">Status Pembayaran</div>
                                <?php if ($item['status_pembayaran'] == 'sudah_bayar'): ?>
                                    <div class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Lunas (Dibayar)</div>
                                <?php else: ?>
                                    <div class="text-danger fw-bold"><i class="bi bi-exclamation-circle-fill me-1"></i>Belum Dibayar</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="mt-3 text-muted">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <?php if(!empty($item['catatan'])): ?>
                                    <i class="bi bi-journal-text me-1"></i>Catatan: <?= esc($item['catatan']) ?>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex gap-2 flex-wrap justify-content-end">
                                <!-- Tombol BAYAR (Hanya jika dikonfirmasi & belum bayar) -->
                                <?php if ($item['status_booking'] == 'dikonfirmasi' && $item['status_pembayaran'] == 'belum_bayar'): ?>
                                    <button
                                        id="btn-bayar-<?= $item['id_booking'] ?>"
                                        class="btn btn-success btn-sm fw-bold px-3 btn-bayar"
                                        data-booking-id="<?= $item['id_booking'] ?>"
                                        data-create-url="<?= base_url('user/payment/create/' . $item['id_booking']) ?>"
                                        onclick="initPayment(this)">
                                        <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                                    </button>
                                <?php endif; ?>

                                <!-- Batal Action (Hanya jika pending) -->
                                <?php if ($item['status_booking'] == 'pending'): ?>
                                    <form action="<?= base_url('user/batal/' . $item['id_booking']) ?>" method="post" onsubmit="return confirm('Yakin ingin membatalkan booking ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">Batalkan Booking</button>
                                    </form>
                                <?php endif; ?>

                                <!-- Ulasan Action (Hanya jika selesai) -->
                                <?php if ($item['status_booking'] == 'selesai'): ?>
                                    <?php if (empty($item['id_review'])): ?>
                                        <a href="<?= base_url('user/ulasan/' . $item['id_booking']) ?>" class="btn btn-warning btn-sm text-dark fw-bold">
                                            <i class="bi bi-star-fill me-1"></i>Beri Ulasan
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-warning border border-warning px-3 py-2">
                                            <i class="bi bi-star-fill me-1"></i>Telah Diulas (<?= $item['rating'] ?>/5)
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Midtrans Snap.js -->
<script src="<?= env('MIDTRANS_IS_PRODUCTION', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>"
        data-client-key="<?= env('MIDTRANS_CLIENT_KEY', '') ?>"></script>

<script>
/**
 * Inisiasi pembayaran Midtrans Snap
 * 1. Kirim POST ke backend untuk buat transaksi & dapatkan snap_token
 * 2. Buka popup Snap dengan snap_token tersebut
 */
function initPayment(btn) {
    const bookingId = btn.dataset.bookingId;
    const createUrl = btn.dataset.createUrl;

    // Tampilkan loading state
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Memproses...';

    // Ambil CSRF token
    const csrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('csrf_cookie_name='))
        ?.split('=')[1] ?? '';

    fetch(createUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ csrf_test_name: csrfToken }),
        credentials: 'same-origin',
    })
    .then(res => res.json())
    .then(data => {
        if (data.status !== 'success') {
            throw new Error(data.message || 'Gagal membuat transaksi.');
        }

        // Buka popup Midtrans Snap
        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Dibayar';
                btn.classList.replace('btn-success', 'btn-secondary');
                showAlert('Pembayaran berhasil! Status akan segera diperbarui.', 'success');
                setTimeout(() => window.location.href = '<?= base_url('user/payment/finish/') ?>' + bookingId, 2000);
            },
            onPending: function(result) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
                showAlert('Pembayaran sedang diproses. Selesaikan pembayaran Anda.', 'warning');
            },
            onError: function(result) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
                showAlert('Pembayaran gagal. Silakan coba lagi.', 'danger');
            },
            onClose: function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
            }
        });
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
        showAlert('Error: ' + err.message, 'danger');
    });
}

function showAlert(message, type = 'info') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" id="dynamic-alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    const existing = document.getElementById('dynamic-alert');
    if (existing) existing.remove();
    document.querySelector('.row.g-4').insertAdjacentHTML('beforebegin', alertHtml);
    setTimeout(() => {
        const el = document.getElementById('dynamic-alert');
        if (el) el.classList.remove('show');
    }, 5000);
}
</script>

<?= $this->endSection() ?>
