<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card premium-card">
            <div class="card-body mt-3">
                <h5 class="card-title">Tambah Jadwal Baru</h5>

                <form action="<?= base_url('admin/schedules/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control <?= ($validation->hasError('tanggal')) ? 'is-invalid' : '' ?>" id="tanggal" name="tanggal" value="<?= old('tanggal') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('tanggal') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jam_mulai" class="col-sm-2 col-form-label">Jam Mulai</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control <?= ($validation->hasError('jam_mulai')) ? 'is-invalid' : '' ?>" id="jam_mulai" name="jam_mulai" value="<?= old('jam_mulai') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('jam_mulai') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jam_selesai" class="col-sm-2 col-form-label">Jam Selesai</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control <?= ($validation->hasError('jam_selesai')) ? 'is-invalid' : '' ?>" id="jam_selesai" name="jam_selesai" value="<?= old('jam_selesai') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('jam_selesai') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kapasitas" class="col-sm-2 col-form-label">Kapasitas</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control <?= ($validation->hasError('kapasitas')) ? 'is-invalid' : '' ?>" id="kapasitas" name="kapasitas" value="<?= old('kapasitas') ?>" placeholder="Misal: 10">
                            <div class="invalid-feedback">
                                <?= $validation->getError('kapasitas') ?>
                            </div>
                        </div>
                    </div>

                    <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="statusAvailable" value="available" <?= old('status') == 'available' || old('status') == '' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusAvailable">
                                    Available
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input <?= ($validation->hasError('status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="statusBooked" value="booked" <?= old('status') == 'booked' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusBooked">
                                    Booked
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('admin/schedules') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
