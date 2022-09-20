<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsPageBean
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
class CopsPageBean extends WpPageBean
{
  /**
   * @param string $uri
   */
  public function __construct($uri)
  {
    parent::__construct();
    $this->uri = $uri;
  }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  public function getContentPage()
  {
    switch ($this->uri) {
      case '/admin/' :
        $Bean = new AdminCopsPageBean();
        return $Bean->getContentPage();
      break;
      default :
        return $this->getStaticContentPage();
      break;
    }
  }

  /**
   * @since 1.22.04.27
   * @version 1.22.04.27
   */
  private function getStaticContentPage()
  {
    return '';
  }
}
