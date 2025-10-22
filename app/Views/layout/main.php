<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta charset="utf-8" />
  <title><?= $title ?? 'Error Title' ?></title>

  <meta name="description" content="overview &amp; stats" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="<?= base_url('/assets/css/bootstrap.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('/assets/font-awesome/4.5.0/css/font-awesome.min.css') ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Page specific plugin styles -->
  <link rel="stylesheet" href="<?= base_url('/assets/css/jquery-ui.custom.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('/assets/css/fullcalendar.min.css') ?>" />

  <!-- Text fonts -->
  <link rel="stylesheet" href="<?= base_url('/assets/css/fonts.googleapis.com.css') ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

  <!-- Ace styles -->
  <link rel="stylesheet" href="<?= base_url('/assets/css/ace.min.css') ?>" class="ace-main-stylesheet" id="main-ace-style" />

  <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?= base_url('/assets/css/ace-part2.min.css') ?>" class="ace-main-stylesheet" />
  <![endif]-->

  <link rel="stylesheet" href="<?= base_url('/assets/css/ace-skins.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('/assets/css/ace-rtl.min.css') ?>" />

  <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?= base_url('/assets/css/ace-ie.min.css') ?>" />
  <![endif]-->

  <!-- Inline styles related to this page -->

  <!-- Ace settings handler -->
  <script src="<?= base_url('/assets/js/ace-extra.min.js') ?>"></script>

  <!-- AXIOS -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <!-- Custom CSS -->
  <?php if (isset($css) && $css !== ''): ?>
    <link rel="stylesheet" href="<?= base_url('/assets/css/custom/' . $css) ?>" />
  <?php endif; ?>

  <!-- HTML5shiv and Respond.js for IE8 support -->
  <!--[if lte IE 8]>
      <script src="<?= base_url('/assets/js/html5shiv.min.js') ?>"></script>
      <script src="<?= base_url('/assets/js/respond.min.js') ?>"></script>
  <![endif]-->
</head>

<body class="no-skin">
  <div class="main-container" id="main-container">

    <!-- Navbar -->
    <?= $this->include('layout/navbar') ?>

    <!-- Sidebar -->
    <?php
    switch ($role) {
      case 'Superuser':
      case 'admin':
        echo $this->include('layout/sidebar_admin');
        break;
      case 'dosen':
      default:
        echo $this->include('layout/sidebar_user');
    }
    ?>

    <!-- Main content -->
    <div class="main-content">
      <?= $this->renderSection('content') ?>
    </div>
  </div>

  <script>
    const api = axios.create({
      baseURL: '<?= base_url() ?>',
      withCredentials: true
    });

    api.interceptors.request.use(
      config => {
        config.headers['X-Requested-With'] = 'XMLHttpRequest';
        config.headers['X-Client-Type'] = 'browser';
        config.headers['Accept'] = 'application/json, text/plain, */*';
        return config;
      },
      error => Promise.reject(error)
    );

    api.interceptors.response.use(
      response => response,
      error => {
        if (error.response && error.response.status === 401) {
          localStorage.removeItem('user');
          sessionStorage.removeItem('user');
          redirectToLogin(error.response.data.message || 'Sesi telah berakhir');
          return Promise.reject(error);
        }
        return Promise.reject(error);
      }
    );

    function redirectToLogin(message = 'Sesi telah berakhir, silakan login kembali') {
      alert(message);
      setTimeout(() => {
        window.location.href = '<?= base_url('/login') ?>';
      }, 1500);
    }
  </script>

  <!-- JS -->
  <script src="<?= base_url('/assets/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('/assets/js/ace.min.js') ?>"></script>
  <script src="<?= base_url('/assets/js/ace-elements.min.js') ?>"></script>

  <!-- CUSTOM SCRIPT -->
  <?= $this->renderSection('scripts'); ?>
</body>
</html>
