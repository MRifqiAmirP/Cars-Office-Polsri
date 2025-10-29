<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="#">Home</a>
        </li>

        <li>
            <a href="#">Master Data</a>
        </li>
        <li class="active">Kendaraan Dinas</li>
    </ul>
</div>
<div class="main-content-inner">
    <div class="page-content">
        <div style="margin-top: -20px; margin-bottom: 20px;">
            <h3 style="font-weight: bold;">Mitra Bengkel</h3>
            <small class="text-muted">Tinjau dokumen, status legalitas, dan reputasi mitra bengkel</small>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari nama bengkel, NPWP, atau kota...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <button class="btn btn-info btn-sm">
                    <i class="fa fa-plus-circle"></i> Tambah Mitra Bengkel
                </button>
            </div>
        </div>

        <!-- Daftar Bengkel -->
        <div class="row">
            <div class="col-md-12">
                <div style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 15px; background-color: #f8f9fa;">
                    <div id="list_mitra" style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background-color: white;">
                        <div class="row" style="display: flex; align-items: center; justify-content: space-around;">
                            <!-- DIISI MELALUI JAVASCRIPT -->
                            <div class="col-md-3">
                                <strong>Toyota Auto Graha</strong><br>
                                <span style="color: #6c757d;">NPWP: 12.345.678-9-012.345</span><br>
                            </div>
                            <div class="col-md-2 text-center">
                                <span><strong>Rifqi Hidayat</strong></span><br>
                                <span>toyota.graha@gmail.com</span><br>
                                <span>0711456789</span>
                            </div>
                            <div class="col-md-2 text-center">
                                <span>Jl. Palembang - Prabumulih</span>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 style="color: #007bff;">86/100</h4>
                            </div>
                            <div class="col-md-2 text-center">
                                <button class="btn btn-sm btn-info">Tinjau</button>
                                <button class="btn btn-sm btn-success">Edit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Tinjauan -->
    <div class="row">
        <div class="col-md-12">
            <!-- Kotak Pembungkus Utama -->
            <div class="widget-box">
                <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="widget-title">Detail Mitra: Bengkel Utama Sejahtera</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <!-- Konten dalam kotak -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><i class="ace-icon fa fa-building-o"></i> Profil &amp; Legalitas</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Nama Bengkel</div>
                                            <div class="profile-info-value">
                                                <span>PT Bengkel Utama Sejahtera</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">NPWP</div>
                                            <div class="profile-info-value">
                                                <span>12.345.678-9-012.345</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Alamat</div>
                                            <div class="profile-info-value">
                                                <i class="ace-icon fa fa-map-marker"></i> Jl. Kaliurang Km 7, Yogyakarta
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Nama Kontak</div>
                                            <div class="profile-info-value">
                                                <i class="ace-icon fa fa-user"></i> Andri Wijaya
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Kontak</div>
                                            <div class="profile-info-value">
                                                <i class="ace-icon fa fa-phone"></i> (0274) 123456<br>
                                                <i class="ace-icon fa fa-envelope"></i> cs@bus.co.id
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="text-center">
                                            <span class="label label-success arrowed">SIUP - Valid</span>
                                            <span class="label label-success arrowed">Asuransi - Aktif</span>
                                            <span class="label label-success arrowed">TDP - Valid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><i class="ace-icon fa fa-bar-chart"></i> Ringkasan Kinerja</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Order Selesai</div>
                                            <div class="profile-info-value">
                                                <span class="badge badge-primary">312</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Rata Waktu Selesai</div>
                                            <div class="profile-info-value">
                                                <span class="label label-grey">1.8 hari</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Kepuasan</div>
                                            <div class="profile-info-value">
                                                <span class="label label-info">4.7/5</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Garansi Klaim</div>
                                            <div class="profile-info-value">
                                                <span class="badge badge-danger">2</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="alert alert-info no-margin">
                                            <i class="ace-icon fa fa-info-circle"></i>
                                            <strong>Catatan:</strong> Performa stabil, kapasitas memadai untuk armada kampus.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><i class="ace-icon fa fa-map-marker"></i> Lokasi Bengkel</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div id="map" style="height: 300px; width: 100%; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 15px; background: #f8f9fa;">
                                            <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #666;" id="map-loading">
                                                <div class="text-center">
                                                    <i class="ace-icon fa fa-spinner fa-spin fa-2x"></i>
                                                    <p>Memuat peta...</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Latitude</div>
                                            <div class="profile-info-value">
                                                <span class="label label-grey" id="display-lat">-7.771401</span>
                                            </div>
                                        </div>
                                        <div class="profile-info-row">
                                            <div class="profile-info-name">Longitude</div>
                                            <div class="profile-info-value">
                                                <span class="label label-grey" id="display-lng">110.377499</span>
                                            </div>
                                        </div>

                                        <div class="text-center" style="margin-top: 15px;">
                                            <button class="btn btn-sm btn-info" onclick="openInOSM()">
                                                <i class="ace-icon fa fa-external-link"></i> OpenStreetMap
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="openInGoogleMaps()">
                                                <i class="ace-icon fa fa-google"></i> Google Maps
                                            </button>
                                            <button class="btn btn-sm btn-warning" onclick="getDirections()">
                                                <i class="ace-icon fa fa-road"></i> Petunjuk Arah
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen Dummy -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><i class="ace-icon fa fa-files-o"></i> Dokumen Legalitas</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 document-preview">
                                                <div class="thumbnail">
                                                    <div class="caption text-center">
                                                        <h5><i class="ace-icon fa fa-file-pdf-o red"></i> SIUP.pdf</h5>
                                                    </div>
                                                    <img src="https://picsum.photos/300/150?random=4" alt="SIUP" class="img-responsive doc-image">
                                                    <div class="caption text-center">
                                                        <div class="btn-group btn-group-justified">
                                                            <a href="#" class="btn btn-xs btn-info">
                                                                <i class="ace-icon fa fa-eye"></i> Preview
                                                            </a>
                                                            <a href="#" class="btn btn-xs btn-success">
                                                                <i class="ace-icon fa fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 document-preview">
                                                <div class="thumbnail">
                                                    <div class="caption text-center">
                                                        <h5><i class="ace-icon fa fa-file-pdf-o red"></i> TDP.pdf</h5>
                                                    </div>
                                                    <img src="https://picsum.photos/300/150?random=5" alt="TDP" class="img-responsive doc-image">
                                                    <div class="caption text-center">
                                                        <div class="btn-group btn-group-justified">
                                                            <a href="#" class="btn btn-xs btn-info">
                                                                <i class="ace-icon fa fa-eye"></i> Preview
                                                            </a>
                                                            <a href="#" class="btn btn-xs btn-success">
                                                                <i class="ace-icon fa fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 document-preview">
                                                <div class="thumbnail">
                                                    <div class="caption text-center">
                                                        <h5><i class="ace-icon fa fa-file-image-o green"></i> Asuransi.png</h5>
                                                    </div>
                                                    <img src="https://picsum.photos/300/150?random=6" alt="Asuransi" class="img-responsive doc-image">
                                                    <div class="caption text-center">
                                                        <div class="btn-group btn-group-justified">
                                                            <a href="#" class="btn btn-xs btn-info">
                                                                <i class="ace-icon fa fa-eye"></i> Preview
                                                            </a>
                                                            <a href="#" class="btn btn-xs btn-success">
                                                                <i class="ace-icon fa fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const bengkelData = {
        name: "Bengkel Utama Sejahtera",
        address: "Jl. Kaliurang Km 7, Yogyakarta",
        lat: -7.771401,
        lng: 110.377499
    };

    let map;

    function initMap() {
        map = L.map('map').setView([bengkelData.lat, bengkelData.lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        const marker = L.marker([bengkelData.lat, bengkelData.lng]).addTo(map);

        marker.bindPopup(`
        <div style="padding: 5px;">
            <h5 style="margin: 0 0 5px 0; color: #2d3e50;">${bengkelData.name}</h5>
            <p style="margin: 0; font-size: 12px; color: #666;">${bengkelData.address}</p>
        </div>
    `).openPopup();
        document.getElementById('display-lat').textContent = bengkelData.lat;
        document.getElementById('display-lng').textContent = bengkelData.lng;
    }

    function openInOSM() {
        const url = `https://www.openstreetmap.org/?mlat=${bengkelData.lat}&mlon=${bengkelData.lng}#map=17/${bengkelData.lat}/${bengkelData.lng}`;
        window.open(url, '_blank');
    }

    function openInGoogleMaps() {
        const url = `https://www.google.com/maps?q=${bengkelData.lat},${bengkelData.lng}&z=17`;
        window.open(url, '_blank');
    }

    function getDirections() {
        const url = `https://www.openstreetmap.org/directions?from=&to=${bengkelData.lat},${bengkelData.lng}`;
        window.open(url, '_blank');
    }
    document.addEventListener('DOMContentLoaded', initMap);
</script>
<?= $this->endSection(); ?>