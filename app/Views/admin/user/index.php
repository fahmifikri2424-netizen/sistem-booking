<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> Tambah User
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body mt-3">

        <table class="table table-striped datatable">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama User</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php $i = 1; ?>
                <?php foreach ($users as $user): ?>

                    <tr>

                        <td><?= $i++ ?></td>

                        <td><?= esc($user['nama']) ?></td>

                        <td>
                            <span class="badge bg-dark">
                                <?= esc($user['username']) ?>
                            </span>
                        </td>

                        <td><?= esc($user['email']) ?></td>

                        <td><?= esc($user['telepon']) ?></td>

                        <td>

                            <?php if ($user['status'] == 'aktif'): ?>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Nonaktif
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="<?= base_url('admin/users/edit/' . $user['id_user']) ?>"
                               class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil"></i>
                                Edit

                            </a>

                            <form action="<?= base_url('admin/users/delete/' . $user['id_user']) ?>"
                                  method="post"
                                  class="d-inline">

                                <?= csrf_field() ?>

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus user ini?')">

                                    <i class="bi bi-trash"></i>
                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>
</div>

<?= $this->endSection() ?>