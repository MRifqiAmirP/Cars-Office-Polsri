<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="#">Home</a>
		</li>
		<li class="active">Request Service</li>
	</ul>
</div>
<div class="container-fluid">
	<h3 class="page-header" style="font-weight: bold;">Request Service</h3>
	<div class="table-responsive" style="margin-top:20px;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr class="info">
					<th>No.</th>
					<th>Nama Pemilik</th>
					<th>Merk</th>
					<th>Type</th>
					<th>Plat Kendaraan</th>
					<th>Status</th>
					<th>Bengkel</th>
					<th>aksi</th>
				</tr>
			</thead>
			<tbody>
				<!-- DATA DIMUAT DI JAVASCRIPT -->
			</tbody>
		</table>
	</div>
</div>

<!-- form -->

<div class="modal fade" id="modalTambahKendaraan" tabindex="-1" role="dialog" aria-labelledby="modalTambahKendaraanLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="formCars">
					<input type="hidden" name="csrf" value="<?= csrf_hash(); ?>">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span>&times;</span>
						</button>
						<h4 class="modal-title" id="modalTambahKendaraanLabel">Tambah Kendaraan</h4>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label for="keterangan">Keterangan</label>
							<input type="varchar" id="keterangan" class="form-control" name="total harga" placeholder="Masukkan keterangan">
						</div>

						<div class="form-group">
							<label for="fileUpload" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Unggah Foto Kendaraan</label>

							<div class="drop-zone" id="dropZone"
								style="border: 2px dashed #ccc; border-radius: 8px; padding: 2rem; text-align: center; transition: all 0.3s ease; background: #f8f9fa; cursor: pointer;">

								<div class="drop-zone-content">
									<div style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;">📁</div>
									<span style="display: block; font-size: 1.1rem; color: #495057; margin-bottom: 0.5rem;">Drag & drop foto di sini</span>
									<span style="display: block; color: #6c757d; margin-bottom: 0.5rem;">atau</span>
									<button type="button" style="background: #007bff; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Pilih File</button>
									<input type="file" class="drop-zone-input" id="fileUpload" name="foto_nota" hidden style="opacity: 0;">
								</div>
							</div>

							<div class="file-info" id="fileInfo" style="margin-top: 1rem; padding: 0.75rem; background: #e8f5e8; border-radius: 4px; display: none;"></div>
						</div>

						<input type="checkbox" id="delete_foto" name="delete_foto" hidden>
					</div>

					<div class="modal-footer">
						<button id="saveButton" type="submit" class="btn btn-success">>
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

	<!-- MODAL DETAIL -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="modalDetailLabel">Detail Service Request</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tr>
						<th>User</th>
						<td id="detail_user"></td>
					</tr>
					<tr>
						<th>Kendaraan</th>
						<td id="detail_kendaraan"></td>
					</tr>
					<tr>
						<th>Bengkel</th>
						<td id="detail_bengkel"></td>
					</tr>
					<tr>
						<th>Keluhan</th>
						<td id="detail_keluhan"></td>
					</tr>
					<tr>
						<th>Status</th>
						<td id="detail_status"></td>
					</tr>
					<tr>
						<th>Total Harga</th>
						<td id="detail_total"></td>
					</tr>
					<tr>
						<th>File</th>
						<td id="detail_file"></td>
					</tr>
					<tr>
						<th>Foto Nota</th>
						<td id="detail_foto_nota"></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				<button class="btn btn-primary" >pdf</button>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>



<?= $this->section('scripts'); ?>
<script type="text/javascript">
	if ('ontouchstart' in document.documentElement)
		document.write("<script src='<?= '/assets/js/jquery.mobile.custom.min.js'; ?>'>" + "<" + "/script>");
</script>

<script>
	document.addEventListener('DOMContentLoaded', async function() {
		const url = '/master/user?nameOnly=true'
		const userOpt = document.getElementById('user_id')

		try {
			const response = await fetch(url)
			const result = await response.json()
			const users = result.data;

			const optionsHTML = `
            <option value="">Pilih pemilik kendaraan</option>
            ${users.map((user) => `
            <option value="${user.id}">${user.nama}</option>
            `).join('')}
        `;

			userOpt.innerHTML = optionsHTML;
		} catch (error) {
			console.error(error);
		}
	})
</script>
<!-- data table -->
<script>
document.addEventListener('DOMContentLoaded', async function() {
	const tableBody = document.querySelector('table tbody');

	try {
		const resService = await fetch('/api/service_request');
		const resultService = await resService.json();
		const reqService = Array.isArray(resultService.data) ? resultService.data : [];

		const resBengkel = await fetch('/api/bengkel');
		const resultBengkel = await resBengkel.json();
		const bengkelList = resultBengkel.data?.bengkel || [];

		// Jika data kosong
		if (reqService.length === 0) {
			tableBody.innerHTML = `
				<tr>
					<td colspan="8" class="text-center text-muted py-3">
						<i>Tidak ada data service request</i>
					</td>
				</tr>`;
			return;
		}

		tableBody.innerHTML = reqService.map((data, index) => {
			const options = bengkelList.map(b => `
				<option value="${b.id}" ${data.bengkel_id == b.id ? 'selected' : ''}>
					${b.nama_bengkel}
				</option>
			`).join('');

			// Tombol input harga & nota 
			const disabled = data.status !== 'selesai' ? 'disabled' : '';

			return `
				<tr>
					<td>${index + 1}</td>
					<td>${data.user_nama || '-'}</td>
					<td>${data.merk || '-'}</td>
					<td>${data.type || '-'}</td>
					<td>${data.plat_kendaran || '-'}</td>
					<td>
						<select class="form-select status" data-id="${data.id}">
							<option class="label label-warning" value="pending" ${data.status == 'pending' ? 'selected' : ''}>Pending</option>
							<option class="label label-primary" value="waiting" ${data.status == 'waiting' ? 'selected' : ''}>Waiting</option>
							<option class="label label-info" value="proses" ${data.status == 'proses' ? 'selected' : ''}>Proses</option>
							<option class="label label-succes" value="selesai" ${data.status == 'selesai' ? 'selected' : ''}>Selesai</option>
						</select>
					</td>
					<td>
						<select class="form-select pilih-bengkel" data-id="${data.id}">
							<option value="">-- Pilih Bengkel --</option>
							${options}
						</select>
					</td>
					<td class="text-center">
						<button class="btn btn-success btn-input" data-id="${data.id}">Update</button>
						<button class="btn btn-primary btn-harga-nota" onClick="edit(${data.id})" ${disabled}>Input Harga & Nota</button>
						<button class="btn btn-info btn-sm" onclick="showDetail(${data.id})">
							<i class="fa fa-eye"></i> Detail
						</button>
				</tr>
			`;
		}).join('');
// status kondisi
document.querySelectorAll('.status').forEach(select => {
  const updateClass = (el) => {

    el.classList.remove('label-warning', 'label-primary', 'label-info', 'label-success', 'text-white');

    switch (el.value) {
      case 'pending':
        el.classList.add('label-warning', 'text-white');
        break;
      case 'waiting':
        el.classList.add('label-primary', 'text-white');
        break;
      case 'proses':
        el.classList.add('label-info', 'text-white');
        break;
      case 'selesai':
        el.classList.add('label-success', 'text-white');
        break;
    }
  };
  updateClass(select);

  select.addEventListener('change', function() {
    updateClass(this);
  });
});

		document.querySelectorAll('.btn-input').forEach(btn => {
			btn.addEventListener('click', async function() {
				const id = this.dataset.id;
				const selectStatus = document.querySelector(`.status[data-id="${id}"]`);
				const selectBengkel = document.querySelector(`.pilih-bengkel[data-id="${id}"]`);
				const status = selectStatus.value;
				const bengkelId = selectBengkel.value;

				const formData = new FormData();
				formData.append('bengkel_id', bengkelId);
				formData.append('status', status);

				try {
					const csrfToken = '<?= csrf_hash() ?>';
					const response = await fetch(`/api/service_request/update/${id}`, {
						method: 'POST',
						headers: { 'X-CSRF-TOKEN': csrfToken },
						body: formData
					});

					const result = await response.json();
					console.log('Response dari server:', result);

					if (result.status === 'success') {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil!',
							text: 'Bengkel dan status berhasil diupdate.',
							confirmButtonColor: '#28a745',
						}).then(() => location.reload());
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Gagal update bengkel',
							text: result.message || 'Unknown error',
							confirmButtonColor: '#d33'
						});
					}
				} catch (error) {
					console.error('Error: ', error);
					Swal.fire({
						toast: true,
						icon: 'error',
						title: 'Gagal Menambahkan update bengkel',
						text: error.message,
						position: 'bottom-end',
						showConfirmButton: false,
						timer: 5000,
						timerProgressBar: true
					});
				}
			});
		});


	} catch (error) {
		console.error('Gagal memuat data:', error);
		tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>`;
	}
});
</script>

<script>
	async function edit(id) {
		const url = `/api/service_request/${id}`;
		document.getElementById('modalTambahKendaraanLabel').innerHTML = 'Edit Kendaraan';

		try {
			const result = await fetch(url);
			const response = await result.json();
			const data = response.data;

			if (data && typeof data === 'object') {
				document.getElementById('saveButton').setAttribute('onclick', `update(${id})`)
				document.getElementById('saveButton').setAttribute('type', 'button')

				document.getElementById('keterangan').value = data.total_harga || '';
				if(data.foto_nota) {
					fileHandler.setExistingFile(data.foto_nota, data.fota_nota);
				}

				$('#modalTambahKendaraan').modal('show');
			} else {
				console.error('Invalid response format:', response);
				alert('Gagal memuat data user');
			}

		} catch (error) {
			console.error('Gagal memuat data:', error);
			alert('Terjadi kesalahan saat memuat data');
		}
	}
</script>

<script>
	async function update(id) {
		const url = `/api/service_request/update/${id}`
		const formData = new FormData(document.getElementById('formCars'))

		const data = {
			csrf_cookie: formData.get('csrf')
		}


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
<script>
	async function showDetail(id) {
		try {
			const response = await fetch(`/api/service_request/${id}`);
			const result = await response.json();

			if (result.status === 'success' && result.data.length > 0) {
				const d = result.data[0];

				document.getElementById('detail_user').textContent = `${d.user_nama} (${d.user_email})`;
				document.getElementById('detail_kendaraan').textContent = `${d.merk} ${d.type} - ${d.plat_kendaran}`;
				document.getElementById('detail_bengkel').textContent = d.nama_bengkel ?? '-';
				document.getElementById('detail_keluhan').textContent = d.keluhan ?? '-';
				document.getElementById('detail_status').textContent = d.status ?? '-';
				document.getElementById('detail_total').textContent = d.total_harga ?? '-';

				// tampilkan file (jika ada)
				const fileCell = document.getElementById('detail_file');
				fileCell.innerHTML = d.file
					? `<a href="/uploads/service_requests/file/${d.file}" target="_blank">Lihat File</a>`
					: '-';

				const notaCell = document.getElementById('detail_foto_nota');
				notaCell.innerHTML = d.foto_nota
					? `<img src="/uploads/service_requests/nota/${d.foto_nota}" alt="Nota" class="img-fluid" style="max-height:200px;">`
					: '-';

				$('#modalDetail').modal('show');
			} else {
				Swal.fire('Gagal', 'Data tidak ditemukan', 'error');
			}
		} catch (err) {
			console.error(err);
			Swal.fire('Error', 'Gagal mengambil data detail', 'error');
		}
	}
</script>

<script>
	class FileUploadHandler {
		constructor(dropZoneId, inputId, infoId) {
			this.dropZone = document.getElementById(dropZoneId);
			this.fileInput = document.getElementById(inputId);
			this.fileInfo = document.getElementById(infoId);
			this.currentFile = null;
			this.existingFileUrl = null;
			this.MAX_FILE_SIZE = 3 * 1024 * 1024;
			this.init();
		}

		init() {
			this.dropZone.addEventListener('click', () => {
				this.fileInput.click();
			});

			['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
				this.dropZone.addEventListener(eventName, this.preventDefaults, false);
			});

			['dragenter', 'dragover'].forEach(eventName => {
				this.dropZone.addEventListener(eventName, () => this.highlight(), false);
			});

			['dragleave', 'drop'].forEach(eventName => {
				this.dropZone.addEventListener(eventName, () => this.unhighlight(), false);
			});

			this.dropZone.addEventListener('drop', (e) => this.handleDrop(e), false);
			this.fileInput.addEventListener('change', (e) => this.handleFiles(e.target.files), false);
		}

		setExistingFile(fileUrl, fileName = null) {
			this.existingFileUrl = fileUrl;
			this.displayExistingFileInfo(fileUrl, fileName);
		}

		displayExistingFileInfo(fileUrl, fileName = null) {
			const fileNameToShow = fileName || fileUrl.split('/').pop() || 'Gambar dari database';

			this.fileInfo.innerHTML = `
            <div onclick="fileHandler.previewExistingImage()" style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: white; border-radius: 8px; cursor: pointer; border: 2px solid #28a745; margin-bottom: 0.5rem;">
                <div style="display: flex; align-items: center; flex: 1;">
                    <div style="width: 50px; height: 50px; border-radius: 6px; overflow: hidden; margin-right: 1rem; border: 1px solid #eee; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <img src="${fileUrl}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.innerHTML='📷';">
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">${fileNameToShow}</div>
                        <div style="color: #6c757d; font-size: 0.875rem; margin-bottom: 0.5rem;">
                            File dari database • <span style="color: #28a745;">✓ Data tersimpan</span>
                        </div>
                        <div style="background: #e9ecef; border-radius: 10px; height: 6px; overflow: hidden;">
                            <div style="background: #28a745; height: 100%; width: 100%; border-radius: 10px;"></div>
                        </div>
                        <div style="color: #6c757d; font-size: 0.75rem; margin-top: 0.25rem;">
                            File existing - upload baru akan menggantikan
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
					<button type="button" onclick="event.stopPropagation(); fileHandler.removeExistingFile()" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0.5rem; margin-left: 0.5rem; border-radius: 4px; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
						✕
					</button>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; color: #28a745; font-size: 0.8rem; padding: 0 0.5rem;">
                <span>✅ File dari database</span>
                <span>Klik untuk preview atau upload file baru</span>
            </div>
        `;
			this.fileInfo.style.display = 'block';

			this.fileInput.value = '';
			this.currentFile = null;
		}

		previewExistingImage() {
			if (!this.existingFileUrl) return;

			const modalOverlay = document.createElement('div');
			modalOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            cursor: pointer;
            padding: 20px;
        `;

			const imageContainer = document.createElement('div');
			imageContainer.style.cssText = `
            position: relative;
            max-width: 80%;
            max-height: 80%;
            width: auto;
            height: auto;
            cursor: default;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            align-items: center;
        `;

			const img = document.createElement('img');
			img.src = this.existingFileUrl;
			img.style.cssText = `
            max-width: 100%;
            max-height: 70vh;
            width: auto;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            object-fit: contain;
            display: block;
        `;

			img.onerror = () => {
				img.style.display = 'none';
				const errorDiv = document.createElement('div');
				errorDiv.style.cssText = `
                background: #f8f9fa;
                padding: 2rem;
                border-radius: 8px;
                text-align: center;
                color: #6c757d;
            `;
				errorDiv.innerHTML = `
                <div style="font-size: 3rem; margin-bottom: 1rem;">📷</div>
                <div style="font-weight: 600; margin-bottom: 0.5rem;">Gambar tidak dapat dimuat</div>
                <div>URL: ${this.existingFileUrl}</div>
            `;
				imageContainer.appendChild(errorDiv);
			};

			const fileInfo = document.createElement('div');
			fileInfo.style.cssText = `
            margin-top: 20px;
            text-align: center;
            color: white;
            font-size: 1rem;
            z-index: 100000;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
        `;

			const fileName = this.existingFileUrl.split('/').pop() || 'Gambar dari database';

			fileInfo.innerHTML = `
            <div style="font-weight: 500; margin-bottom: 0.5rem;">${fileName}</div>
            <div style="color: #ccc; font-size: 0.9rem; margin-bottom: 0.5rem;">
                File dari database • <span style="color: #28a745;">✓ Data tersimpan</span>
            </div>
            <div style="color: #4CAF50; font-size: 0.8rem;">
                Klik di luar gambar untuk menutup
            </div>
        `;

			const controlsContainer = document.createElement('div');
			controlsContainer.style.cssText = `
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100001;
        `;

			const downloadButton = document.createElement('button');
			downloadButton.innerHTML = '⬇️';
			downloadButton.title = 'Download gambar';
			downloadButton.style.cssText = `
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: background 0.2s;
        `;
			downloadButton.onmouseover = function() {
				this.style.background = 'white';
			};
			downloadButton.onmouseout = function() {
				this.style.background = 'rgba(255, 255, 255, 0.95)';
			};
			downloadButton.onclick = (e) => {
				e.stopPropagation();
				this.downloadExistingImage();
			};

			const closeButton = document.createElement('button');
			closeButton.innerHTML = '✕';
			closeButton.style.cssText = `
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: background 0.2s;
        `;
			closeButton.onmouseover = function() {
				this.style.background = 'white';
			};
			closeButton.onmouseout = function() {
				this.style.background = 'rgba(255, 255, 255, 0.95)';
			};

			imageContainer.appendChild(img);
			controlsContainer.appendChild(downloadButton);
			controlsContainer.appendChild(closeButton);
			imageContainer.appendChild(controlsContainer);
			modalOverlay.appendChild(imageContainer);
			modalOverlay.appendChild(fileInfo);

			document.body.appendChild(modalOverlay);

			document.body.style.overflow = 'hidden';

			const closeModal = () => {
				if (document.body.contains(modalOverlay)) {
					document.body.removeChild(modalOverlay);
				}
				document.body.style.overflow = '';
			};

			modalOverlay.addEventListener('click', (e) => {
				if (e.target === modalOverlay) {
					closeModal();
				}
			});

			closeButton.addEventListener('click', closeModal);

			const handleKeyDown = (e) => {
				if (e.key === 'Escape') {
					closeModal();
					document.removeEventListener('keydown', handleKeyDown);
				}
			};
			document.addEventListener('keydown', handleKeyDown);

			imageContainer.addEventListener('click', (e) => {
				e.stopPropagation();
			});
		}

		downloadExistingImage() {
			if (!this.existingFileUrl) return;

			const a = document.createElement('a');
			a.href = this.existingFileUrl;
			a.download = this.existingFileUrl.split('/').pop() || 'download';
			a.target = '_blank';
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
		}

		removeExistingFile() {
			this.existingFileUrl = null;
			this.fileInput.value = '';
			this.currentFile = null;
			this.fileInfo.style.display = 'none';
			this.fileInfo.innerHTML = '';
			document.getElementById('delete_foto').checked = true;

			this.showSuccess('File existing telah dihapus. Silakan upload file baru.');
		}

		getFileStatus() {
			return {
				hasExistingFile: !!this.existingFileUrl,
				hasNewFile: !!this.currentFile,
				existingFileUrl: this.existingFileUrl,
				newFile: this.currentFile
			};
		}

		reset() {
			this.existingFileUrl = null;
			this.currentFile = null;
			this.fileInput.value = '';
			this.fileInfo.style.display = 'none';
			this.fileInfo.innerHTML = '';
		}

		preventDefaults(e) {
			e.preventDefault();
			e.stopPropagation();
		}

		highlight() {
			this.dropZone.style.borderColor = '#007bff';
			this.dropZone.style.background = '#e3f2fd';
			this.dropZone.style.transform = 'scale(1.02)';
		}

		unhighlight() {
			this.dropZone.style.borderColor = '#ccc';
			this.dropZone.style.background = '#f8f9fa';
			this.dropZone.style.transform = 'scale(1)';
		}

		handleDrop(e) {
			const dt = e.dataTransfer;
			const files = dt.files;
			this.handleFiles(files);
		}

		handleFiles(files) {
			if (files.length > 0) {
				const file = files[0];

				if (file.size > this.MAX_FILE_SIZE) {
					this.showError(`File terlalu besar! Maksimal 3MB. File Anda: ${this.formatFileSize(file.size)}`);
					return;
				}

				if (!file.type.startsWith('image/')) {
					this.showError('Hanya file gambar yang diizinkan!');
					return;
				}

				this.currentFile = file;
				this.displayFileInfo(this.currentFile);
			}
		}

		showError(message) {
			this.fileInput.value = '';

			const errorDiv = document.createElement('div');
			errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            max-width: 300px;
            animation: slideIn 0.3s ease;
        `;

			errorDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.2rem;">⚠️</span>
                <div>
                    <div style="font-weight: bold; margin-bottom: 0.25rem;">Error</div>
                    <div style="font-size: 0.9rem;">${message}</div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: white; cursor: pointer; margin-left: auto; font-size: 1.2rem;">
                    ✕
                </button>
            </div>
        `;

			document.body.appendChild(errorDiv);

			setTimeout(() => {
				if (document.body.contains(errorDiv)) {
					document.body.removeChild(errorDiv);
				}
			}, 5000);
		}

		showSuccess(message) {
			const successDiv = document.createElement('div');
			successDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            max-width: 300px;
            animation: slideIn 0.3s ease;
        `;

			successDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.2rem;">✅</span>
                <div>
                    <div style="font-weight: bold; margin-bottom: 0.25rem;">Sukses</div>
                    <div style="font-size: 0.9rem;">${message}</div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: white; cursor: pointer; margin-left: auto; font-size: 1.2rem;">
                    ✕
                </button>
            </div>
        `;

			document.body.appendChild(successDiv);

			setTimeout(() => {
				if (document.body.contains(successDiv)) {
					document.body.removeChild(successDiv);
				}
			}, 5000);
		}

		displayFileInfo(file) {
			const fileSize = this.formatFileSize(file.size);
			const isImage = file.type.startsWith('image/');
			const fileSizePercent = (file.size / this.MAX_FILE_SIZE) * 100;

			this.fileInfo.innerHTML = `
            <div onclick="${isImage ? 'fileHandler.previewImage()' : ''}" style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: white; border-radius: 8px; ${isImage ? 'cursor: pointer;' : ''} ${isImage ? 'border: 2px solid #007bff;' : 'border: 1px solid #ddd;'} margin-bottom: 0.5rem;">
                <div style="display: flex; align-items: center; flex: 1;">
                    ${isImage ? 
                        `<div style="width: 50px; height: 50px; border-radius: 6px; overflow: hidden; margin-right: 1rem; border: 1px solid #eee;">
                            <img src="${URL.createObjectURL(file)}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>` 
                        : 
                        `<div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; font-size: 1.5rem; border: 1px solid #eee;">
                            📄
                        </div>`
                    }
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">${file.name}</div>
                        <div style="color: #6c757d; font-size: 0.875rem; margin-bottom: 0.5rem;">
                            ${fileSize} • ${file.type || 'Unknown type'}
                        </div>
                        <div style="color: #6c757d; font-size: 0.75rem; margin-top: 0.25rem;">
                            ${Math.round(fileSizePercent)}% dari batas 3MB
                        </div>
                    </div>
                </div>
                <button type="button" onclick="event.stopPropagation(); fileHandler.removeFile()" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0.5rem; margin-left: 0.5rem; border-radius: 4px; transition: background 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                    ✕
                </button>
            </div>
            ${isImage ? `
                <div style="display: flex; justify-content: space-between; align-items: center; color: #007bff; font-size: 0.8rem; padding: 0 0.5rem;">
                    <span>✅ File memenuhi syarat (≤ 3MB)</span>
                    <span>Klik untuk preview gambar</span>
                </div>
            ` : ''}
        `;
			this.fileInfo.style.display = 'block';

			this.existingFileUrl = null;
		}

		previewImage() {
			if (!this.currentFile || !this.currentFile.type.startsWith('image/')) {
				return;
			}

			const modalOverlay = document.createElement('div');
			modalOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            cursor: pointer;
            padding: 20px;
        `;

			const imageContainer = document.createElement('div');
			imageContainer.style.cssText = `
            position: relative;
            max-width: 80%;
            max-height: 80%;
            width: auto;
            height: auto;
            cursor: default;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            align-items: center;
        `;

			const img = document.createElement('img');
			img.src = URL.createObjectURL(this.currentFile);
			img.style.cssText = `
            max-width: 100%;
            max-height: 70vh;
            width: auto;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            object-fit: contain;
            display: block;
        `;

			// Create file info dengan ukuran file
			const fileInfo = document.createElement('div');
			fileInfo.style.cssText = `
            margin-top: 20px;
            text-align: center;
            color: white;
            font-size: 1rem;
            z-index: 100000;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 8px;
        `;

			const fileSize = this.formatFileSize(this.currentFile.size);
			const fileSizePercent = (this.currentFile.size / this.MAX_FILE_SIZE) * 100;
			const sizeStatus = fileSizePercent <= 100 ? '✅' : '⚠️';

			fileInfo.innerHTML = `
            <div style="font-weight: 500; margin-bottom: 0.5rem;">${this.currentFile.name}</div>
            <div style="color: #ccc; font-size: 0.9rem; margin-bottom: 0.5rem;">
                ${this.currentFile.type} • ${fileSize} • ${sizeStatus} ${Math.round(fileSizePercent)}% dari 3MB
            </div>
            <div style="color: #4CAF50; font-size: 0.8rem;">
                Klik di luar gambar untuk menutup
            </div>
        `;

			const controlsContainer = document.createElement('div');
			controlsContainer.style.cssText = `
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100001;
        `;

			const downloadButton = document.createElement('button');
			downloadButton.innerHTML = '⬇️';
			downloadButton.title = 'Download gambar';
			downloadButton.style.cssText = `
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: background 0.2s;
        `;
			downloadButton.onmouseover = function() {
				this.style.background = 'white';
			};
			downloadButton.onmouseout = function() {
				this.style.background = 'rgba(255, 255, 255, 0.95)';
			};
			downloadButton.onclick = (e) => {
				e.stopPropagation();
				this.downloadImage();
			};

			const closeButton = document.createElement('button');
			closeButton.innerHTML = '✕';
			closeButton.style.cssText = `
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: background 0.2s;
        `;
			closeButton.onmouseover = function() {
				this.style.background = 'white';
			};
			closeButton.onmouseout = function() {
				this.style.background = 'rgba(255, 255, 255, 0.95)';
			};

			// Assemble modal
			imageContainer.appendChild(img);
			controlsContainer.appendChild(downloadButton);
			controlsContainer.appendChild(closeButton);
			imageContainer.appendChild(controlsContainer);
			modalOverlay.appendChild(imageContainer);
			modalOverlay.appendChild(fileInfo);

			document.body.appendChild(modalOverlay);

			document.body.style.overflow = 'hidden';

			const closeModal = () => {
				if (document.body.contains(modalOverlay)) {
					document.body.removeChild(modalOverlay);
				}
				URL.revokeObjectURL(img.src);
				document.body.style.overflow = '';
			};

			modalOverlay.addEventListener('click', (e) => {
				if (e.target === modalOverlay) {
					closeModal();
				}
			});

			closeButton.addEventListener('click', closeModal);

			const handleKeyDown = (e) => {
				if (e.key === 'Escape') {
					closeModal();
					document.removeEventListener('keydown', handleKeyDown);
				}
			};
			document.addEventListener('keydown', handleKeyDown);

			imageContainer.addEventListener('click', (e) => {
				e.stopPropagation();
			});
		}

		downloadImage() {
			if (!this.currentFile) return;

			const url = URL.createObjectURL(this.currentFile);
			const a = document.createElement('a');
			a.href = url;
			a.download = this.currentFile.name;
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
			URL.revokeObjectURL(url);
		}

		removeFile() {
			this.fileInput.value = '';
			this.currentFile = null;
			this.fileInfo.style.display = 'none';
			this.fileInfo.innerHTML = '';
		}

		formatFileSize(bytes) {
			if (bytes === 0) return '0 Bytes';
			const k = 1024;
			const sizes = ['Bytes', 'KB', 'MB', 'GB'];
			const i = Math.floor(Math.log(bytes) / Math.log(k));
			return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
		}
	}

	const fileHandler = new FileUploadHandler('dropZone', 'fileUpload', 'fileInfo');
</script>

<?= $this->endSection(); ?>