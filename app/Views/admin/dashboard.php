<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="pagetitle mb-4">
    <h1 class="dashboard-title">Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<?= $this->endSection() ?>