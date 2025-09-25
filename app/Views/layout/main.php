<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <title><?= $title ?? 'Error Title' ?></title>

  <meta name="description" content="overview &amp; stats" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

  <!-- Page specific plugin styles -->
  <link rel="stylesheet" href="assets/css/jquery-ui.custom.min.css" />
  <link rel="stylesheet" href="assets/css/fullcalendar.min.css" />

  <!-- Text fonts -->
  <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

  <!-- Ace styles -->
  <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

  <!--[if lte IE 9]>
        <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
    <![endif]-->

  <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
  <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

  <!--[if lte IE 9]>
        <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
    <![endif]-->

  <!-- Inline styles related to this page -->

  <!-- Ace settings handler -->
  <script src="assets/js/ace-extra.min.js"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/custom/dashboard.css" />

  <!-- HTML5shiv and Respond.js for IE8 support -->
  <!--[if lte IE 8]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="no-skin">
  <div class="main-container" id="main-container">

    <!-- Navbar -->
    <?= $this->include('layout/navbar') ?>

    <!-- Sidebar -->
    <?= $this->include('layout/sidebar') ?>

    <!-- Main content -->
    <div class="main-content">
      <?= $this->renderSection('content') ?>
    </div>
  </div>

  <!-- JS -->
  <script src="<?= base_url('assets/js/jquery-2.1.4.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/ace.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/ace-elements.min.js') ?>"></script>

  <!-- CUSTOM SCRIPT -->
  <?= $this->renderSection('scripts'); ?>
</body>

</html>