<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
  <h3 class="mb-3">Edit User</h3>

  <form action="<?= base_url('master/user/update/' . $user->id) ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nip" class="form-label">NIP</label>
        <input type="text" class="form-control" id="nip" name="nip"
               value="<?= esc($user->nip) ?>" required>
      </div>

      <div class="col-md-6">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama"
               value="<?= esc($user->nama) ?>" required>
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= esc($user->email) ?>" required>
      </div>

      <div class="col-md-6">
        <label for="no_handphone" class="form-label">No Handphone</label>
        <input type="text" class="form-control" id="no_handphone" name="no_handphone"
               value="<?= esc($user->no_handphone) ?>" required>
      </div>

      <div class="col-md-6">
        <label for="jabatan" class="form-label">Jabatan</label>
        <select id="jabatan" name="jabatan" class="form-select" required>
          <option value="Superuser" <?= $user->jabatan === 'Superuser' ? 'selected' : '' ?>>Superuser</option>
          <option value="admin" <?= $user->jabatan === 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="dosen" <?= $user->jabatan === 'dosen' ? 'selected' : '' ?>>Dosen</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="password" class="form-label">Password (opsional)</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Isi jika ingin ganti password">
      </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-success me-2">
        <i class="bi bi-save"></i> Update
      </button>
      <a href="<?= base_url('master/user') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
  </form>
</div>
<?= $this->endSection() ?>
