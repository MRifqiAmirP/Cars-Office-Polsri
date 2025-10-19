<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>
<div class="page-content">
    <div class="pull-right" style="margin-top:5px; margin-bottom: 10px;">
        <button class="btn btn-primary btn-sm" id="btnBuatRequest">
            <i class="ace-icon fa fa-plus-circle bigger-120"></i> Buat Pengajuan Servis
        </button>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <!-- REQUEST SERVIS -->
            <div class="widget-box">
                <div class="widget-header">
                    <h5 class="widget-title"><i class="fa fa-wrench"></i> Data Request Servis</h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <table id="tableRequest" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kendaraan</th>
                                    <th>Bengkel</th>
                                    <th>Keluhan</th>
                                    <th>Status</th>
                                    <th>Tanggal Request</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRequest">
                                <tr>
                                    <td colspan="8" class="center">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SERVIS SELESAI -->
            <div class="widget-box" style="margin-top: 5rem;">
                <div class="widget-header">
                    <h5 class="widget-title"><i class="fa fa-check-square"></i> Data Servis Kendaraan</h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <table id="tableService" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Bengkel</th>
                                    <th>Speedometer Sekarang</th>
                                    <th>Jenis Perawatan</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyService">
                                <tr>
                                    <td colspan="5" class="center">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="modalRequest" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Buat Pengajuan Servis</h4>
            </div>

            <form id="formRequest" class="form-horizontal" role="form">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="hidden" name="user_id" id="user_id" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="hidden" name="kendaraan_id" id="kendaraan_id" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Keluhan</label>
                        <div class="col-sm-9">
                            <textarea id="keluhan" name="keluhan" class="form-control" placeholder="Masukkan keluhan kendaraan..." required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ace-icon fa fa-check"></i> Kirim Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    const sessionUserId = <?= session('userId') ?? 2 ?>;
    let kendaraanId = null;

    document.addEventListener('DOMContentLoaded', async () => {
        await loadUserData();
        await loadServiceRequest();
        await loadService();

        document.getElementById('btnBuatRequest').addEventListener('click', () => {
            $('#modalRequest').modal('show');
        });

        document.getElementById('formRequest').addEventListener('submit', submitRequest);
    });

    // === GET USER DETAIL ===
    async function loadUserData() {
        try {
            const res = await api.get(`/master/user/${sessionUserId}`);
            const user = res.data.data.user;
            const kendaraan = res.data.data.kendaraan;

            kendaraanId = kendaraan.id;

            document.getElementById('user_id').value = user.id;
            document.getElementById('kendaraan_id').value = kendaraan.id;
        } catch (err) {
            console.error('Gagal memuat data user:', err);
        }
    }

    // === GET DATA REQUEST SERVIS ===
    async function loadServiceRequest() {
        const tbody = document.getElementById('tbodyRequest');
        try {
            const res = await api.get(`/api/service_request?user_id=${sessionUserId}`);
            const data = res.data.data;

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="5" class="center">Belum ada data</td></tr>`;
                return;
            }

            tbody.innerHTML = '';
            data.forEach((item, i) => {
                const statusLabel =
                    item.status === 'selesai' ?
                    '<span class="label label-success">Selesai</span>' :
                    item.status === 'proses' ?
                    '<span class="label label-info">Proses</span>' :
                    '<span class="label label-warning">Pending</span>';

                const date = new Date(item.created_at.date).toLocaleString('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'short'
                });

                tbody.innerHTML += `
          <tr>
            <td>${i + 1}</td>
            <td>${item.merk} ${item.type} (${item.plat_kendaran})</td>
            <td>${item.nama_bengkel ?? '-'}</td>
            <td>${item.keluhan}</td>
            <td>${statusLabel}</td>
            <td>${date}</td>
          </tr>
        `;
            });
        } catch (err) {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="5" class="center text-danger">Gagal memuat data</td></tr>`;
        }
    }

    // === LOAD DATA SERVIS ===
    async function loadService() {
        const tbody = document.getElementById('tbodyService');
        try {
            const res = await api.get(`/api/services?kendaraan_id=${kendaraanId}`);
            const data = res.data.data;

            // Kosongkan tbody
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="5" class="center">Belum ada data servis</td></tr>`;
                return;
            }

            data.forEach((item, i) => {
                const tanggal = new Date(item.tanggal).toLocaleDateString('id-ID');
                const perawatan = item.jenis_perawatan.map(j => j.jenis_perawatan).join(', ');
                const namaBengkel = item.nama_bengkel || '-';

                tbody.innerHTML += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${tanggal}</td>
                    <td>${namaBengkel}</td>
                    <td>${item.speedometer_saat_ini} Km</td>
                    <td>${perawatan}</td>
                </tr>
            `;
            });
        } catch (err) {
            console.error('Error loading service data:', err);
            tbody.innerHTML = `<tr><td colspan="5" class="center text-danger">Gagal memuat data servis</td></tr>`;
        }
    }

    // === SUBMIT SERVICE REQUEST ===
    async function submitRequest(event) {
        event.preventDefault();

        const form = document.getElementById('formRequest');
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            await api.post('/api/service_request/create', formData, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            $('#modalRequest').modal('hide');
            form.reset();
            loadServiceRequest();

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Service request berhasil dikirim.',
                showConfirmButton: false,
                timer: 1800
            });

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: err.response?.data?.messages?.error || 'Gagal mengirim service request.',
                confirmButtonText: 'Coba Lagi'
            });
        }
    }
</script>
<?= $this->endSection() ?>