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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo PLUGINS_MYCOMMON; ?>web/rsc/css/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo PLUGINS_COPS; ?>web/rsc/css/cops.css"/>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo PLUGINS_COPS; ?>web/rsc/open-iconic-bootstrap.css"/>
<?php
  global $Cops;
  if (empty($Cops)) {
    $Cops = new Cops();
  }
  $AdminPageBean = new AdminPageBean();
  echo $AdminPageBean->getContentPage();
?>
<script type='text/javascript' src='<?php echo PLUGINS_MYCOMMON; ?>web/rsc/js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo PLUGINS_MYCOMMON; ?>web/rsc/js/jquery-ui.min.js'></script>
<script type='text/javascript' src='<?php echo PLUGINS_COPS; ?>web/rsc/js/cops.js'></script>
