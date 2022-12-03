<!DOCTYPE html>
<html lang="en">

<head>
    <script src="<?= base_url() ?>OneSignalSDKWorker.js"></script>
    <script>
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "51832f7c-c8bf-45da-b2a2-de9926641f7d",
                safari_web_id: "web.onesignal.auto.064b44a8-1dd7-4e10-9d87-452ef5b9c9dd",
                notifyButton: {
                    enable: true,
                },
            });
        });
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.png">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>template/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
    <!-- Site wrapper -->
    <div class="wrapper">
        <?php $this->load->view('partials/navbar'); ?>
        <?php $this->load->view('partials/sidebar'); ?>