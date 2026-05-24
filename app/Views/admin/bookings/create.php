<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body mt-3">
                <h5 class="card-title">Tambah Booking Baru</h5>

                <form action="<?= base_url('admin/bookings/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="id_user" class="col-sm-2 col-form-label">Pelanggan</label>
                        <div class="col-sm-10">
                            <select name="id_user" id="id_user" class="form-select <?= ($validation->hasError('id_user')) ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id_user'] ?>" <?= old('id_user') == $user['id_user'] ? 'selected' : '' ?>>
                                        <?= esc($user['nama']) ?> (<?= esc($user['username']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('id_user') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="id_staff" class="col-sm-2 col-form-label">Barber (Staff)</label>
                        <div class="col-sm-10">
                            <select name="id_staff" id="id_staff" class="form-select <?= ($validation->hasError('id_staff')) ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Barber --</option>
                                <?php foreach ($staffs as $staff): ?>
                                    <option value="<?= $staff['id_staff'] ?>" <?= old('id_staff') == $staff['id_staff'] ? 'selected' : '' ?>>
                                        <?= esc($staff['nama']) ?> - <?= esc($staff['spesialisasi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('id_staff') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="id_service" class="col-sm-2 col-form-label">Layanan</label>
                        <div class="col-sm-10">
                            <select name="id_service" id="id_service" class="form-select <?= ($validation->hasError('id_service')) ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Layanan --</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= $service['id_service'] ?>" <?= old('id_service') == $service['id_service'] ? 'selected' : '' ?>>
                                        <?= esc($service['nama']) ?> - Rp <?= number_format($service['harga'], 0, ',', '.') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('id_service') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="id_schedule" class="col-sm-2 col-form-label">Jadwal</label>
                        <div class="col-sm-10">
                            <select name="id_schedule" id="id_schedule" class="form-select <?= ($validation->hasError('id_schedule')) ? 'is-invalid' : '' ?>">
                                <option value="">-- Pilih Jadwal --</option>
                                <?php foreach ($schedules as $schedule): ?>
                                    <option value="<?= $schedule['id_schedule'] ?>" <?= old('id_schedule') == $schedule['id_schedule'] ? 'selected' : '' ?>>
                                        <?= date('d M Y', strtotime($schedule['tanggal'])) ?> | <?= date('H:i', strtotime($schedule['jam_mulai'])) ?> - <?= date('H:i', strtotime($schedule['jam_selesai'])) ?> (Kapasitas: <?= $schedule['kapasitas'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= $validation->getError('id_schedule') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="tanggal_booking" class="col-sm-2 col-form-label">Tanggal Booking</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control <?= ($validation->hasError('tanggal_booking')) ? 'is-invalid' : '' ?>" id="tanggal_booking" name="tanggal_booking" value="<?= old('tanggal_booking') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('tanggal_booking') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Catatan tambahan (opsional)"><?= old('catatan') ?></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Booking</button>
                        <a href="<?= base_url('admin/bookings') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>