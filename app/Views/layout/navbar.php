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
<<<<<<< HEAD
=======
                <li class="green dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
                        <span class="badge badge-success">5</span>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-envelope-o"></i>
                            5 Messages
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar">
                                <li>
                                    <a href="#" class="clearfix">
                                        <img src="assets/images/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Alex:</span>
                                                Ciao sociis natoque penatibus et auctor ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="ace-icon fa fa-clock-o"></i>
                                                <span>a moment ago</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="clearfix">
                                        <img src="assets/images/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Susan:</span>
                                                Vestibulum id ligula porta felis euismod ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="ace-icon fa fa-clock-o"></i>
                                                <span>20 minutes ago</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="clearfix">
                                        <img src="assets/images/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Bob:</span>
                                                Nullam quis risus eget urna mollis ornare ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="ace-icon fa fa-clock-o"></i>
                                                <span>3:15 pm</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="clearfix">
                                        <img src="assets/images/avatars/avatar2.png" class="msg-photo" alt="Kate's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Kate:</span>
                                                Ciao sociis natoque eget urna mollis ornare ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="ace-icon fa fa-clock-o"></i>
                                                <span>1:33 pm</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="clearfix">
                                        <img src="assets/images/avatars/avatar5.png" class="msg-photo" alt="Fred's Avatar" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">Fred:</span>
                                                Vestibulum id penatibus et auctor ...
                                            </span>

                                            <span class="msg-time">
                                                <i class="ace-icon fa fa-clock-o"></i>
                                                <span>10:09 am</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown-footer">
                            <a href="inbox.html">
                                See all messages
                                <i class="ace-icon fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

>>>>>>> 540f18b7ba0e282322c9b0389c197ebb3c9672b6
                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <span class="user-info">
                            <small>Welcome,</small>
                            <?= session('nama'); ?>
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
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
<<<<<<< HEAD
</div> /.navbar-container -->
</div>

<script src="<?= base_url('/assets/js/jquery-2.1.4.min.js') ?>"></script>
<script src="<?= base_url('/assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('/assets/js/ace.min.js') ?>"></script>
=======
    </div><!-- /.navbar-container -->
</div>
>>>>>>> 540f18b7ba0e282322c9b0389c197ebb3c9672b6
