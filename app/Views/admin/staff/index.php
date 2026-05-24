<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= base_url('admin/staffs/create') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Staff</a>
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
                    <th scope="col">Nama Barber</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telepon</th>
                    <th scope="col">Spesialisasi</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($staffs as $staff): ?>
                    <tr>
                        <th scope="row"><?= $i++ ?></th>
                        <td><?= esc($staff['nama']) ?></td>
                        <td><span class="badge bg-dark"><?= esc($staff['username']) ?></span></td>
                        <td><?= esc($staff['email']) ?></td>
                        <td><?= esc($staff['telepon']) ?></td>
                        <td><span class="badge bg-secondary"><?= esc($staff['spesialisasi']) ?></span></td>
                        <td>
                            <?php if ($staff['status'] == 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/staffs/edit/' . $staff['id_staff']) ?>" class="btn btn-warning btn-sm" title="Edit Staff"><i class="bi bi-pencil"></i> Edit</a>
                            
                            <form action="<?= base_url('admin/staffs/delete/' . $staff['id_staff']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Staff" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini? Semua riwayat booking staff ini juga akan terhapus.')"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
