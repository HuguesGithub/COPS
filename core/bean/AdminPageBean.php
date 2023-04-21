<?php
namespace core\bean;

/**
 * Classe AdminPageBean
 * @author Hugues
 * @since 1.22.09.05
 * @version 1.23.04.20
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
  public function getContentPage()
  {
    $returned = null;
    if (static::isAdmin() || current_user_can('editor')) {
      try {
        $returned = match ($this->urlParams[self::CST_ONGLET]) {
            'meteo' => AdminPageMeteoBean::getStaticContentPage($this->urlParams),
            'index' => AdminPageIndexBean::getStaticContentPage($this->urlParams),
            'calendrier' => AdminPageCalendrierBean::getStaticContentPage($this->urlParams),
            default => $this,
        };
      } catch (\Exception) {
        $returned = 'Error APB';
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
