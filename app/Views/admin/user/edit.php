<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<h4>Edit User</h4>

<form action="<?= base_url('admin/users/update/'.$user['id_user']) ?>"
      method="post">

<div class="mb-3">

    <label>Username</label>

    <input type="text"
           name="username"
           value="<?= $user['username'] ?>"
           class="form-control">

</div>

<div class="mb-3">

    <label>Nama</label>

    <input type="text"
           name="nama"
           value="<?= $user['nama'] ?>"
           class="form-control">

</div>

<div class="mb-3">

    <label>Email</label>

    <input type="email"
           name="email"
           value="<?= $user['email'] ?>"
           class="form-control">

</div>

<div class="mb-3">

    <label>Password Baru</label>

    <input type="password"
           name="password"
           class="form-control">

</div>

<div class="mb-3">

    <label>Telepon</label>

    <input type="text"
           name="telepon"
           value="<?= $user['telepon'] ?>"
           class="form-control">

</div>

<div class="mb-3">

    <label>Status</label>

    <select name="status"
            class="form-control">

        <option value="aktif"
        <?= $user['status'] == 'aktif' ? 'selected' : '' ?>>

        Aktif

        </option>

        <option value="nonaktif"
        <?= $user['status'] == 'nonaktif' ? 'selected' : '' ?>>

        Nonaktif

        </option>

    </select>

</div>

<button type="submit"
        class="btn btn-primary">

    Update

</button>

<a href="<?= base_url('admin/users') ?>"
   class="btn btn-secondary">

   Kembali
</a>

</form>

<?= $this->endSection() ?>