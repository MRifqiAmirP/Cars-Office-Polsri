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

				<form id="formUser">
					<input type="hidden" name="csrf" value="<?= csrf_hash(); ?>">
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
							<label for="role">Role</label>
							<select id="role" name="role" class="form-control" required>
								<option value="" selected disabled>Pilih Role</option>
								<option value="Superuser">Superuser</option>
								<option value="Admin">Admin</option>
								<option value="User">Dosen</option>
							</select>
						</div>

						<div id="jabatanGroup" class="form-group hidden">
							<label for="jabatan">Jabatan</label>
							<input type="text" id="jabatan" class="form-control" name="jabatan" placeholder="Masukkan jabatan">
						</div>

						<div class="form-group" id="passwordGroup">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
						</div>
					</div>

					<div class="modal-footer">
						<button id="saveButton" type="submit" class="btn btn-success">
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
<script type="text/javascript">
	if ('ontouchstart' in document.documentElement)
		document.write("<script src='<?= '/assets/js/jquery.mobile.custom.min.js'; ?>'>" + "<" + "/script>");
</script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const roleSelect = document.getElementById('role');
		const jabatanGroup = document.getElementById('jabatanGroup');
		const jabatanInput = document.getElementById('jabatan');

		roleSelect.addEventListener('change', function() {
			if (this.value === 'User') {
				jabatanGroup.classList.remove('hidden');
				jabatanInput.setAttribute('required', 'required');
			} else {
				jabatanGroup.classList.add('hidden');
				jabatanInput.removeAttribute('required');
				jabatanInput.value = '';
			}
		});
	})
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
				<td class="text-center">
					<div class="btn-group">
						<button type="button" onClick="edit(${user.id})" class="btn btn-xs btn-primary" style="margin-right: 8px;">Edit</button>
						<button class="btn btn-xs btn-danger" style="margin-left: 8px;">Hapus</button>
					</div>
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

<script>
	document.getElementById('formUser').addEventListener('submit', async function(e) {
		e.preventDefault()

		const formData = new FormData(this)
		const role = formData.get('role').toLowerCase()

		if (formData.get('role') !== 'User') {
			formData.set('jabatan', formData.get('role'))
		}

		formData.set('role', role)

		const data = {
			csrf_cookie: formData.get('csrf'),
		}
		formData.delete('csrf')

		try {
			const response = await fetch('/master/user/create', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': data.csrf_cookie
				},
				body: formData
			})

			const result = await response.json()

			if (result.status === 'success') {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil!',
					text: 'User berhasil ditambahkan',
					confirmButtonColor: '#28a745',
					showCancelButton: false,
					confirmButtonText: 'OK'
				}).then((result) => {
					if (result.isConfirmed) {
						location.reload()
					}
				})
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal Menambahkan User',
					text: result.message || 'Unknown error',
					confirmButtonColor: '#d33'
				})
			}
		} catch (error) {
			console.error('Error: ', error)
			Swal.fire({
				toast: true,
				icon: 'error',
				title: 'Gagal Menambahkan User',
				text: error.message,
				position: 'bottom-end',
				showConfirmButton: false,
				timer: 5000,
				timerProgressBar: true
			})
		}
	})
</script>

<script>
	async function edit(id) {
		const url = `/master/user/${id}`;
		document.getElementById('modalTambahUserLabel').innerHTML = 'Edit User';

		try {
			const result = await fetch(url);
			const response = await result.json();
			const userData = response.data.user;

			const role = toRoleCase(userData.role);

			if (userData && typeof userData === 'object') {
				document.getElementById('passwordGroup').style.display = 'none';
				document.getElementById('saveButton').setAttribute('onclick', `update(${id})`)
				document.getElementById('saveButton').setAttribute('type', 'button')
				toggleJabatanField(userData.role);

				document.getElementById('nip').value = userData.nip || '';
				document.getElementById('nama').value = userData.nama || '';
				document.getElementById('email').value = userData.email || '';
				document.getElementById('no_handphone').value = userData.no_handphone || '';
				document.getElementById('role').value = role || '';
				document.getElementById('jabatan').value = userData.jabatan || '';

				$('#modalTambahUser').modal('show');
			} else {
				console.error('Invalid response format:', response);
				alert('Gagal memuat data user');
			}

		} catch (error) {
			console.error('Gagal memuat data:', error);
			alert('Terjadi kesalahan saat memuat data');
		}
	}

	function toggleJabatanField(role) {
		const jabatanGroup = document.getElementById('jabatanGroup');
		if (role === 'user') {
			jabatanGroup.classList.remove('hidden');
		} else {
			jabatanGroup.classList.add('hidden');
		}
	}

	function toRoleCase(str) {
		return str.split(' ')
			.map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
			.join(' ');
	}
</script>

<script>
	async function update(id) {
		const url = `/master/user/update/${id}`
		const formData = new FormData(document.getElementById('formUser'))

		const data = {
			csrf_cookie: formData.get('csrf')
		}
		formData.delete('csrf')

		if (formData.get('role') !== 'User') {
			formData.set('jabatan', formData.get('role'))
		}

		const role = formData.get('role').toLowerCase()
		formData.set('role', role)

		try {
			const response = await fetch(url, {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': data.csrf_cookie
				},
				body: formData
			})

			const result = await response.json()

			if (result.status === 'success') {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil!',
					text: 'User berhasil diupdate',
					confirmButtonColor: '#28a745',
					showCancelButton: false,
					confirmButtonText: 'OK'
				}).then((result) => {
					if (result.isConfirmed) {
						location.reload()
					}
				})
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal update User',
					text: result.message || 'Unknown error',
					confirmButtonColor: '#d33'
				})
			}
		} catch (error) {
			console.error('Error: ', error)
			Swal.fire({
				toast: true,
				icon: 'error',
				title: 'Gagal update User',
				text: error.message,
				position: 'bottom-end',
				showConfirmButton: false,
				timer: 5000,
				timerProgressBar: true
			})
		}
	}
</script>
<?= $this->endSection(); ?>