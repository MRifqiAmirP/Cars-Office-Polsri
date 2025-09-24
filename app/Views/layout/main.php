<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?= $title ?? 'My App' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/4.5.0/css/font-awesome.min.css') ?>" />

    <!-- Ace styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/ace.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/ace-skins.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/ace-rtl.min.css') ?>" />
</head>
<body class="no-skin">
    <div class="main-container" id="main-container">

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
</body>
</html>
