<!-- app/Views/layout/sidebar.php -->
<div id="sidebar" class="sidebar responsive">
    <ul class="nav nav-list">
        <li class="active">
            <a href="<?= site_url('dashboard') ?>">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
        </li>

        <li>
            <a href="<?= site_url('users') ?>">
                <i class="menu-icon fa fa-users"></i>
                <span class="menu-text"> Users </span>
            </a>
        </li>

        <li>
            <a href="<?= site_url('settings') ?>">
                <i class="menu-icon fa fa-cog"></i>
                <span class="menu-text"> Settings </span>
            </a>
        </li>
    </ul>
</div>
