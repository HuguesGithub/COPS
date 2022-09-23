<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * CopsStageBean
 * @author Hugues
 * @since 1.22.06.03
 * @version 1.22.06.03
 */
class CopsStageBean extends UtilitiesBean
{
  public function __construct($Obj=null)
  {
    $this->CopsStageServices = new CopsStageServices();
    $this->CopsStage = ($Obj==null ? new CopsStage() : $Obj);
  }

  /**
   * @since 1.22.06.03
   * @version 1.22.06.03
   */
  public function getStageDisplay()
  {
    $urlTemplate = 'web/pages/public/fragments/public-fragments-article-library-stage.php';

    $strCapacitesSpeciales = '';
    $CapsSpecs = $this->CopsStageServices->getStageSpecs($this->CopsStage->getField(self::FIELD_ID));
    while (!empty($CapsSpecs)) {
      $CapSpe = array_shift($CapsSpecs);
      $strCapacitesSpeciales .= '<dt>'.$CapSpe->getField(self::FIELD_SPEC_NAME).'</dt>';
      $strCapacitesSpeciales .= '<dd>'.$CapSpe->getField(self::FIELD_SPEC_DESC).'</dd>';
    }

    $attributes = array(
      // Le nom du stage
      $this->CopsStage->getField(self::FIELD_STAGE_LIBELLE),
      // Le niveau du stage
      'lvl'.$this->CopsStage->getField(self::FIELD_STAGE_LEVEL),
      // Les Pré Requis
      $this->CopsStage->getField(self::FIELD_STAGE_REQUIS),
      // Le Cumul éventuel
      $this->CopsStage->getField(self::FIELD_STAGE_CUMUL),
      // La Description
      $this->CopsStage->getField(self::FIELD_STAGE_DESC),
      // Le Bonus éventuel
      $this->CopsStage->getField(self::FIELD_STAGE_BONUS),
      // La liste des capacités spéciales
      '<dl>'.$strCapacitesSpeciales.'</dl>',
      //
      '', '', '', '', '',
    );
    return $this->getRender($urlTemplate, $attributes);
  }
}
