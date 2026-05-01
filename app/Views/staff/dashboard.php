<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Menampilkan  -->
 <h2>Dashboard Staff</h2>
<p>Username: <?= session('username') ?></p>
<a href="/logout">Logout</a>


<?= $this->endSection() ?>