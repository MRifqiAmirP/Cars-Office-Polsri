<?php $this->extend('layout/main'); ?>

<?php $this->section('content'); ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- Ringkasan Kendaraan -->
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Ringkasan Kendaraan</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main row">
                    <div class="col-md-4">
                        <img id="foto-kendaraan" src="assets/images/loading.gif" alt="Kendaraan" class="img-responsive" />
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Merk</th>
                                    <td id="merk"></td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td id="type"></td>
                                </tr>
                                <tr>
                                    <th>Nomor Polisi</th>
                                    <td id="no-polisi"></td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td id="tahun"></td>
                                </tr>
                                <tr>
                                    <th>Pemegang</th>
                                    <td id="pemegang"></td>
                                </tr>
                                <tr>
                                    <th>KM Servis Terakhir</th>
                                    <td id="km-terakhir"></td>
                                </tr>
                                <tr>
                                    <th>Terakhir Servis</th>
                                    <td id="terakhir-servis"></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td id="catatan">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Pengajuan Perbaikan -->
            <div class="col-md-6">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Pengajuan Servis</h4>
                        <span class="widget-toolbar">
                            <a href="#" id="link-lihat-semua-perbaikan">Lihat Semua</a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Bengkel</th>
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-perbaikan"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Riwayat Servis -->
            <div class="col-md-6">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Riwayat Servis</h4>
                        <span class="widget-toolbar">
                            <a href="#" id="link-lihat-semua-servis">Lihat Semua</a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Bengkel</th>
                                        <th>Jenis Perawatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-servis"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
</div> <!-- /.page-content -->
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    document.addEventListener("DOMContentLoaded", async () => {
        const userId = <?= session()->get('userId') ?>;
        const apiUrl = `/master/user/${userId}`;

        const tabelPerbaikan = document.getElementById("tabel-perbaikan");
        const tabelServis = document.getElementById("tabel-servis");

        try {
            const response = await fetch(apiUrl);
            const result = await response.json();

            if (result.statusCode === 200 && result.data) {
                const data = result.data;
                const userData = data.user;
                const carsData = data.kendaraan;

                const {
                    service_request,
                    service
                } = result.data;

                const sortedServiceRequest = service_request.sort((a, b) =>
                    new Date(b.created_at || b.tanggal) - new Date(a.created_at || a.tanggal) ||
                    b.id - a.id
                );

                const sortedService = service.sort((a, b) =>
                    new Date(b.tanggal) - new Date(a.tanggal) ||
                    b.id - a.id
                );

                const latestService = sortedService.length > 0 ? sortedService[0] : null;

                if (sortedServiceRequest.length === 0) {
                    tabelPerbaikan.innerHTML =
                        `<tr><td colspan="4" class="text-center">Data perbaikan tidak ada</td></tr>`;
                } else {
                    const req = sortedServiceRequest[0];
                    const namaBengkel = req.nama_bengkel ?? '-';
                    const nopol = carsData?.nopol?.trim() || '-';
                    const keluhan = req.keluhan;
                    const status = (req.status ?? '').toString().toLowerCase();

                    const labelClass =
                        status === 'selesai' ? 'success' :
                        status === 'pending' ? 'warning' :
                        status === 'waiting' ? 'info' :
                        status === 'proses' ? 'primary' : '';

                    tabelPerbaikan.innerHTML = `
                    <tr>
                    <td>${namaBengkel}</td>
                    <td>${keluhan}</td>
                    <td>
                        ${status === 'pending'
                        ? `Sedang di proses oleh admin`
                        : status === 'waiting'
                        ? `Silahkan antarkan mobil ke admin`
                        : status === 'proses'
                        ? `Sedang diservis`
                        : status === 'selesai'
                        ? `<button class="btn btn-xs btn-info">Detail</button>`
                        : ""}
                    </td>
                    <td><span class="label label-${labelClass}">${req.status}</span></td>
                    </tr>`;
                }

                if (sortedService.length === 0) {
                    tabelServis.innerHTML = `<tr><td colspan="4" class="text-center">Data servis tidak ada</td></tr>`;
                } else {
                    const srv = sortedService[0];
                    tabelServis.innerHTML = `
                    <tr>
                    <td>${srv.tanggal || '-'}</td>
                    <td>${srv.nama_bengkel}</td>
                    <td>
                        ${srv.jenis_perawatan && srv.jenis_perawatan.length > 0 
                        ? srv.jenis_perawatan.map(j => j.jenis_perawatan).join(', ')
                        : '-'}
                    </td>
                    <td><button class="btn btn-xs btn-default">Detail</button></td>
                    </tr>`;
                }

                const imgElement = document.getElementById('foto-kendaraan');
                imgElement.src = `uploads/cars/${carsData['foto_kendaraan']}`;
                imgElement.alt = `${carsData['merk']} ${carsData['type']}`;

                document.getElementById('merk').textContent = carsData['merk'] || '-';
                document.getElementById('type').textContent = carsData['type'] || '-';
                document.getElementById('no-polisi').textContent = carsData['nopol'] || '-';
                document.getElementById('tahun').textContent = carsData['tahun_pembuatan'] || '-';
                document.getElementById('pemegang').textContent = userData['nama'] || '-';
                document.getElementById('km-terakhir').textContent = latestService ?
                    (latestService['speedometer_saat_ini'] || '-') : '-';
                const lastServiceElement = document.getElementById('terakhir-servis');
                if (latestService) {
                    const tanggal = latestService['tanggal'] || '-';
                    const jenisPerawatan = latestService.jenis_perawatan && latestService.jenis_perawatan.length > 0 ?
                        latestService.jenis_perawatan.map(j => j.jenis_perawatan).join(', ') :
                        null;

                    if (jenisPerawatan) {
                        lastServiceElement.textContent = `${tanggal} (${jenisPerawatan})`;
                    } else {
                        lastServiceElement.textContent = tanggal;
                    }
                } else {
                    lastServiceElement.textContent = '-';
                }

                document.getElementById('catatan').textContent = carsData['keterangan'] || '-';
            } else {
                alert("Data tidak ditemukan!");
            }
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            tabelPerbaikan.innerHTML = `<tr><td colspan="4" class="text-center">Data perbaikan tidak ada</td></tr>`;
            tabelServis.innerHTML = `<tr><td colspan="4" class="text-center">Data servis tidak ada</td></tr>`;
        }
    });
</script>
<?php $this->endSection(); ?>