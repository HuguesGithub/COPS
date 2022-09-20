<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageHomeBean
 * @author Hugues
 * @since 1.22.04.28
 * @version 1.22.04.28
 */
class WpPageHomeBean extends WpPageBean
{

  /**
   * @since 1.22.04.28
   * @version 1.22.04.28
   */
  public function getContentPage()
  {
    return '';
    $Bean = new AdminCopsPageBean();
    return $Bean->getContentPage();
  }

}
