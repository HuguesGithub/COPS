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
    add_filter('template_include', $this->template_loader(...));
  }

  public function template_loader()
  {
    wp_enqueue_script('jquery');
    return PLUGIN_PATH.'web/pages/publique/main-publique-page.php';
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
    $matches = [];
    $arr = [
        'Actions' => 'actions',
        'Bean' => 'bean',
        'Class' => 'domain',
        'DaoImpl' => 'daoimpl',
        'Enum' => 'enum',
        'Interface' => 'interfaceimpl',
        'Services' => 'services',
        'Utils' => 'utils',
    ];
    $pattern = "/(Actions|Bean|Class|DaoImpl|Enum|Interface|Services|Utils)/";
    if (preg_match($pattern, (string) $classname, $matches)) {
        if (str_contains((string) $classname, '\\')) {
            $classname = substr((string) $classname, strrpos((string) $classname, '\\')+1);
        }

        if (isset($arr[$matches[1]])) {
            $filePath = PLUGIN_PATH.'core/'.$arr[$matches[1]].'/'.$classname.'.php';
            if (file_exists($filePath)) {
                include_once $filePath;
            }
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
    $urlFavicon = plugins_url('/hj-cops/web/rsc/img/icons/favicon.ico');
    add_menu_page($pluginName, $pluginName, $uploadFiles, $urlRoot, '', $urlFavicon);
    if (function_exists('add_submenu_page')) {
      $arrUrlSubMenu = [
        'index'     => 'Index',
        'library'   => 'Bibliothèque',
        'calendar'  => 'Calendrier',
        'equipment' => 'Équipement',
        'meteo'     => 'Météo',
        '-'         => '-----------------'
    ];
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
function dealWithAjax_callback(): never
{
  echo \core\actions\AjaxActions::dealWithAjax();
  die();
}

/**
#######################################################################################
### Gestion des Exceptions
### Description: Met en forme les exceptions
#######################################################################################
*/
function exception_handler($objException)
{
    $strHandler  = '<div class="card border-danger" style="max-width: 100%;margin-right: 15px;">';
    $strHandler .= '  <div class="card-header bg-danger text-white"><strong>';
    $strHandler .= $objException->getMessage().'</strong></div>';
    $strHandler .= '  <div class="card-body text-danger">';
    $strHandler .= '    <p>Une erreur est survenue dans le fichier <strong>'.$objException->getFile();
    $strHandler .= '</strong> à la ligne <strong>'.$objException->getLine().'</strong>.</p>';
    $strHandler .= '    <ul class="list-group">';

    $arrTraces = $objException->getTrace();
    foreach ($arrTraces as $trace) {
        $strHandler .= '<li class="list-group-item">Fichier <strong>'.$trace['file'];
        $strHandler .= '</strong> ligne <em>'.$trace['line'].'</em> :<br>';
        if (isset($trace['args'])) {
            if (is_array($trace['args'])) {
                $strHandler .= $trace['function'].'()</li>';
            } else {
                $strHandler .= $trace['class'].$trace['type'].$trace['function'];
                $strHandler .= '('.implode(', ', $trace['args']).')</li>';
            }
        }
    }

    $strHandler .= '    </ul>';
    $strHandler .= '  </div>';
    $strHandler .= '  <div class="card-footer"></div>';
    $strHandler .= '</div>';

    echo $strHandler;
}
set_exception_handler('exception_handler');
