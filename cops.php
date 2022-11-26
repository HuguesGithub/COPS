<?php
/**
 * Plugin Name: HJ - COPS
 * Description: Site Web COPS
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_PACKAGE', 'Cops');
session_start([]);

class Cops
{
  public function __construct()
  {
    add_filter('template_include', array($this,'template_loader'));
  }

  public function template_loader()
  {
    wp_enqueue_script('jquery');
    return PLUGIN_PATH.'web/pages/public/main-public-page.php';
  }
}
$Cops = new Cops();

/**
#######################################################################################
###  Autoload des classes utilisées
### Description: Gestion de l'inclusion des classes
#######################################################################################
*/
spl_autoload_register(PLUGIN_PACKAGE.'_autoloader');
function cops_autoloader($classname)
{
  $pattern = "/(Bean|DaoImpl|Dao|Services|Actions|Utils|Interface)/";
  preg_match($pattern, $classname, $matches);
  if (isset($matches[1])) {
    switch ($matches[1]) {
      case 'Actions' :
      case 'Bean' :
      case 'Dao' :
      case 'DaoImpl' :
      case 'Interface' :
      case 'Services' :
      case 'Utils' :
        if (file_exists(PLUGIN_PATH.'core/'.strtolower($matches[1]).'/'.$classname.'.php')) {
          include_once(PLUGIN_PATH.'core/'.strtolower($matches[1]).'/'.$classname.'.php');
        } elseif (file_exists(PLUGIN_PATH.'../mycommon/core/'.strtolower($matches[1]).'/'.$classname.'.php')) {
          include_once(PLUGIN_PATH.'../mycommon/core/'.strtolower($matches[1]).'/'.$classname.'.php');
        }
      break;
      default :
        // On est dans un cas o? on a match? mais pas pr?vu le traitement...
      break;
    }
  } else {
    $classfile = sprintf('%score/domain/%s.class.php', PLUGIN_PATH, str_replace('_', '-', $classname));
    if (!file_exists($classfile)) {
      $classfile = sprintf('%s../mycommon/core/domain/%s.class.php', PLUGIN_PATH, str_replace('_', '-', $classname));
    }
    if (file_exists($classfile)) {
      include_once($classfile);
    }
  }
}

/**
#######################################################################################
###  Ajout d'une entrée dans le menu d'administration.
#######################################################################################
**/
function cops_menu()
{
  $urlRoot = 'hj-cops/admin_manage.php';
  if (function_exists('add_menu_page')) {
    $uploadFiles = 'upload_files';
    $pluginName = 'COPS';
    add_menu_page($pluginName, $pluginName, $uploadFiles, $urlRoot, '', plugins_url('/hj-cops/web/rsc/img/icons/favicon.ico'));
    if (function_exists('add_submenu_page')) {
      $arrUrlSubMenu = array(
        'index' => 'Index',
        'calendrier' => 'Calendrier',
        'meteo' => 'Météo',
        '-'              => '-----------------',
      );
      foreach ($arrUrlSubMenu as $key => $value) {
        $urlSubMenu = $urlRoot.'&amp;onglet='.$key;
        add_submenu_page($urlRoot, $value, $value, $uploadFiles, $urlSubMenu, $key);
      }
    }
  }
}
add_action('admin_menu', 'cops_menu');
/**
#######################################################################################
### Ajout d'une action Ajax
### Description: Entrance point for Ajax Interaction.
#######################################################################################
*/
add_action('wp_ajax_dealWithAjax', 'dealWithAjax_callback');
add_action('wp_ajax_nopriv_dealWithAjax', 'dealWithAjax_callback');
function dealWithAjax_callback()
{
  echo AjaxActions::dealWithAjax();
  die();
}
