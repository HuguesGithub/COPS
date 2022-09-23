<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminPageBean
 * @author Hugues
 * @version 1.22.09.05
 * @since 1.22.09.05
 */
class AdminPageBean extends UtilitiesBean
{

  /**
   * Class Constructor
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public function __construct()
  {
    $this->analyzeUri();
  }

  /**
   * @return bool
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public static function isAdmin()
  { return current_user_can('manage_options'); }

  /**
   * @return string
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public function analyzeUri()
  {
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, '?');
    if ($pos!==false) {
      $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
      if (!empty($arrParams)) {
        foreach ($arrParams as $param) {
          list($key, $value) = explode('=', $param);
          $this->urlParams[$key] = $value;
        }
      }
      $uri = substr($uri, 0, $pos-1);
    }
    $pos = strpos($uri, '#');
    if ($pos!==false) {
      $this->anchor = substr($uri, $pos+1, strlen($uri));
    }
    if (isset($_POST)) {
      foreach ($_POST as $key => $value) {
        $this->urlParams[$key] = $value;
      }
    }
    return $uri;
  }


  /**
   * @return string
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public function getContentPage()
  {
    if (self::isAdmin() || current_user_can('editor')) {
      try {
        switch ($this->urlParams[self::CST_ONGLET]) {
          case 'meteo' : //self::PAGE_ADMIN_CALENDRIER :
            $returned = AdminPageMeteoBean::getStaticContentPage($this->urlParams);
          break;
          case 'index' : //self::PAGE_ADMIN_CALENDRIER :
            $returned = AdminPageIndexBean::getStaticContentPage($this->urlParams);
          break;
          case 'calendrier' : //self::PAGE_ADMIN_CALENDRIER :
            $returned = AdminPageCalendrierBean::getStaticContentPage($this->urlParams);
          break;
          default       :
            $returned = $this;
          break;
        }
      } catch (\Exception $Exception) {
        $returned = 'Error';
      }
    }
    return $returned;
  }

  /**
   * Retourne le contenu de l'interface
   * @return string
   * @version 1.22.09.05
   * @since 1.22.09.05
   */
  public function getBoard()
  {
    return '';
  }

}