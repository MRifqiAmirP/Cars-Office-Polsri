<div id="sidebar" class="sidebar responsive ace-save-state">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {}
    </script>
    <style>
    </style>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
           data-icon1="ace-icon fa fa-angle-double-left"
           data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
    <ul class="nav nav-list list-unstyled">
        <li class="<?= base_url('api/cars') ?>">
            <a href="" class="d-flex align-items-center text-decoration-none">
                <i class="menu-icon fa fa-list-alt me-2"></i>
                <span class="menu-text"> Mobil </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="">
            <a href="widgets.html" class="d-flex align-items-center text-decoration-none">
                <i class="menu-icon fa fa-list-alt me-2"></i>
                <span class="menu-text"> Services </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="">
            <a href="<?= base_url('master/user') ?>" class="d-flex align-items-center text-decoration-none">
                <i class="menu-icon fa fa-list-alt me-2"></i>
                <span class="menu-text"> Users </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="">
            <a href="widgets.html" class="d-flex align-items-center text-decoration-none">
                <i class="menu-icon fa fa-list-alt me-2"></i>
                <span class="menu-text"> Pengajuan Service </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="">
            <a href="<?= base_url('master/user') ?>" class="d-flex align-items-center text-decoration-none">
                <i class="menu-icon fa fa-list-alt me-2"></i>
                <span class="menu-text" > Peminjaman Kendaraan </span>
            </a>
            <b class="arrow"></b>
        </li>
        <li class="">
            <a href="<?= base_url('master/user') ?>" class="d-flex align-items-center text-decoration-none">
                <i class="menu-icon fa fa-list-alt me-2"></i>
                <span class="menu-text">Bengkel</span>
            </a>
            <b class="arrow"></b>
        </li>
    </ul>
</div>