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

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Manajemen Ulasan Pelanggan</h5>
    </div>
    <div class="card-body">
        <?php if (empty($reviews)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-star fs-1 mb-3 d-block"></i>
                <p>Belum ada ulasan dari pelanggan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Layanan & Barber</th>
                            <th>Kode Booking</th>
                            <th>Rating</th>
                            <th style="width: 30%">Komentar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <span class="fw-semibold"><?= esc($item['nama_customer']) ?></span>
                            </td>
                            <td>
                                <div><?= esc($item['nama_service']) ?></div>
                                <small class="text-muted"><i class="bi bi-person me-1"></i><?= esc($item['nama_staff']) ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?= esc($item['kode_booking']) ?></span></td>
                            <td>
                                <div class="text-warning">
                                    <?php for($j=1; $j<=5; $j++): ?>
                                        <?php if($j <= $item['rating']): ?>
                                            <i class="bi bi-star-fill"></i>
                                        <?php else: ?>
                                            <i class="bi bi-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">(<?= $item['rating'] ?>/5)</small>
                            </td>
                            <td>
                                <?php if(!empty($item['komentar'])): ?>
                                    <div class="fst-italic">"<?= esc($item['komentar']) ?>"</div>
                                <?php else: ?>
                                    <span class="text-muted small">Tidak ada komentar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?= base_url('admin/reviews/delete/' . $item['id_review']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Ulasan">
                                        <i class="bi bi-trash"></i>
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

<?= $this->endSection() ?>
