<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe AdminCopsPlayerPageBean
 * @author Hugues
 * @since 1.22.04.27
 * @version 1.22.04.27
 */
class AdminCopsPlayerPageBean extends AdminCopsPageBean implements ConstantsInterface
{
  /**
   * @return string
   * @version 1.22.04.27
   * @since 1.22.04.27
   */
  public function getBoard()
  {
    $isCreation = true;
    switch ($this->urlParams[self::CST_SUBONGLET]) {
      case 'player-carac' :
        $strContent = $this->CopsPlayer->getBean()->getCopsPlayerCarac($isCreation);
      break;
      case 'player-comps' :
        $strContent = $this->CopsPlayer->getBean()->getCopsPlayerComps();
      break;
      default :
        $strContent = '';
      break;
    }

    // Soit on est loggué et on affiche le contenu du bureau du cops
    $urlTemplate = 'web/pages/public/public-board.php';
    $attributes = array(
      // La sidebar
      $this->getSidebar(),
      // Le contenu de la page
      $strContent,

      '', '', '', '', '', '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }


}
