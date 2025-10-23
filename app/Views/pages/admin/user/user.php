<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
  <h3 class="mb-3">Tambah User</h3>

  <form action="<?= base_url('master/user/create') ?>" method="post">
	<?= csrf_field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nip" class="form-label">NIP</label>
        <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" required>
      </div>

      <div class="col-md-6">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
      </div>

      <div class="col-md-6">
        <label for="no_handphone" class="form-label">No Handphone</label>
        <input type="text" class="form-control" id="no_handphone" name="no_handphone" placeholder="Masukkan nomor HP" required>
      </div>

      <div class="col-md-6">
        <label for="jabatan" class="form-label">Jabatan</label>
        <select id="jabatan" name="jabatan" class="form-select" required>
          <option value="" selected disabled>Pilih Jabatan</option>
          <option value="Superuser">Superuser</option>
          <option value="admin">Admin</option>
          <option value="dosen">Dosen</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
      </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
      <button type="submit" class="btn btn-primary me-2">
        <i class="bi bi-save"></i> Simpan
      </button>
      <a href="<?= base_url('master/user') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
  </form>
</div>

<!-- Optional: Bootstrap form validation script -->
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

<div class="container mt-4">
  <h3 class="mb-3">Daftar User</h3>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">NIP</th>
          <th scope="col">Nama</th>
          <th scope="col">Email</th>
          <th scope="col">No Handphone</th>
          <th scope="col">Jabatan</th>
          <th scope="col">Password</th>
          <th scope="col">Created At</th>
          <th scope="col">Updated At</th>
        </tr>
      </thead>
<tbody>
  <?php if (!empty($data)): ?>
    <?php foreach ($data as $u): ?>
      <tr>
        <td><?= esc($u->id) ?></td>
        <td><?= esc($u->nip) ?></td>
        <td><?= esc($u->nama) ?></td>
        <td><?= esc($u->email) ?></td>
        <td><?= esc($u->no_handphone) ?></td>
        <td><span class="badge bg-primary"><?= esc($u->jabatan) ?></span></td>
        <td><span class="text-muted fst-italic">••••••••</span></td>
        <td><?= esc($u->created_at) ?></td>
        <td><?= esc($u->updated_at) ?></td>
        <td class="text-center">
          <a href="<?= base_url('master/user/edit/'.$u->id) ?>" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="10" class="text-center text-muted py-3">Tidak ada data user.</td>
    </tr>
  <?php endif; ?>
</tbody>
    </table>
  </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<!--[if IE]>
<script src="<?= '/assets/js/jquery-1.11.3.min.js'; ?>"></script>
<![endif]-->

<script type="text/javascript">
	if ('ontouchstart' in document.documentElement)
		document.write("<script src='<?= '/assets/js/jquery.mobile.custom.min.js'; ?>'>" + "<" + "/script>");
</script>

<script src="<?= '/assets/js/bootstrap.min.js'; ?>"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="<?= '/assets/js/excanvas.min.js'; ?>"></script>
<![endif]-->

<script src="<?= '/assets/js/jquery-ui.custom.min.js'; ?>"></script>
<script src="<?= '/assets/js/jquery.ui.touch-punch.min.js'; ?>"></script>
<script src="<?= '/assets/js/jquery.easypiechart.min.js'; ?>"></script>
<script src="<?= '/assets/js/jquery.sparkline.index.min.js'; ?>"></script>
<script src="<?= '/assets/js/jquery.flot.min.js'; ?>"></script>
<script src="<?= '/assets/js/jquery.flot.pie.min.js'; ?>"></script>
<script src="<?= '/assets/js/jquery.flot.resize.min.js'; ?>"></script>

<!-- ace scripts -->
<script src="<?= '/assets/js/ace-elements.min.js'; ?>"></script>
<script src="<?= '/assets/js/ace.min.js'; ?>"></script>

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
		});

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

		$.resize.throttleWindow = false;

		var placeholder = $('#piechart-placeholder').css({
			'width': '90%',
			'min-height': '150px'
		});

		var data = [
			{ label: "social networks", data: 38.7, color: "#68BC31" },
			{ label: "search engines", data: 24.5, color: "#2091CF" },
			{ label: "ad campaigns", data: 8.2, color: "#AF4E96" },
			{ label: "direct traffic", data: 18.6, color: "#DA5430" },
			{ label: "other", data: 10, color: "#FEE074" }
		];

		function drawPieChart(placeholder, data, position) {
			$.plot(placeholder, data, {
				series: {
					pie: {
						show: true,
						tilt: 0.8,
						highlight: { opacity: 0.25 },
						stroke: { color: '#fff', width: 2 },
						startAngle: 2
					}
				},
				legend: {
					show: true,
					position: position || "ne",
					labelBoxBorderColor: null,
					margin: [-30, 15]
				},
				grid: { hoverable: true, clickable: true }
			});
		}

		drawPieChart(placeholder, data);
		placeholder.data('chart', data);
		placeholder.data('draw', drawPieChart);

		var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
		var previousPoint = null;

		placeholder.on('plothover', function(event, pos, item) {
			if (item) {
				if (previousPoint != item.seriesIndex) {
					previousPoint = item.seriesIndex;
					var tip = item.series['label'] + " : " + item.series['percent'] + '%';
					$tooltip.show().children(0).text(tip);
				}
				$tooltip.css({ top: pos.pageY + 10, left: pos.pageX + 10 });
			} else {
				$tooltip.hide();
				previousPoint = null;
			}
		});

		$(document).one('ajaxloadstart.page', function(e) { $tooltip.remove(); });

		var d1 = [], d2 = [], d3 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) d1.push([i, Math.sin(i)]);
		for (var i = 0; i < Math.PI * 2; i += 0.5) d2.push([i, Math.cos(i)]);
		for (var i = 0; i < Math.PI * 2; i += 0.2) d3.push([i, Math.tan(i)]);

		var sales_charts = $('#sales-charts').css({ 'width': '100%', 'height': '220px' });
		$.plot("#sales-charts", [
			{ label: "Domains", data: d1 },
			{ label: "Hosting", data: d2 },
			{ label: "Services", data: d3 }
		], {
			hoverable: true,
			shadowSize: 0,
			series: { lines: { show: true }, points: { show: true } },
			xaxis: { tickLength: 0 },
			yaxis: { ticks: 10, min: -2, max: 2, tickDecimals: 3 },
			grid: {
				backgroundColor: { colors: ["#fff", "#fff"] },
				borderWidth: 1,
				borderColor: '#555'
			}
		});

		$('#recent-box [data-rel="tooltip"]').tooltip({ placement: tooltip_placement });
		function tooltip_placement(context, source) {
			var $source = $(source);
			var $parent = $source.closest('.tab-content');
			var off1 = $parent.offset();
			var w1 = $parent.width();
			var off2 = $source.offset();
			if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
			return 'left';
		}

		$('.dialogs,.comments').ace_scroll({ size: 300 });

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
			stop: function(event, ui) { $(ui.item).css('z-index', 'auto'); }
		});

		$('#tasks').disableSelection();
		$('#tasks input:checkbox').removeAttr('checked').on('click', function() {
			if (this.checked) $(this).closest('li').addClass('selected');
			else $(this).closest('li').removeClass('selected');
		});

		$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
			var offset = $(this).offset();
			var $w = $(window);
			if (offset.top > $w.scrollTop() + $w.innerHeight() - 100)
				$(this).addClass('dropup');
			else $(this).removeClass('dropup');
		});
	});
</script>

<?= $this->endSection(); ?>