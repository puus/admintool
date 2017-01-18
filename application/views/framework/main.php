<?php
    /**
     * Main view template used across the entire appliction
     *
     * To use it you must store the content you want to inject into this template
     * within a buffer, flush the contents of the buffer to a variable then pass
     * that flushed content to CIs load->view methods as the second argument.
     *
     * Example:
     *
     * ```php
     * <?php ob_start(); ?>
     *
     * <div>YOUR CONTENT HERE</div>
     *
     * <?php
     * $content = ob_get_clean();
     *
     * $template = $this->load->view('partials/framework.php', [
     *     'title'       => 'Example Page',
     *     'pagetitle'   => 'Example',
     *     'breadcrumbs' => [
     *         [
     *             'link'  => '/dashboard',
     *             'title' => 'Home'
     *         ]
     *     ],
     *     'section' => 'Example',
     *     'content' => $content
     * ]);
     * ?>
     * ```
     *
     * Also this template expects a number of other pieces of information in
     * addition to the page content as seen above. They're mostly self
     * explanitory, but title refers to the HTML <title> tag and section is used
     * to assign the active class to the correct <li> in our mainnav.php partial
     */
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/jquery/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/toastr/toastr.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/select2/dist/js/select2.full.min.js"></script>

        <script>
            var AdminLTEOptions = {
                sidebarExpandOnHover: false,
            };
        </script>

        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/dist/js/app.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/dropzone/dropzone.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/plugins/iCheck/icheck.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/CryptoJS/sha1.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/CryptoJS/typedarrays.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/vendor/Chartist/chartist.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/main.js"></script>


        <link href="<?= base_url() ?>assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/ionicon/css/ionicons.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/dist/css/AdminLTE.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/dist/css/skins/skin-purple.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/dropzone/dropzone.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/AdminLTE-2.3.6/plugins/iCheck/square/green.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/toastr/toastr.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/vendor/Chartist/chartist.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/css/main.css" rel="stylesheet" />
    </head>
    <body class="fixed skin-purple <?= (isset($collapseNav) && $collapseNav) ? 'sidebar-collapse' : 'sidebar-mini' ?>">
        <div class='wrapper'>
            <header class="main-header">
                <a href="../../index2.html" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">AT</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">AdminTool</span>
                </a>

                <nav class="navbar navbar-static-top">
                  <!-- Sidebar toggle button-->
                  <a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>

                  <?php if(array_key_exists('user', $_SESSION) && $_SESSION['user']): ?>
                  <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <?php if(property_exists($_SESSION['user'], 'avatar') && $_SESSION['user']->avatar): ?>
                                    <img src="<?= $_SESSION['user']->avatar ?>" class="user-image" alt="User Image">
                                <?php else: ?>
                                    <img src="http://placehold.it/160x160" class="user-image" alt="User Image">
                                <?php endif; ?>

                              <span class="hidden-xs"><?= $_SESSION['user']->first_name ?></span>&nbsp;
                              <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                              <!-- User image -->
                              <li class="user-header">
                                  <?php if(property_exists($_SESSION['user'], 'avatar') && $_SESSION['user']->avatar): ?>
                                      <img src="<?= $_SESSION['user']->avatar ?>" class="img-circle" alt="User Image">
                                  <?php else: ?>
                                      <img src="http://placehold.it/160x160" class="img-circle" alt="User Image">
                                  <?php endif; ?>

                                <p>
                                  <?= $_SESSION['user']->first_name . ' ' . $_SESSION['user']->last_name ?>
                                  <small>Member since <?= date('M. Y', strtotime($_SESSION['user']->created)) ?></small>
                                </p>
                              </li>

                              <li class="user-footer">
                                <div class="pull-left">
                                  <a href="/user/profile" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                  <a href="/user/logout" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                              </li>
                            </ul>
                          </li>

                    </ul>
                  </div>
                <?php endif; ?>
                </nav>
            </header>

            <aside class="main-sidebar">
              <div class="sidebar">
                <?php $this->load->view('framework/mainnav.php'); ?>
              </div>
            </aside>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1><?= $pagetitle ?></h1>

                    <?php if(isset($breadcrumbs)): ?>
                        <ol class="breadcrumb">
                            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                            <?php for($x = 0; $x < count($breadcrumbs); $x++): ?>
                                <li class="<?= $x == count($breadcrumbs) ? 'active' : '' ?>"><a href="<?= $breadcrumbs[$x]['link'] ?>"><?= $breadcrumbs[$x]['title'] ?></a></li>
                            <?php endfor; ?>
                        </ol>
                    <?php endif; ?>
                </section>

                <?php if (function_exists('validation_errors')) { echo validation_errors('<div class="error">', '</div>'); } ?>

                <div class='content body'>
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

                    <?= $content ?>
                </div>
            </div>

            <footer class="main-footer">

            </footer>
        </div>

    </body>
</html>
