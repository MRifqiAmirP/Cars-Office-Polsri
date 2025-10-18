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

  <!-- AXIOS -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <!-- Custom CSS -->
  <?php if (isset($css) && $css !== ''): ?>
    <link rel="stylesheet" href="assets/css/custom/<?= $css; ?>" />
  <?php endif; ?>

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
    <?php
    switch ($role) {
      case 'superuser':
        echo $this->include('layout/sidebar_admin');
        break;
      case 'admin':
        echo $this->include('layout/sidebar_admin');
        break;
      case 'user':
        echo $this->include('layout/sidebar_dosen');
        break;
    }
    ?>

    <!-- Main content -->
    <div class="main-content">
      <?= $this->renderSection('content') ?>
    </div>
  </div>

  <script>
    // Konfigurasi Axios dengan header untuk browser
    const api = axios.create({
      baseURL: '<?= base_url() ?>',
      withCredentials: true
    });

    // Tambahkan header untuk identifikasi browser request
    api.interceptors.request.use(
      config => {
        config.headers['X-Requested-With'] = 'XMLHttpRequest';
        config.headers['X-Client-Type'] = 'browser';
        config.headers['Accept'] = 'application/json, text/plain, */*';
        return config;
      },
      error => {
        return Promise.reject(error);
      }
    );

    // Interceptor response tetap sama
    api.interceptors.response.use(
      response => response,
      error => {
        if (error.response && error.response.status === 401) {
          const responseData = error.response.data;

          // Clear storage
          localStorage.removeItem('user');
          sessionStorage.removeItem('user');

          // Redirect to login untuk browser
          redirectToLogin(responseData.message || 'Sesi telah berakhir');

          return Promise.reject(error);
        }
        return Promise.reject(error);
      }
    );

    // Fungsi redirect
    function redirectToLogin(message = 'Sesi telah berakhir, silakan login kembali') {
      showNotification('error', message);
      setTimeout(() => {
        window.location.href = '<?= base_url('/login') ?>';
      }, 1500);
    }

    // Fungsi check auth
    async function checkAuthStatus() {
      try {
        const response = await api.get('/auth/me');
        return response.data;
      } catch (error) {
        // Biarkan interceptor handle 401
        throw error;
      }
    }

    // Override jQuery AJAX untuk konsistensi
    if (typeof jQuery !== 'undefined') {
      $(document).ajaxSend(function(event, xhr, settings) {
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      });

      $(document).ajaxComplete(function(event, xhr, settings) {
        if (xhr.status === 401) {
          try {
            const response = JSON.parse(xhr.responseText);
            redirectToLogin(response.message);
          } catch (e) {
            redirectToLogin();
          }
        }
      });
    }
  </script>

  <!-- <script>
    const api = axios.create({
      baseURL: '<?= base_url(); ?>',
      withCredentials: true
    })

    api.interceptors.response.use(
      response => response,
      error => {
        if (error.response.status === 401) {
          localStorage.removeItem('user')
          sessionStorage.removeItem('user')

          showNotification('warning', 'Sesi telah habis, silahkan login kembali')

          setTimeout(() => {
            window.location.href = '<?= base_url('/login'); ?>'
          }, 1500)

          return Promise.reject(error)
        }
        return Promise.reject(error)
      }
    )

    function showNotification(type, message) {
      alert(message)
    }

    async function checkAuthStatus() {
      try {
        const response = await api.get('/auth/me')
        return response.data
      } catch (error) {
        console.error('Auth check error:', error)
        throw error
      }
    }

    async function logout() {
      try {
        await api.post('/auth/logout')
        localStorage.removeItem('user')
        sessionStorage.removeItem('user')
        window.location.href = '<?= base_url('/login'); ?>'
      } catch (error) {
        console.error('Logout error:', error)
        window.location.href = '<?= base_url('/login'); ?>'
      }
    }

    function updateUIForLoggedInUser(user) {
      const userElement = document.querySelector('.user-info')
      if (userElement && user.name) {
        userElement.textContent = user.name
      }

      const roleElement = document.querySelector('.user-role')
      if (roleElement && user.role) {
        roleElement.textContent = user.role
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      if (!window.location.pathname.includes('/login')) {
        checkAuthStatus().then(user => {
          updateUIForLoggedInUser(user)
        }).catch(error => {

        })
      }
    })
  </script> -->

  <!-- JS -->
  <script src="<?= base_url('assets/js/jquery-2.1.4.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/ace.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/ace-elements.min.js') ?>"></script>

  <!-- CUSTOM SCRIPT -->
  <?= $this->renderSection('scripts'); ?>
</body>

</html>