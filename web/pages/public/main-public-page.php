<!DOCTYPE html>
<html lang="fr">
  <head>
<?php
  $PageBean = CopsPageBean::getPageBean();
  define('COPS_SITE_URL', 'https://cops.jhugues.fr/');
  define('PLUGINS_MYCOMMON', COPS_SITE_URL.'wp-content/plugins/mycommon/');
  define('PLUGINS_COPS', COPS_SITE_URL.'wp-content/plugins/hj-cops/');
  date_default_timezone_set('Europe/Paris');
//  wp_head();
?>
    <meta charset='utf-8'/>
    <meta content='width=device-width, initial-scale=1' name='viewport'/>
    <title>C.O.P.S. | Central Organisation for Public Security</title>
  <!-- COPS style -->
    <link rel="stylesheet" media="all" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/cops.css" media="all" />
  <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo PLUGINS_COPS; ?>web/rsc/fontawesome-6.1.1/css/all.min.css">
  <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://gones.jhugues.fr/wp-content/plugins/hj-gones/web/rsc/css/admin-transverse.min.css">
  </head>
  <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed"><!-- sidebar-collapse-->
    <div id="page">
      <!-- Start Main -->
      <div id="main" style="overflow: hidden;">
        <!-- Start Header -->
<?php
  if ($PageBean->hasHeader) {
    echo $PageBean->getPublicHeader();
  }
?>
        <!-- Finish Header -->
        <!-- Start Middle -->
<?php
  echo $PageBean->getContentPage();
?>
        <!-- Finish Middle -->
      </div>
        <!-- Finish Main -->
        <!-- Start Footer -->
<?php
  if ($PageBean->hasFooter) {
    echo $PageBean->getPublicFooter();
  }
?>
        <!-- Finish Footer -->
    </div>
    <!-- jQuery -->
    <script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery-ui.min.js'></script>
    <!-- Bootstrap -->
    <script src="<?php echo PLUGINS_COPS; ?>web/rsc/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery.overlayScrollbars.min.js"></script>
    <!-- Theme script -->
    <script src="<?php echo PLUGINS_COPS; ?>web/rsc/js/adminlte.js"></script>
    <script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/cops.js'></script>
  </body>
</html>
