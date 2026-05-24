<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mt-3">Tambah Barber (Staff) Baru</h5>

                <!-- Horizontal Form -->
                <form action="<?= base_url('admin/staffs/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= old('nama') ?>" autofocus placeholder="Misal: Budi Santoso">
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username') ?>" placeholder="Misal: budi_barber">
                            <div class="invalid-feedback">
                                <?= $validation->getError('username') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email') ?>" placeholder="Misal: budi@gmail.com">
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Minimal 3 karakter">
                            <div class="invalid-feedback">
                                <?= $validation->getError('password') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="telepon" class="col-sm-2 col-form-label">No. Telepon</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('telepon')) ? 'is-invalid' : '' ?>" id="telepon" name="telepon" value="<?= old('telepon') ?>" placeholder="Misal: 081234567890">
                            <div class="invalid-feedback">
                                <?= $validation->getError('telepon') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="spesialisasi" class="col-sm-2 col-form-label">Spesialisasi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('spesialisasi')) ? 'is-invalid' : '' ?>" id="spesialisasi" name="spesialisasi" value="<?= old('spesialisasi') ?>" placeholder="Misal: Fade Cut, Hair Tattoo, Classic Cut">
                            <div class="invalid-feedback">
                                <?= $validation->getError('spesialisasi') ?>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Staff</button>
                        <a href="<?= base_url('admin/staffs') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
                <!-- End Horizontal Form -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
