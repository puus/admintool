<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link href="<?= base_url() ?>assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/ionicon/css/ionicons.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/dist/css/AdminLTE.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/dist/css/skins/skin-purple.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/plugins/iCheck/square/green.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/toastr/toastr.min.css" rel="stylesheet" />

        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/jquery/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/toastr/toastr.min.js"></script>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
          <div class="login-logo">
            <a href="#"><b>Admin</b>Tool</a>
          </div>
          <!-- /.login-logo -->
          <div class="login-box-body">
            <?= $content ?>
          </div>
          <!-- /.login-box-body -->
        </div>

        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/jquery/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/dist/js/app.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/plugins/iCheck/icheck.min.js"></script>

        <!-- Replace standard radio buttons & checkboxs -->
        <script type="text/javascript">
            $(document).ready(function(){
              $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
              });
            });
        </script>

        <!-- Display success and error toast notifications -->
        <?php if(array_key_exists('success', $_SESSION)): ?>
            <script>
                toastr.success("<?= $_SESSION['success'] ?>");
            </script>
        <?php endif; ?>
        <?php if(array_key_exists('error', $_SESSION)): ?>
            <script>
                toastr.error("<?= $_SESSION['error'] ?>");
            </script>
        <?php endif; ?>
    </body>
</html>
