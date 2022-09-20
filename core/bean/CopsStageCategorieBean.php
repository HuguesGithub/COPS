<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsStageCategorieBean
 * @author Hugues
 * @since 1.22.06.02
 * @version 1.22.06.03
 */
class CopsStageCategorieBean extends LocalBean
{
  public function __construct($Obj=null)
  {
    $this->CopsStageCategorie = ($Obj==null ? new CopsStageCategorie() : $Obj);
  }

  /**
   * @since 1.22.05.30
   * @version 1.22.06.03
   */
  public function getStageCategoryDisplay()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-library-stage-categorie.php';
    $strContent  = '';

    $Stages = $this->CopsStageCategorie->getCopsStages();
    while (!empty($Stages)) {
      $Stage = array_shift($Stages);
      $strContent .= $Stage->getBean()->getStageDisplay();
    }

    $attributes = array(
      // Le nom de la compétence
      $this->CopsStageCategorie->getField(self::FIELD_STAGE_CAT_NAME),
      // Liste des Stages de cette Catégorie
      $strContent,

      // Normalement, plus rien après
      '', '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }
}
