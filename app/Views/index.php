<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<p>Username: <?= session('username') ?></p>
<p>Role: <?= session('role') ?></p>
<a href="/logout">Logout</a>
<?= $this->endSection() ?>