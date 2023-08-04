<?php
use core\bean\WpPageBean;

$objPageBean = WpPageBean::getPageBean();
define('COPS_SITE_URL', 'https://cops.jhugues.fr/');
define('PLUGINS_MYCOMMON', COPS_SITE_URL.'wp-content/plugins/mycommon/');
define('PLUGINS_COPS', COPS_SITE_URL.'wp-content/plugins/hj-cops/');
date_default_timezone_set('Europe/Paris');
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset='utf-8'/>
    <meta content='width=device-width, initial-scale=1' name='viewport'/>
    <title>C.O.P.S. | Central Organisation for Public Security</title>
    <!-- Bootstrap style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <!-- COPS style -->
    <link rel="stylesheet" media="all" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/cops.css" media="all" />
  <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo PLUGINS_COPS; ?>web/rsc/fontawesome-6.1.1/css/all.min.css">
  <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://gones.jhugues.fr/wp-content/plugins/hj-gones/web/rsc/css/admin-transverse.min.css">
    <link rel="icon" type="image/png" href="<?php echo PLUGINS_COPS; ?>web/rsc/img/favicon-32x32.png">
  </head>
  <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed"><!-- sidebar-collapse-->
    <div id="page">
      <!-- Start Main -->
      <div id="main" style="overflow: hidden;">
        <!-- Start Header -->
<?php
  if ($objPageBean->hasHeader) {
    echo $objPageBean->getPublicHeader();
  }
?>
        <!-- Finish Header -->
        <!-- Start Middle -->
<?php
  echo $objPageBean->getContentPage();
?>
        <!-- Finish Middle -->
      </div>
        <!-- Finish Main -->
        <!-- Start Footer -->
<?php
  if ($objPageBean->hasFooter) {
    echo $objPageBean->getPublicFooter();
  }
?>
        <!-- Finish Footer -->
    </div>
    <!-- jQuery -->
    <script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery-ui.min.js'></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <!-- overlayScrollbars -->
    <script src="<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery.overlayScrollbars.min.js"></script>
    <!-- Theme script -->
    <script src="<?php echo PLUGINS_COPS; ?>web/rsc/js/adminlte.js"></script>
    <script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/cops.js'></script>
  </body>
</html>
