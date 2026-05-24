<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= base_url('admin/bookings/create') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Booking</a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body mt-3">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kode</th>
                    <th scope="col">Pelanggan</th>
                    <th scope="col">Barber</th>
                    <th scope="col">Layanan</th>
                    <th scope="col">Tanggal Booking</th>
                    <th scope="col">Status Booking</th>
                    <th scope="col">Status Bayar</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($bookings as $booking): ?>
                    <tr>
                        <th scope="row"><?= $i++ ?></th>
                        <td><span class="badge bg-dark"><?= esc($booking['kode_booking']) ?></span></td>
                        <td><?= esc($booking['nama']) ?></td>
                        <td><?= esc($booking['nama_staff']) ?></td>
                        <td><?= esc($booking['nama_service']) ?></td>
                        <td><?= date('d M Y', strtotime($booking['tanggal_booking'])) ?></td>
                        <td>
                            <?php
                                $statusClass = match($booking['status_booking']) {
                                    'pending'       => 'bg-warning text-dark',
                                    'dikonfirmasi'  => 'bg-primary',
                                    'selesai'       => 'bg-success',
                                    'batal'         => 'bg-danger',
                                    default         => 'bg-secondary',
                                };
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($booking['status_booking'])) ?></span>
                        </td>
                        <td>
                            <?php if ($booking['status_pembayaran'] == 'sudah_bayar'): ?>
                                <span class="badge bg-success">Sudah Bayar</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum Bayar</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($booking['status_booking'] == 'pending'): ?>
                                <form action="<?= base_url('admin/bookings/confirm/' . $booking['id_booking']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-success btn-sm" title="Konfirmasi Booking"><i class="bi bi-check-lg"></i></button>
                                </form>
                            <?php endif; ?>

                            <?php if (in_array($booking['status_booking'], ['pending', 'dikonfirmasi'])): ?>
                                <form action="<?= base_url('admin/bookings/cancel/' . $booking['id_booking']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-warning btn-sm text-white" title="Batalkan Booking" onclick="return confirm('Yakin membatalkan booking ini?')"><i class="bi bi-x-lg"></i></button>
                                </form>
                            <?php endif; ?>

                            <form action="<?= base_url('admin/bookings/delete/' . $booking['id_booking']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Booking" onclick="return confirm('Yakin ingin menghapus booking ini?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>