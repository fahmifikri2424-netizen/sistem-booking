<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<!-- Menampilkan Data -->
 <h2>Dashboard Admin</h2>
<p>Username: <?= session('username') ?></p>
<a href="/logout">Logout</a>


<?= $this->endSection() ?>