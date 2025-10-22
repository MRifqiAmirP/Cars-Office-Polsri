<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h3 class="mb-3">Tambah Mobil</h3>

    <form action="<?= base_url('api/cars/create') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">

            <div class="col-md-6">
                <label for="user_id" class="form-label">User</label>
                <select id="user_id" name="user_id" class="form-select" required>
                    <option value="" selected disabled>Pilih User</option>
                    <?php if(!empty($users)): ?>
                        <?php foreach($users as $u): ?>
                            <option value="<?= esc($u->id) ?>"><?= esc($u->nama) ?> (<?= esc($u->nip) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="nopol" class="form-label">No. Polisi</label>
                <input type="text" class="form-control" id="nopol" name="nopol" placeholder="Masukkan No. Polisi" required>
            </div>

            <div class="col-md-6">
                <label for="merk" class="form-label">Merk</label>
                <input type="text" class="form-control" id="merk" name="merk" placeholder="Merk kendaraan" required>
            </div>

            <div class="col-md-6">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control" id="type" name="type" placeholder="Type kendaraan" required>
            </div>

            <div class="col-md-6">
                <label for="foto_kendaraan" class="form-label">Foto Kendaraan</label>
                <input type="file" class="form-control" id="foto_kendaraan" name="foto_kendaraan">
            </div>

            <div class="col-md-6">
                <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                <input type="number" class="form-control" id="tahun_pembuatan" name="tahun_pembuatan" placeholder="Tahun pembuatan">
            </div>

            <div class="col-md-6">
                <label for="no_bpkb" class="form-label">No. BPKB</label>
                <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" placeholder="No. BPKB">
            </div>

            <div class="col-md-6">
                <label for="no_mesin" class="form-label">No. Mesin</label>
                <input type="text" class="form-control" id="no_mesin" name="no_mesin" placeholder="No. Mesin">
            </div>

            <div class="col-md-6">
                <label for="no_rangka" class="form-label">No. Rangka</label>
                <input type="text" class="form-control" id="no_rangka" name="no_rangka" placeholder="No. Rangka">
            </div>

            <div class="col-md-12">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan"></textarea>
            </div>

        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>
    </form>
</div>


<div class="container mt-4">
    <h3 class="mb-3">Daftar Mobil</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Foto Kendaraan</th>
                    <th scope="col">Nopol</th>
                    <th scope="col">Merk</th>
                    <th scope="col">Type</th>
                    <th scope="col">No BPKB</th>
                    <th scope="col">No Mesin</th>
                    <th scope="col">No Rangka</th>
                    <th scope="col">Tahun Pembuatan</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $car): ?>
                        <tr>
                            <td><?= esc($car->id) ?></td>
                            <td><?= esc($car->user_id) ?></td>
                            <td>
                                <?php if ($car->foto_kendaraan): ?>
                                    <img src="<?= base_url('uploads/cars/' . $car->foto_kendaraan) ?>" alt="Foto Kendaraan" width="80">
                                <?php else: ?>
                                    <span class="text-muted fst-italic">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($car->nopol) ?></td>
                            <td><?= esc($car->merk) ?></td>
                            <td><?= esc($car->type) ?></td>
                            <td><?= esc($car->no_bpkb) ?></td>
                            <td><?= esc($car->no_mesin) ?></td>
                            <td><?= esc($car->no_rangka) ?></td>
                            <td><?= esc($car->tahun_pembuatan) ?></td>
                            <td><?= esc($car->keterangan) ?></td>
                            <td><?= esc($car->created_at) ?></td>
                            <td><?= esc($car->updated_at) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13" class="text-center text-muted py-3">Tidak ada data mobil.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
