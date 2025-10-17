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
                        <img src="path/to/car.jpg" alt="Kendaraan" class="img-responsive" />
                    </div>
                    <div class="col-md-8">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Nomor Polisi</th>
                                    <td id="no-polisi">AB 5543 XX</td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td id="tahun">2021</td>
                                </tr>
                                <tr>
                                    <th>KM Terakhir</th>
                                    <td id="km-terakhir">32.410 km</td>
                                </tr>
                                <tr>
                                    <th>Pemegang</th>
                                    <td id="pemegang">Boby N</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Servis</th>
                                    <td id="terakhir-servis">BG 1234 DZ – Servis Berkala, 2021</td>
                                </tr>
                                <tr>
                                    <th>Servis Selanjutnya</th>
                                    <td id="servis-selanjutnya">32.410 km</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <td id="catatan">-</td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" id="btn-ajukan-servis">Ajukan Perbaikan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Pengajuan Perbaikan -->
            <div class="col-md-6">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Pengajuan Perbaikan</h4>
                        <span class="widget-toolbar">
                            <a href="#" id="link-lihat-semua-perbaikan">Lihat Semua</a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Mobil</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-perbaikan">
                                    <tr>
                                        <td>PRB-2025-045</td>
                                        <td>Avanza - B 1234 CNA</td>
                                        <td><span class="label label-warning">Menunggu</span></td>
                                        <td><button class="btn btn-xs btn-danger">Batalkan</button></td>
                                    </tr>
                                    <tr>
                                        <td>PRB-2025-039</td>
                                        <td>Yaris - D 8890 QA</td>
                                        <td><span class="label label-success">Selesai</span></td>
                                        <td><button class="btn btn-xs btn-info">Detail</button></td>
                                    </tr>
                                </tbody>
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
                                        <th>Kode</th>
                                        <th>Mobil</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-servis">
                                    <tr>
                                        <td>PRB-2025-045</td>
                                        <td>Avanza - B 1234 CNA</td>
                                        <td><span class="label label-primary">Service</span></td>
                                        <td><button class="btn btn-xs btn-default">Detail</button></td>
                                    </tr>
                                    <tr>
                                        <td>PRB-2025-039</td>
                                        <td>Yaris - D 8890 QA</td>
                                        <td><span class="label label-success">Selesai</span></td>
                                        <td><button class="btn btn-xs btn-default">Detail</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- /.container-fluid -->
</div> <!-- /.page-content -->
<?php $this->endSection(); ?>