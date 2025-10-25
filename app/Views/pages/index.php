<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="dashboard-wrapper container-fluid">

	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="page-title">Dashboard</h4>
		</div>
		<div class="action-buttons ">
			<button id="btn-refresh" class="btn btn-outline-primary btn-sm mr-2">
				<i class="fa fa-sync"></i> Segarkan
			</button>
			<a href="<?= site_url('laporan/pdf') ?>" class="btn btn-primary btn-sm">
				<i class="fa fa-download"></i> Unduh Laporan PDF
			</a>
		</div>
	</div>

	<div class="row stats-cards mb-4">
		<div class="col-md-3">
			<div class="card stat-card">
				<div class="card-body">
					<div class="stat-title">Jumlah Mobil</div>
					<div class="stat-number"><?= $jumlahMobil ?? 0 ?></div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card stat-card">
				<div class="card-body">
					<div class="stat-title">Pengajuan Perbaikan</div>
					<div class="stat-number"><?= $pengajuanPerbaikan ?? 0 ?></div>
					<?php if (!empty($menunggu)): ?>
						<span class="badge badge-warning stat-badge">Menunggu <?= $menunggu ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card stat-card">
				<div class="card-body">
					<div class="stat-title">Status Servis</div>
					<div class="stat-number"><?= $statusServisCount ?? 0 ?></div>
					<?php if (!empty($dalamProses)): ?>
						<span class="badge badge-info stat-badge">Dalam Proses <?= $dalamProses ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card stat-card">
				<div class="card-body">
					<div class="stat-title">Peminjaman Aktif</div>
					<div class="stat-number"><?= $peminjamanAktif ?? 0 ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mb-4">
		<div class="col-lg-7">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5>Pengajuan Servis</h5>
					<div class="d-flex">
						<input type="text" id="cari-servis" class="form-control form-control-sm mr-2" placeholder="Cari...">
						<button class="btn btn-light btn-sm"><i class="fa fa-filter"></i></button>
					</div>
				</div>
				<div class="card-body p-0">
					<table class="table table-hover table-sm mb-0">
						<thead class="thead-light">
							<tr>
								<th>Pengajuan</th>
								<th>Mobil</th>
								<th>Nopol</th>
								<th>Tanggal</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($listPengajuan)): ?>
								<?php foreach ($listPengajuan as $pj): ?>
									<tr>
										<td><?= $pj->kode ?></td>
										<td><?= $pj->mobil ?></td>
										<td><?= $pj->nopol ?></td>
										<td><?= $pj->tanggal ?></td>
										<td>
											<span class="badge badge-<?= $pj->status_badge_class ?>"><?= $pj->status_text ?></span>
										</td>
										<td>
											<a href="<?= site_url('servis/detail/' . $pj->id) ?>" class="btn btn-sm btn-primary">Detail</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="6" class="text-center">Tidak ada data</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="card">
				<div class="card-header">
					<h5>Riwayat Servis Terbaru</h5>
				</div>
				<div class="card-body">
					<ul class="list-group list-group-flush">
						<?php if (!empty($riwayatServis)): ?>
							<?php foreach ($riwayatServis as $rw): ?>
								<li class="list-group-item">
									<div class="d-flex justify-content-between align-items-start">
										<div>
											<strong><?= $rw->judul ?></strong><br>
											<small class="text-muted"><?= $rw->keterangan ?></small>
										</div>
										<div class="text-right">
											<small class="text-muted"><?= $rw->tanggal ?></small><br>
											<?php if (!empty($rw->biaya)): ?>
												<span class="text-primary">Rp <?= number_format($rw->biaya, 0, ',', '.') ?></span>
											<?php endif; ?>
										</div>
									</div>
								</li>
							<?php endforeach; ?>
						<?php else: ?>
							<li class="list-group-item text-center">Tidak ada riwayat</li>
						<?php endif; ?>
					</ul>
					<div class="mt-3 text-center">
						<a href="<?= site_url('servis/riwayat') ?>" class="small">Lihat Semua</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h5>Data Mobil Dinas</h5>
					<div class="d-flex align-items-center">
						<input type="text" id="cari-mobil" class="form-control form-control-sm mr-2" placeholder="Cari merk, nopol...">
						<select id="filter-status" class="form-control form-control-sm">
							<option value="">Semua Status</option>
							<option value="servis">Servis</option>
							<option value="aktif">Aktif</option>
						</select>
					</div>
				</div>
				<div class="card-body p-0">
					<table class="table table-bordered table-sm mb-0">
						<thead class="thead-light">
							<tr>
								<th>Merk</th>
								<th>Nopol</th>
								<th>Unit</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($listMobil)): ?>
								<?php foreach ($listMobil as $mob): ?>
									<tr>
										<td><?= $mob->merk ?></td>
										<td><?= $mob->nopol ?></td>
										<td><?= $mob->unit ?></td>
										<td>
											<?php if ($mob->status === 'servis'): ?>
												<span class="badge badge-warning">Servis</span>
											<?php else: ?>
												<span class="badge badge-success"><?= ucfirst($mob->status) ?></span>
											<?php endif; ?>
										</td>
										<td>
											<a href="<?= site_url('mobil/detail/' . $mob->id) ?>" class="btn btn-sm btn-info">Detail</a>
											<a href="<?= site_url('mobil/edit/' . $mob->id) ?>" class="btn btn-sm btn-light">Edit</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="5" class="text-center">Tidak ada data mobil</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
	if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
<script src="assets/js/jquery-ui.custom.min.js"></script>
<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="assets/js/jquery.easypiechart.min.js"></script>
<script src="assets/js/jquery.sparkline.index.min.js"></script>
<script src="assets/js/jquery.flot.min.js"></script>
<script src="assets/js/jquery.flot.pie.min.js"></script>
<script src="assets/js/jquery.flot.resize.min.js"></script>

<!-- ace scripts -->
<!-- <script src="assets/js/ace-elements.min.js"></script>
<script src="assets/js/ace.min.js"></script> -->

<!-- inline scripts related to this page -->
<script type="text/javascript">
	jQuery(function($) {
		$('.easy-pie-chart.percentage').each(function() {
			var $box = $(this).closest('.infobox');
			var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
			var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
			var size = parseInt($(this).data('size')) || 50;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size / 10),
				animate: ace.vars['old_ie'] ? false : 1000,
				size: size
			});
		})

		$('.sparkline').each(function() {
			var $box = $(this).closest('.infobox');
			var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
			$(this).sparkline('html', {
				tagValuesAttribute: 'data-values',
				type: 'bar',
				barColor: barColor,
				chartRangeMin: $(this).data('min') || 0
			});
		});


		//flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
		//but sometimes it brings up errors with normal resize event handlers
		$.resize.throttleWindow = false;

		var placeholder = $('#piechart-placeholder').css({
			'width': '90%',
			'min-height': '150px'
		});
		var data = [{
				label: "social networks",
				data: 38.7,
				color: "#68BC31"
			},
			{
				label: "search engines",
				data: 24.5,
				color: "#2091CF"
			},
			{
				label: "ad campaigns",
				data: 8.2,
				color: "#AF4E96"
			},
			{
				label: "direct traffic",
				data: 18.6,
				color: "#DA5430"
			},
			{
				label: "other",
				data: 10,
				color: "#FEE074"
			}
		]

		function drawPieChart(placeholder, data, position) {
			$.plot(placeholder, data, {
				series: {
					pie: {
						show: true,
						tilt: 0.8,
						highlight: {
							opacity: 0.25
						},
						stroke: {
							color: '#fff',
							width: 2
						},
						startAngle: 2
					}
				},
				legend: {
					show: true,
					position: position || "ne",
					labelBoxBorderColor: null,
					margin: [-30, 15]
				},
				grid: {
					hoverable: true,
					clickable: true
				}
			})
		}
		drawPieChart(placeholder, data);

		/**
		we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
		so that's not needed actually.
		*/
		placeholder.data('chart', data);
		placeholder.data('draw', drawPieChart);


		//pie chart tooltip example
		var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
		var previousPoint = null;

		placeholder.on('plothover', function(event, pos, item) {
			if (item) {
				if (previousPoint != item.seriesIndex) {
					previousPoint = item.seriesIndex;
					var tip = item.series['label'] + " : " + item.series['percent'] + '%';
					$tooltip.show().children(0).text(tip);
				}
				$tooltip.css({
					top: pos.pageY + 10,
					left: pos.pageX + 10
				});
			} else {
				$tooltip.hide();
				previousPoint = null;
			}

		});

		/////////////////////////////////////
		$(document).one('ajaxloadstart.page', function(e) {
			$tooltip.remove();
		});




		var d1 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) {
			d1.push([i, Math.sin(i)]);
		}

		var d2 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) {
			d2.push([i, Math.cos(i)]);
		}

		var d3 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.2) {
			d3.push([i, Math.tan(i)]);
		}


		var sales_charts = $('#sales-charts').css({
			'width': '100%',
			'height': '220px'
		});
		$.plot("#sales-charts", [{
				label: "Domains",
				data: d1
			},
			{
				label: "Hosting",
				data: d2
			},
			{
				label: "Services",
				data: d3
			}
		], {
			hoverable: true,
			shadowSize: 0,
			series: {
				lines: {
					show: true
				},
				points: {
					show: true
				}
			},
			xaxis: {
				tickLength: 0
			},
			yaxis: {
				ticks: 10,
				min: -2,
				max: 2,
				tickDecimals: 3
			},
			grid: {
				backgroundColor: {
					colors: ["#fff", "#fff"]
				},
				borderWidth: 1,
				borderColor: '#555'
			}
		});


		$('#recent-box [data-rel="tooltip"]').tooltip({
			placement: tooltip_placement
		});

		function tooltip_placement(context, source) {
			var $source = $(source);
			var $parent = $source.closest('.tab-content')
			var off1 = $parent.offset();
			var w1 = $parent.width();

			var off2 = $source.offset();
			//var w2 = $source.width();

			if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
			return 'left';
		}


		$('.dialogs,.comments').ace_scroll({
			size: 300
		});


		//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
		//so disable dragging when clicking on label
		var agent = navigator.userAgent.toLowerCase();
		if (ace.vars['touch'] && ace.vars['android']) {
			$('#tasks').on('touchstart', function(e) {
				var li = $(e.target).closest('#tasks li');
				if (li.length == 0) return;
				var label = li.find('label.inline').get(0);
				if (label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation();
			});
		}

		$('#tasks').sortable({
			opacity: 0.8,
			revert: true,
			forceHelperSize: true,
			placeholder: 'draggable-placeholder',
			forcePlaceholderSize: true,
			tolerance: 'pointer',
			stop: function(event, ui) {
				//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
				$(ui.item).css('z-index', 'auto');
			}
		});
		$('#tasks').disableSelection();
		$('#tasks input:checkbox').removeAttr('checked').on('click', function() {
			if (this.checked) $(this).closest('li').addClass('selected');
			else $(this).closest('li').removeClass('selected');
		});


		//show the dropdowns on top or bottom depending on window height and menu position
		$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
			var offset = $(this).offset();

			var $w = $(window)
			if (offset.top > $w.scrollTop() + $w.innerHeight() - 100)
				$(this).addClass('dropup');
			else $(this).removeClass('dropup');
		});

	})
</script>
<?= $this->endSection(); ?>