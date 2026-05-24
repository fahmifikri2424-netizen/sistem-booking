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

<!-- Info -->
<div class="alert alert-info d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
    <div>
        Halaman ini menampilkan booking yang <strong>belum selesai</strong> (Pending / Dikonfirmasi).
        Anda dapat mengubah status sesuai perkembangan pengerjaan.
    </div>
</div>

<!-- Tabel Update Status -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Daftar Booking Aktif
            <span class="badge bg-primary ms-2"><?= count($bookings) ?></span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($bookings)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clipboard-check fs-1 mb-3 d-block text-success"></i>
                <h6 class="fw-semibold">Semua Booking Sudah Ditangani!</h6>
                <p class="mb-0 small">Tidak ada booking yang memerlukan update status saat ini.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Tanggal &amp; Jam</th>
                            <th>Status Saat Ini</th>
                            <th class="text-center">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $i => $item): ?>
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
                                         style="width:34px;height:34px;font-size:13px;">
                                        <?= strtoupper(substr($item['nama_customer'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold lh-sm"><?= esc($item['nama_customer']) ?></div>
                                        <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= esc($item['telepon']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($item['nama_service']) ?></td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                    <?= date('d M Y', strtotime($item['tanggal'])) ?>
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= substr($item['jam_mulai'], 0, 5) ?> – <?= substr($item['jam_selesai'], 0, 5) ?>
                                </div>
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
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Form update status -->
                                <form method="post"
                                      action="<?= base_url('staff/update-status/' . $item['id_booking']) ?>"
                                      class="d-flex gap-2 align-items-center justify-content-center"
                                      onsubmit="return konfirmasiUpdate(this)">
                                    <?= csrf_field() ?>
                                    <select name="status_booking" class="form-select form-select-sm" style="min-width:140px;">
                                        <?php if ($item['status_booking'] == 'pending'): ?>
                                            <option value="dikonfirmasi">✅ Konfirmasi</option>
                                            <option value="batal">❌ Batalkan</option>
                                        <?php elseif ($item['status_booking'] == 'dikonfirmasi'): ?>
                                            <option value="selesai">🏁 Selesai</option>
                                            <option value="batal">❌ Batalkan</option>
                                        <?php endif; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function konfirmasiUpdate(form) {
    const select = form.querySelector('select[name="status_booking"]');
    const pilihan = select.options[select.selectedIndex].text.trim();
    return confirm('Yakin ingin mengubah status menjadi "' + pilihan + '"?');
}
</script>

<?= $this->endSection() ?>
