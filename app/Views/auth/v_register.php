<?= $this->extend('layout_clear') ?>
<?= $this->section('main') ?>

<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7 d-flex flex-column align-items-center justify-content-center">

                <div class="d-flex justify-content-center mb-3">
                    <a href="<?= base_url('/') ?>" class="logo d-flex align-items-center gap-2">
                        <img src="<?= base_url() ?>NiceAdmin/assets/img/logo.png" alt="">
                        <span class="fw-bold fs-5">BARBERSHOP</span>
                    </a>
                </div>

                <div class="card w-100 mb-3">
                    <div class="card-body px-4 py-4">
                        <div class="pt-2 pb-3">
                            <h5 class="card-title text-center pb-0 fs-4 fw-bold">Buat Akun Baru</h5>
                            <p class="text-center small text-muted">Daftar untuk mulai booking layanan</p>
                        </div>

                        <!-- Flash success -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Validation errors -->
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                        <li><?= esc($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('register') ?>" method="post" class="row g-3">
                            <?= csrf_field() ?>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama" class="form-control"
                                           placeholder="Masukkan nama lengkap"
                                           value="<?= old('nama') ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                                    <input type="text" name="username" class="form-control"
                                           placeholder="Pilih username unik"
                                           value="<?= old('username') ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control"
                                           placeholder="contoh@email.com"
                                           value="<?= old('email') ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telepon" class="form-control"
                                           placeholder="08xxxxxxxxxx"
                                           value="<?= old('telepon') ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Minimal 6 karakter" required>
                                </div>
                            </div>

                            <div class="col-12 mt-1">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                    <i class="bi bi-person-check me-1"></i>Daftar Sekarang
                                </button>
                            </div>

                            <div class="col-12 text-center">
                                <p class="small mb-0">Sudah punya akun?
                                    <a href="<?= base_url('login') ?>" class="fw-semibold text-primary">Login di sini</a>
                                </p>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
