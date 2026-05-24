<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mt-3">Edit Data Staff (Barber)</h5>

                <!-- Horizontal Form -->
                <form action="<?= base_url('admin/staffs/update/' . $staff['id_staff']) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= old('nama', $staff['nama']) ?>" autofocus>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username', $staff['username']) ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('username') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email', $staff['email']) ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <div class="invalid-feedback">
                                <?= $validation->getError('password') ?>
                            </div>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password lama.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="telepon" class="col-sm-2 col-form-label">No. Telepon</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('telepon')) ? 'is-invalid' : '' ?>" id="telepon" name="telepon" value="<?= old('telepon', $staff['telepon']) ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('telepon') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="spesialisasi" class="col-sm-2 col-form-label">Spesialisasi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('spesialisasi')) ? 'is-invalid' : '' ?>" id="spesialisasi" name="spesialisasi" value="<?= old('spesialisasi', $staff['spesialisasi']) ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('spesialisasi') ?>
                            </div>
                        </div>
                    </div>

                    <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="statusAktif" value="aktif" <?= old('status', $staff['status']) == 'aktif' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusAktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="statusNonaktif" value="nonaktif" <?= old('status', $staff['status']) == 'nonaktif' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusNonaktif">
                                    Nonaktif
                                </label>
                            </div>
                            <?php if ($validation->hasError('status')): ?>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('status') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </fieldset>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('admin/staffs') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
                <!-- End Horizontal Form -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
