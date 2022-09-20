<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * LocalActions
 * @author Hugues
 * @since 1.22.05.19
 * @version 1.22.05.19
 */
class LocalActions extends GlobalActions implements ConstantsInterface
{
  /**
   * Class Constructor
   */
  public function __construct()
  {
  }
  /**
   * @return bool
   */
  public static function isAdmin()
  { return current_user_can('manage_options'); }

  /**
   * @since 1.22.05.19
   * @version 1.22.05.19
   */
  public function getToastContentJson($type, $title, $msg)
  { return '{"toastContent": '.json_encode($this->getToastContent($type, $title, $msg)).'}'; }

  /**
   * @since 1.22.05.19
   * @version 1.22.05.19
   */
  public function getToastContent($type, $title, $msg)
  {
    $strContent  = '<div class="toast fade show bg-'.$type.'">';
    $strContent .= '  <div class="toast-header">';
    $strContent .= '    <i class="fas fa-exclamation-circle mr-2"></i>';
    $strContent .= '    <strong class="me-auto">'.$title.'</strong>';
    $strContent .= '    <small>à l\'instant</small>';
    $strContent .= '  </div>';
    $strContent .= '  <div class="toast-body">';
    $strContent .= $msg;
    $strContent .= '  </div>';
    $strContent .= '</div>';
    return $strContent;
  }
}
