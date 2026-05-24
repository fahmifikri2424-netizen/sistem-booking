<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h4>Tambah User</h4>

<form action="<?= base_url('admin/users/store') ?>"
      method="post">

<div class="mb-3">

    <label>Username</label>

    <input type="text"
           name="username"
           class="form-control">

</div>

<div class="mb-3">

    <label>Nama</label>

    <input type="text"
           name="nama"
           class="form-control">

</div>

<div class="mb-3">

    <label>Email</label>

    <input type="email"
           name="email"
           class="form-control">

</div>

<div class="mb-3">

    <label>Password</label>

    <input type="password"
           name="password"
           class="form-control">

</div>

<div class="mb-3">

    <label>Telepon</label>

    <input type="text"
           name="telepon"
           class="form-control">

</div>

<div class="mb-3">

    <label>Status</label>

    <select name="status"
            class="form-control">

        <option value="aktif">Aktif</option>

        <option value="nonaktif">Nonaktif</option>

    </select>

</div>

<button type="submit"
        class="btn btn-success">

    Simpan

</button>

<a href="<?= base_url('admin/users') ?>"
   class="btn btn-secondary">

   Kembali
</a>

</form>

<?= $this->endSection() ?>