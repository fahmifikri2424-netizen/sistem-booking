<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Layanan Baru</h5>

                <!-- Horizontal Form -->
                <form action="<?= base_url('admin/services/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Layanan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= old('nama') ?>" autofocus>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control <?= ($validation->hasError('deskripsi')) ? 'is-invalid' : '' ?>" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi') ?></textarea>
                            <div class="invalid-feedback">
                                <?= $validation->getError('deskripsi') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="harga" class="col-sm-2 col-form-label">Harga (Rp)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control <?= ($validation->hasError('harga')) ? 'is-invalid' : '' ?>" id="harga" name="harga" value="<?= old('harga') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('harga') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="durasi" class="col-sm-2 col-form-label">Durasi (Menit)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control <?= ($validation->hasError('durasi')) ? 'is-invalid' : '' ?>" id="durasi" name="durasi" value="<?= old('durasi') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('durasi') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="foto" class="col-sm-2 col-form-label">Foto Layanan</label>
                        <div class="col-sm-10">
                            <input class="form-control <?= ($validation->hasError('foto')) ? 'is-invalid' : '' ?>" type="file" id="foto" name="foto" accept="image/*">
                            <div class="invalid-feedback">
                                <?= $validation->getError('foto') ?>
                            </div>
                        </div>
                    </div>

                    <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="statusAktif" value="aktif" <?= old('status') == 'aktif' || old('status') == '' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusAktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="statusNonaktif" value="nonaktif" <?= old('status') == 'nonaktif' ? 'checked' : '' ?>>
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

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('admin/services') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
                <!-- End Horizontal Form -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
