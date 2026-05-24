<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= base_url('admin/services/create') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Layanan</a>
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

<table class="table table-striped datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Foto</th>
            <th scope="col">Nama Layanan</th>
            <th scope="col">Harga</th>
            <th scope="col">Durasi (Menit)</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($services as $service): ?>
            <tr>
                <th scope="row"><?= $i++ ?></th>
                <td>
                    <?php if ($service['foto']): ?>
                        <img src="<?= base_url('uploads/services/' . $service['foto']) ?>" alt="<?= $service['nama'] ?>" width="80" class="img-thumbnail">
                    <?php else: ?>
                        <span class="text-muted">No Image</span>
                    <?php endif; ?>
                </td>
                <td><?= esc($service['nama']) ?></td>
                <td>Rp <?= number_format($service['harga'], 0, ',', '.') ?></td>
                <td><?= esc($service['durasi']) ?></td>
                <td>
                    <?php if ($service['status'] == 'aktif'): ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Nonaktif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('admin/services/edit/' . $service['id_service']) ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    
                    <form action="<?= base_url('admin/services/delete/' . $service['id_service']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
