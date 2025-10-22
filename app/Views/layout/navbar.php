<nav id="navbar" class="navbar navbar-default ">
    <a href="<?= base_url('/') ?>" class="logoContainer">
        <img src="<?= base_url('assets/images/logoPolsri.png') ?>" alt="">
        <h1>Cars Office Polsri</h1>
    </a>
    <ul class="nav ace-nav">
        <li class="dropwdownContainer">
            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                <!-- <img class="nav-user-photo" src="assets/images/avatars/user.jpg" alt="Jason's Photo" />
                <span class="user-info">
                    <small>Welcome,</small>
                    <?= session('nama'); ?>
                </span> -->
                <i class="fa-solid fa-user" style="color: #f8bd17;"></i>
            </a>

            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                <li>
                    <a href="#">
                        <i class="ace-icon fa fa-cog"></i>
                        Settings
                    </a>
                </li>

                <li>
                    <a href="profile.html">
                        <i class="ace-icon fa fa-user"></i>
                        Profile
                    </a>
                </li>

                <li class="divider"></li>

                <li>
                    <a href="<?= base_url('/auth/logout'); ?>">
                        <i class="ace-icon fa fa-power-off"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<style>
    .logoContainer{
        display:flex;
        gap:10px;
        align-items:center;
    }
    .logoContainer a, h1{
        text-decoration:none!important;
    }
    .logoContainer a, h1:hover{
        text-decoration:none!important;
    }
    .logoContainer img{
        width: clamp(0%, 45px, 20%);
        object-fit:cover;
        object-position:center;
    }
    .logoContainer h1{
        font-family: "Outfit", sans-serif;
        font-weight:600;
        color:white;
        font-size:clamp(20px,10px,32px);
        margin: 0;
    }
    #navbar{
        padding: 8px 20px 8px 20px;
        display:flex;
        justify-content:space-between;
        background-color:#1D65A5;
    }
    .dropdown-toggle{
        background-color:#2e658900!important;
        display: flex !important;  
        align-items: center;
        justify-content: center;
    }
    .dropdown-toggle i{
        font-size:clamp(18px,10px,32px);
        transition:0.2s;
    }
    .dropdown-toggle i:hover{
        background-color:white;
        padding:8px;
        border-radius:900px;
    }
</style>

<!-- <div id="navbar" class="navbar navbar-default          ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="<?= base_url('/') ?>" class="navbar-brand">
                <small>
                    <i class="fa fa-leaf"></i>
                    Cars Office POLSRI
                </small>
            </a>
        </div>

        <div class="" role="navigation">
            <i class="fa-solid fa-user" style="color: #f8bd17;"></i>
            <ul class="nav ace-nav">
                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="assets/images/avatars/user.jpg" alt="Jason's Photo" />
                        <span class="user-info">
                            <small>Welcome,</small>
                            <?= session('nama'); ?>
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="#">
                                <i class="ace-icon fa fa-cog"></i>
                                Settings
                            </a>
                        </li>

                        <li>
                            <a href="profile.html">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="<?= base_url('/auth/logout'); ?>">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
</div> /.navbar-container -->
</div>

<script src="<?= base_url('/assets/js/jquery-2.1.4.min.js') ?>"></script>
<script src="<?= base_url('/assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('/assets/js/ace.min.js') ?>"></script>