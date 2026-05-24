<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= base_url('admin/schedules/create') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Jadwal</a>
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

<div class="card premium-card">
    <div class="card-body mt-3">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Waktu</th>
                    <th scope="col">Kapasitas</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($schedules as $schedule): ?>
                    <tr>
                        <th scope="row"><?= $i++ ?></th>
                        <td><?= date('d M Y', strtotime($schedule['tanggal'])) ?></td>
                        <td><?= date('H:i', strtotime($schedule['jam_mulai'])) ?> - <?= date('H:i', strtotime($schedule['jam_selesai'])) ?></td>
                        <td><?= esc($schedule['kapasitas']) ?> Orang</td>
                        <td>
                            <?php if ($schedule['status'] == 'available'): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Booked</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/schedules/edit/' . $schedule['id_schedule']) ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            
                            <form action="<?= base_url('admin/schedules/delete/' . $schedule['id_schedule']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
