<?php
/**
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
  define('COPS_SITE_URL', 'https://cops.jhugues.fr/');
  define('PLUGINS_MYCOMMON', COPS_SITE_URL.'wp-content/plugins/mycommon/');
  define('PLUGINS_COPS', COPS_SITE_URL.'wp-content/plugins/hj-cops/');
  if (!defined('ABSPATH')) {
    die('Forbidden');
  }
?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo PLUGINS_COPS; ?>web/rsc/fontawesome-6.1.1/css/all.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo PLUGINS_MYCOMMON; ?>web/rsc/css/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/cops.css"/>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/open-iconic-bootstrap.css"/>
<?php
  global $Cops;
  if (empty($Cops)) {
    $Cops = new Cops();
  }
  $objAdminPageBean = new \core\bean\AdminPageBean();
  echo $objAdminPageBean->getContentPage();
?>
<script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo PLUGINS_MYCOMMON; ?>web/rsc/js/jquery-ui.min.js'></script>
<script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/cops.js'></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
