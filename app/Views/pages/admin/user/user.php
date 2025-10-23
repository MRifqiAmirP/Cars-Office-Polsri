<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="#">Home</a>
		</li>

		<li>
			<a href="#">Master Data</a>
		</li>
		<li class="active">Users</li>
	</ul>
</div>
<div class="container-fluid">
	<h3 class="page-header" style="font-weight: bold;">Master Data User</h3>

	<button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahUser">
		<i class="glyphicon glyphicon-plus"></i> Tambah User
	</button>

	<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-labelledby="modalTambahUserLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<form action="<?= base_url('master/user/create') ?>" method="post">
					<?= csrf_field() ?>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span>&times;</span>
						</button>
						<h4 class="modal-title" id="modalTambahUserLabel">Tambah User</h4>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label for="nip">NIP</label>
							<input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" required>
						</div>

						<div class="form-group">
							<label for="nama">Nama</label>
							<input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
						</div>

						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
						</div>

						<div class="form-group">
							<label for="no_handphone">No Handphone</label>
							<input type="text" class="form-control" id="no_handphone" name="no_handphone" placeholder="Masukkan nomor HP" required>
						</div>

						<div class="form-group">
							<label for="jabatan">Jabatan</label>
							<select id="jabatan" name="jabatan" class="form-control" required>
								<option value="" selected disabled>Pilih Jabatan</option>
								<option value="Superuser">Superuser</option>
								<option value="admin">Admin</option>
								<option value="dosen">Dosen</option>
							</select>
						</div>

						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-success">
							<i class="glyphicon glyphicon-floppy-disk"></i> Simpan
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							<i class="glyphicon glyphicon-remove"></i> Batal
						</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<!-- Tabel User -->
	<div class="table-responsive" style="margin-top:20px;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr class="info">
					<th>No.</th>
					<th>NIP</th>
					<th>Nama</th>
					<th>Email</th>
					<th>No HP</th>
					<th>Jabatan</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<!-- DATA DIMUAT DI JAVASCRIPT -->
			</tbody>
		</table>
	</div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
	(() => {
		'use strict';
		const forms = document.querySelectorAll('.needs-validation');
		Array.from(forms).forEach(form => {
			form.addEventListener('submit', event => {
				if (!form.checkValidity()) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	})();
</script>
<script type="text/javascript">
	if ('ontouchstart' in document.documentElement)
		document.write("<script src='<?= '/assets/js/jquery.mobile.custom.min.js'; ?>'>" + "<" + "/script>");
</script>

<script>
	document.addEventListener('DOMContentLoaded', async function() {
		const tableBody = document.querySelector('table tbody');
		const url = '/master/user';

		try {
			const result = await fetch(url);
			const response = await result.json();

			const users = Array.isArray(response.data) ? response.data : response;

			if (users.length === 0) {
				tableBody.innerHTML = `
				<tr>
					<td colspan="7" class="text-center text-muted">
						Data belum ada
					</td>
				</tr>
			`;
				return;
			}

			tableBody.innerHTML = users.map((user, index) => `
			<tr>
				<td>${index + 1}</td>
				<td>${user.nip || '-'}</td>
				<td>${user.nama || '-'}</td>
				<td>${user.email || '-'}</td>
				<td>${user.no_handphone || '-'}</td>
				<td>${user.jabatan || '-'}</td>
				<td>
					<button class="btn btn-xs btn-primary">Edit</button>
					<button class="btn btn-xs btn-danger">Hapus</button>
				</td>
			</tr>
		`).join('');

		} catch (error) {
			console.error('Gagal memuat data:', error);
			tableBody.innerHTML = `
			<tr>
				<td colspan="7" class="text-center text-danger">
					Gagal memuat data
				</td>
			</tr>
		`;
		}
	})
</script>

<?= $this->endSection(); ?>