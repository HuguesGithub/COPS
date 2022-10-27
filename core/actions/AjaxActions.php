<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AjaxActions
 * @author Hugues
 * @since 1.22.05.19
 * @version 1.22.05.19
 */
class AjaxActions extends LocalActions
{

  /**
   * Gère les actions Ajax
   * @since 1.22.05.19
   * @version 1.22.05.19
   */
  public static function dealWithAjax()
  {
    switch ($_POST[self::AJAX_ACTION]) {
      case 'saveData' :
        $returned = CopsPlayerActions::dealWithStatic($_POST);
      break;
      case 'csvExport':
          $returned = CopsIndexActions::dealWithStatic($_POST);
          break;
      default :
        $saisie = stripslashes($_POST[self::AJAX_ACTION]);
        $returned  = 'Erreur dans AjaxActions le $_POST['.self::AJAX_ACTION.'] : '.$saisie.'<br>';
      break;
    }
    return $returned;
  }

}
