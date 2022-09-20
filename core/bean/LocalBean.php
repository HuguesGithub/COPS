<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalBean
 * @author Hugues
 * @since 1.00.00
 * @version 1.00.00
 */
class LocalBean extends UtilitiesBean
{
  /**
   */
  public function __construct()
  {}
  /**
   * @return int
   */
  public static function getWpUserId()
  { return get_current_user_id(); }
  /**
   * @param string $id
   * @param string $default
   * @return mixed
   */
  public function initVar($id, $default='')
  {
    if (isset($_POST[$id])) {
      return $_POST[$id];
    }
    if (isset($_GET[$id])) {
      return $_GET[$id];
    }
    return $default;
  }

  /**
   * @return string
   */
  public function getPublicHeader()
  {
    $strNavigation = '';
    $strSubmenu    = '';

    //////////////////////////////////////////////////////////////
    // On enrichi le template et on le retourne
    $args = array(
      // La navigation - 1
      $strNavigation,
      // Sousmenu - 2
      $strSubmenu,
    );
    $urlTemplate = 'web/pages/public/fragments/main-header.php';
    return '';//$this->getRender($urlTemplate, $args);
  }

  /**
   * @return string
   */
  public function getPublicFooter()
  {

    $args = array(
      // ajaxUrl - 1
      admin_url('admin-ajax.php'),
    );
    $urlTemplate = 'web/pages/public/public-main-footer.php';
    return $this->getRender($urlTemplate, $args);
  }

}
